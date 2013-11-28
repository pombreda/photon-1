<?php

namespace Orangehill\Photon;

use Orangehill\Photon\Library\Creator\MigrationGenerator;

class SettingsController extends \BaseController
{

    /**
     * $data is passed to settings.blade.php view file
     *
     * @var array
     */
    public $data = array(
        'breadcrumbs'       => array(
            array(
                'url'    => 'javascript:;',
                'anchor' => 'Settings'
            )
        ),
        'moduleId'          => null,
        'isFolder'          => false,
        'nextAutoIncrement' => null,
        'folders'           => null,
        'module'            => array(),
        'fields'            => array(),
        'fieldsJson'        => array(),
    );
    public $settingsValidate;

    public function __construct(SettingsValidator $settingsValidator)
    {
        $this->settingsValidate = $settingsValidator;
    }

    /**
     * Default GET method
     *
     * @param  int $id
     *
     * @return Response
     */
    public function getIndex($id = null)
    {
        // Initialize the Settings Page
        $this->init($id);

        // Show the Settings Page
        return \View::make('photon::admin.settings', $this->data);
    }

    /**
     * Init function
     *
     * @param int $id
     */
    public function init($id = null)
    {
        $this->data['moduleId']          = \Input::get('module_id') ? : $id;
        $this->data['nextAutoIncrement'] = AdminController::getNextAutoIncrement('fields');
        $this->data['folders']           = Folder::get();

        if (is_numeric($this->data['moduleId'])) {
            $this->data['module']     = Module::find($id);
            $this->data['fields']     = $this->data['module']->fields;
            $this->data['fieldsJson'] = $this->data['fields']->toJson();
            $this->data['isFolder']   = isset($this->data['module']->is_folder) && $this->data['module']->is_folder == 1;
        }
    }

    public function postValidate()
    {
        var_dump("Yay, posted here");
    }

    public function getValidate()
    {
        var_dump("Yay, posted here");
    }

    /**
     * Default POST method
     *
     * @param  int $id
     *
     * @return Response
     */
    public function postIndex($id = null)
    {
        // Initialize the Settings Page
        $this->init($id);

        // Extract all field IDs
        $fieldIds = $this->extractFieldIds(\Input::all());

        $validate = $this->settingsValidate->validate($fieldIds, $id);

        if (\Request::ajax()) {
            if ($validate['fails']) {
                return $this->jsonResponse('error', $validate['message']);
            }

            // For AJAX requests run save function in verbose mode (only print pending changes - don't make any changes)
            $verbose = $this->save($fieldIds, true);

            // if $verbose is false there are no changes to be saved
            if (!$verbose) {
                return $this->jsonResponse('error', 'No changes detected');
            }

            return $this->jsonResponse('success', $verbose);
        } else {
            if ($validate !== true) {
                throw new \UnexpectedValueException($validate);
            }

            // For regular POST requests save data
            return $this->save($fieldIds);
        }
    }

    /**
     * Loops through all received fields (\Input::all()) to extract all field IDs
     *
     * @return array Array of IDs
     */
    protected function extractFieldIds($input)
    {
        $output = array();
        if (is_array($input)) {
            foreach ($input as $key => $var) {
                if (substr($key, 0, 8) === "field_id") {
                    $output[] = str_replace('field_id', '', $key);
                }
            }
        }

        return $output;
    }

    /**
     * Returns an array formatted as JSON response
     * Conforms to http://labs.omniti.com/labs/jsend API response standards
     *
     * @param  string $status (success, fail, error)
     * @param  array  $data   data array
     *
     * @return string           JSON formatted response
     */
    protected function jsonResponse($status = 'success', $data = null)
    {
        $output['status'] = $status;
        if ($status == 'success' || $status == 'fail') {
            $output['data'] = $data;
        }
        if ($status == 'error') {
            $output['message'] = $data;
        }

        return \Response::json($output);
    }

    /**
     * Saves submitted and validated data
     *
     * @param  $verbose boolean $verbose If set to true function will only specify pending changes without making any
     *
     * @return array
     */
    protected function save($fieldIds, $verbose = false)
    {
        $report  = '';

        // Instantiate Migration Generator Class (Laravel 4 Generator Module Wrapper)
        $generator = new MigrationGenerator();

        // Check if module_name is new or existing one
        if (is_numeric(\Input::get('module_id'))) {
            // Get module data
            $module = Module::find(\Input::get('module_id'));

            // Handle Folder module type
            if (\Input::get('is_folder') == '1') {

                return $this->updateFolderModuleType($module, $verbose);
            }

            // Get id's of fields that are stored in the database
            $existingFieldIds = array_fetch($module->fields()->get()->toArray(), 'id');

            // Check if remove module request is received
            if (\Input::get('remove_request') == 1) {
                $report .= $this->reportSectionTitleGenerator('remove', 'module', \Input::get('name'));

                // If in verbose mode return report array
                if ($verbose) {
                    return array('report' => $report);
                }

                // Populate migrationFieldsDropped array (needed for php artisan migrate:rollback)
                $migrationFieldsDropped = array();

                $existingFieldIds = $module->fields()->get();

                foreach ($existingFieldIds as $field) {
                    $migrationFieldsDropped[$field->column_name] = $field->column_type;
                }

                // Generate migration file
                $generator->destroy($module->table_name, $migrationFieldsDropped);

                // Run the migrations
                if (\Input::get('run-migrations') == 1) {
                    \Artisan::call('migrate');
                }

                // Remove the module (also removes orphans from fields table)
                $module->delete();

                // Notify about successful data removal
                \Session::flash('success', 'Data has been removed.');

                // Redirect to settings page
                return \Redirect::to('/admin/settings/');
            }

            // Create an array of deleted fields, if any
            $deletedFieldIds = array_diff($existingFieldIds, $fieldIds);

            // Create an array of new fields, if any
            $newFieldIds = array_diff($fieldIds, $existingFieldIds);

            // Create an array of edited fields
            $editedFieldIds = array_diff($fieldIds, $deletedFieldIds, $newFieldIds);

            if (!$module->get()->isEmpty()) {

                $moduleHasChanged = false;

                // Updating existing module
                $report .= $this->reportSectionTitleGenerator('change', 'module', \Input::get('name'));

                if ($module->name != \Input::get('module_name')) {
                    $report .= $this->reportGenerator('set', 'name', \Input::get('name'), $module->name);
                    $module->name     = \Input::get('name');
                    $moduleHasChanged = true;
                }

                if ($module->parent_module != \Input::get('parent_module')) {
                    $report .= $this->reportGenerator('set', 'parent module', \Input::get('parent_module'),
                        $module->parent_module
                    );
                    $module->parent_module = \Input::get('parent_module');
                    $moduleHasChanged      = true;
                }

                if ($module->nestable != \Input::get('nestable')) {
                    $report .= $this->reportGenerator('set', 'nestable', \Input::get('nestable'), $module->nestable);
                    $module->nestable = \Input::get('nestable');
                    $moduleHasChanged = true;
                }

                $report .= $moduleHasChanged ? '' : '<br/>';

                // Save changes to module table
                if (!$verbose) {
                    $module->save();
                }

                // Process new fields for an existing module
                if (count($newFieldIds) > 0) {
                    $migrationFields = array();

                    foreach ($newFieldIds as $newFieldId) {
                        // Generate a report line
                        $report .= $this->insertFieldReport($newFieldId);

                        // Insert a new record in fields table
                        if (!$verbose) {
                            $tmp = array();

                            $tmp['module_id']      = \Input::get('module_id');
                            $tmp['field_name']     = \Input::get('field_name' . $newFieldId);
                            $tmp['field_type']     = \Input::get('field_type' . $newFieldId);
                            $tmp['relation_table'] = \Input::get('relation_table' . $newFieldId);
                            $tmp['column_name']    = \Input::get('column_name' . $newFieldId);
                            $tmp['column_type']    = \Input::get('column_type' . $newFieldId);
                            $tmp['tooltip_text']   = \Input::get('tooltip_text' . $newFieldId);

                            $newField = Field::create($tmp);

                            // Populate migrationFields array
                            $migrationFields[\Input::get('column_name' . $newFieldId
                            )] = \Input::get('column_type' . $newFieldId);
                        }

                        $moduleHasChanged = true;
                    }

                    // Generate migration files
                    if (!$verbose) {
                        $generator->add($module->table_name, $migrationFields);
                    }
                }

                // Process edited fields for an existing module
                if (count($editedFieldIds) > 0) {

                    $migrationFields        = array();
                    $migrationFieldsDropped = array();


                    foreach ($editedFieldIds as $fieldId) {

                        $fieldHasChanged = false;
                        $fieldReport     = '';

                        $field = $module->fields()->find($fieldId);

                        // Populate migrationFields and migrationFieldsDropped arrays
                        if ($field->column_name != \Input::get('column_name' . $fieldId
                            ) OR $field->column_type != \Input::get('column_type' . $fieldId)
                        ) {
                            $migrationFields[\Input::get('column_name' . $fieldId
                            )]                                           = \Input::get('column_type' . $fieldId);
                            $migrationFieldsDropped[$field->column_name] = $field->column_type;
                        }

                        if ($field->field_name != \Input::get('field_name' . $fieldId)) {
                            $fieldReport .= $this->reportGenerator('set', 'field name',
                                \Input::get('field_name' . $fieldId), $field->field_name
                            );
                            $field->field_name = \Input::get('field_name' . $fieldId);
                            $fieldHasChanged   = true;
                        }

                        if ($field->field_type != \Input::get('field_type' . $fieldId)) {
                            $fieldReport .= $this->reportGenerator('set', 'field type',
                                \Input::get('field_type' . $fieldId), $field->field_type
                            );
                            $field->field_type = \Input::get('field_type' . $fieldId);
                            $fieldHasChanged   = true;
                        }

                        if ($field->relation_table != \Input::get('relation_table' . $fieldId)) {
                            $fieldReport .= $this->reportGenerator('set', 'relation table',
                                \Input::get('relation_table' . $fieldId), $field->relation_table
                            );
                            $field->relation_table = \Input::get('relation_table' . $fieldId);
                            $fieldHasChanged       = true;
                        }

                        if ($field->column_name != \Input::get('column_name' . $fieldId)) {
                            $fieldReport .= $this->reportGenerator('set', 'column name',
                                \Input::get('column_name' . $fieldId), $field->column_name
                            );
                            $field->column_name = \Input::get('column_name' . $fieldId);
                            $fieldHasChanged    = true;
                        }

                        if ($field->column_type != \Input::get('column_type' . $fieldId)) {
                            $fieldReport .= $this->reportGenerator('set', 'column type',
                                \Input::get('column_type' . $fieldId), $field->column_type
                            );
                            $field->column_type = \Input::get('column_type' . $fieldId);
                            $fieldHasChanged    = true;
                        }

                        if ($field->tooltip_text != \Input::get('tooltip_text' . $fieldId)) {
                            $fieldReport .= $this->reportGenerator('set', 'tooltip text',
                                \Input::get('tooltip_text' . $fieldId), $field->tooltip_text
                            );
                            $field->tooltip_text = \Input::get('tooltip_text' . $fieldId);
                            $fieldHasChanged     = true;
                        }

                        if ($fieldHasChanged) {
                            $fieldReport = $this->reportSectionTitleGenerator('change', 'field', $field->field_name
                                ) . $fieldReport;
                            $fieldReport .= '<br/>';
                            $report .= $fieldReport;
                            $moduleHasChanged = true;
                        }
                        if (!$verbose AND $fieldHasChanged) {
                            $field->save();
                        }
                    }

                    // Generate migration files
                    if (!$verbose AND $fieldHasChanged) {
                        // First drop fields with old column_type
                        $generator->remove($module->table_name, $migrationFieldsDropped);

                        // Wait 1 second so that remove migration is executed before add (filenaming issue)
                        sleep(1);

                        // Than add fields with changed column_type
                        $generator->add($module->table_name, $migrationFields);
                    }
                }

                // Process deleted fields
                if (count($deletedFieldIds) > 0) {
                    $migrationFieldsDropped = array();

                    foreach ($deletedFieldIds as $deletedFieldId) {
                        $field = $module->fields()->find($deletedFieldId);
                        $report .= $this->reportSectionTitleGenerator('remove', 'field', $field->field_name);

                        // Populate migrationFieldsDropped array
                        $migrationFieldsDropped[$field->column_name] = $field->column_type;

                        if (!$verbose) {
                            $field->delete();
                        }
                        $moduleHasChanged = true;
                    }

                    // Generate migration files
                    if (!$verbose) {
                        $generator->remove($module->table_name, $migrationFieldsDropped);
                    }
                }

                // This will yield 'no changes detected' message
                if (!$moduleHasChanged) {
                    return false;
                }

                // If in verbose mode return report array
                if ($verbose) {
                    return array('report' => $report);
                }

                // Run the migrations
                if (\Input::get('run-migrations') == 1) {
                    \Artisan::call('migrate');
                }

                // Notify about successful data entry
                \Session::flash('success', 'Data has been saved.');

                // Redirect to module settings page
                return \Redirect::to('/admin/settings/' . \Input::get('module_id'));
            }
        }

        // Inserting a new model
        $report .= $this->reportSectionTitleGenerator('create', 'module');
        $report .= $this->reportGenerator('set', 'module name', \Input::get('name'));
        $report .= $this->reportGenerator('set', 'table name', \Input::get('table_name'));
        $report .= $this->reportGenerator('set', 'parent module', \Input::get('parent_module'));
        $report .= $this->reportGenerator('set', 'nestable', \Input::get('nestable'));
        $report .= '<br/>';

        // Insert a new record in modules table, and get it's ID
        if (!$verbose) {
            $newModule = Module::create(\Input::all());
        }

        $migrationFields = array();

        // Inserting new fields
        foreach ($fieldIds as $fieldId) {

            // Generate a report line
            $report .= $this->insertFieldReport($fieldId);

            // Insert a new record in fields table
            if (!$verbose) {
                $tmp = array();

                $tmp['module_id']      = $newModule->id;
                $tmp['field_name']     = \Input::get('field_name' . $fieldId);
                $tmp['field_type']     = \Input::get('field_type' . $fieldId);
                $tmp['relation_table'] = \Input::get('relation_table' . $fieldId);
                $tmp['column_name']    = \Input::get('column_name' . $fieldId);
                $tmp['column_type']    = \Input::get('column_type' . $fieldId);
                $tmp['tooltip_text']   = \Input::get('tooltip_text' . $fieldId);

                $newField = Field::create($tmp);

                // Populate migrationFields array
                $migrationFields[\Input::get('column_name' . $fieldId)] = \Input::get('column_type' . $fieldId);
            }
        }

        // If in verbose mode return report array
        if ($verbose) {
            return array('report' => $report);
        }

        // Generate migration files (Only if module type is not folder)
        if (\Input::get('is_folder') !== '1') {
            $generator->create(\Input::get('table_name'), $migrationFields);
        }

        // Run the migrations (Only if module type is not folder)
        if (\Input::get('run-migrations') == 1 && \Input::get('is_folder') !== '1') {
            \Artisan::call('migrate');
        }

        // Notify about successful data entry
        \Session::flash('success', 'Data has been saved.');

        // Redirect to new module settings page
        return \Redirect::to('/admin/settings/' . $newModule->id);
    }

    protected function updateFolderModuleType($module, $verbose)
    {

        $report = '';

        // Check if remove module request is received
        if (\Input::get('remove_request') == 1) {

            $report .= $this->reportSectionTitleGenerator('remove', 'module', \Input::get('name'));

            // If in verbose mode return report array
            if ($verbose) {
                return array('report' => $report);
            }

            // Remove the module (also removes orphans from fields table)
            $module->delete();

            // Notify about successful data removal
            \Session::flash('success', 'Data has been removed.');

            // Redirect to settings page
            return \Redirect::to('/admin/settings/');
        }

        if (!$module->get()->isEmpty()) {

            $moduleHasChanged = false;

            // Updating existing module
            $report .= $this->reportSectionTitleGenerator('change', 'module', \Input::get('name'));

            if ($module->name != \Input::get('name')) {
                $report .= $this->reportGenerator('set', 'module name', \Input::get('name'), $module->name);
                $module->name     = \Input::get('name');
                $moduleHasChanged = true;
            }

            if ($module->parent_module != \Input::get('parent_module')) {
                $report .= $this->reportGenerator('set', 'parent module', \Input::get('parent_module'),
                    $module->parent_module
                );
                $module->parent_module = \Input::get('parent_module');
                $moduleHasChanged      = true;
            }

            if ($module->nestable != \Input::get('nestable')) {
                $report .= $this->reportGenerator('set', 'nestable', \Input::get('nestable'), $module->nestable);
                $module->nestable = \Input::get('nestable');
                $moduleHasChanged = true;
            }

            $report .= $moduleHasChanged ? '' : '<br/>';

            // Save changes to module table
            if (!$verbose) {
                $module->save();
            }

            // This will yield 'no changes detected' message
            if (!$moduleHasChanged) {
                return false;
            }

            // If in verbose mode return report array
            if ($verbose) {
                return array('report' => $report);
            }

            // Notify about successful data entry
            \Session::flash('success', 'Data has been saved.');

            // Redirect to module settings page
            return \Redirect::to('/admin/settings/' . \Input::get('module_id'));
        }
    }

    /**
     * Generates report section titles
     *
     * @param $action     variable (create, change, remove)
     * @param $target     variable (module, field)
     * @param $targetName variable (module or field Name)
     *
     * @return string
     */
    protected function reportSectionTitleGenerator($action, $target, $targetName = '')
    {
        switch ($action) {
            case "create":
                $label = "label-success";
                break;
            case "change":
                $label = "label-warning";
                break;
            case "remove":
                $label = "label-important";
                break;
        };

        $output = '<span class="label ' . $label . '">' . strtoupper($action) . ' ' . strtoupper($target) . ':</span>';

        if ($targetName != '') {
            $output .= '&nbsp;<span class="label ' . $label . '">' . $targetName . '</span>';
        }

        $output .= '<br/>';

        return $output;
    }

    protected function reportGenerator($action, $targetName, $newValue = '', $oldValue = '')
    {
        $output = ucfirst($action) . ' ' . ucwords($targetName) . ': ';

        if ($oldValue != '') {
            $output .= '<span class="label label-important">' . $oldValue . '</span> &#62; ';
        }

        $output .= '<span class="label label-success">' . $newValue . '</span><br/>';

        return $output;
    }

    /**
     * Generates a report for a single field entry
     *
     * @param  int $fieldId
     *
     * @return string
     */
    protected function insertFieldReport($fieldId)
    {
        $report = $this->reportSectionTitleGenerator('create', 'field');

        $report .= $this->reportGenerator('set', 'field name', \Input::get('field_name' . $fieldId));
        $report .= $this->reportGenerator('set', 'field type', \Input::get('field_type' . $fieldId));
        $report .= $this->reportGenerator('set', 'relation table', \Input::get('relation_table' . $fieldId));
        $report .= $this->reportGenerator('set', 'column name', \Input::get('column_name' . $fieldId));
        $report .= $this->reportGenerator('set', 'column type', \Input::get('column_type' . $fieldId));
        $report .= $this->reportGenerator('set', 'tooltip text', \Input::get('tooltip_text' . $fieldId));

        $report .= '<br/>';

        return $report;
    }

    /**
     * Gets All Available Parent Modules
     *
     * @param int $id
     *
     * @return Object
     */
    public function getParentModules($id = null)
    {
        if (is_numeric($id)) {
            return Module::roots()->where('id', '!=', $id)->get();
        }

        return Module::roots()->get();
    }

}
