<?php namespace Orangehill\Photon;

class ModuleControllerx extends \BaseController
{
    /**
     * $data is passed to module.blade.php view file
     *
     * @var array
     */
    public $data = array(
        'module' => array()
    );
    /**
     * $imageFields is used to determine if file uploads are existing or not
     *
     * @var array
     */
    protected $imageFields = array();
    /**
     * $manyToManyFields is used to handle many-to-many relation fields
     *
     * @var array
     */
    protected $manyToManyFields = array();
    /**
     * $booleanFields is used to determine if boolean fields are submitted
     *
     * @var array
     */
    protected $booleanFields = array();
    /**
     * $modelInstance holds the current module model instance
     *
     * @var \Baum\Node
     */
    protected $modelInstance;

    public function init($tableName, $id = null)
    {

        $modelName = studly_case(str_singular($tableName));
        $model     = '\\' . $modelName;

        // If there's no model in root namespace, check in Photon ns
        if (!class_exists($model)) {
            $model = '\\Orangehill\\Photon\\' . $modelName;
        }

        // Get model instance for current module
        $this->modelInstance = new $model;

        // Fetch module data by table name
        $this->data['module'] = Module::where('table_name', $tableName)->first();


        // Return error page if no module is found
        if (!$this->data['module']) {
            return \View::make('photon::errors.missing');
        }

        // Fetch Fields
        $this->data['fields'] = $this->data['module']->fields;

        // If entry ID has been provided fetch the entry data to hydrate a form
        if ($id) {
            $this->data['entry'] = $this->modelInstance->find($id);
        }

        // Check for certain field types that need to be handled specialy later in postEntry()
        foreach ($this->data['fields'] as $field) {
            if ($field->field_type == 'image') {
                $this->imageFields[] = $field;
            }
            if ($field->field_type == 'boolean') {
                $this->booleanFields[] = $field;
            }
            if ($field->field_type == 'one-to-many') {
                $this->oneToManyFields[] = $field;
            }
            if ($field->field_type == 'many-to-many') {
                $this->manyToManyFields[] = $field;
            }
        }

        // Handle oneToMany fields
        if (isset($this->oneToManyFields) AND count($this->oneToManyFields) > 0) {

            // $data['oneToMany'] will be used to populate the select field
            $this->data['oneToMany'] = array();

            foreach ($this->oneToManyFields as $oneToManyField) {

                // Fetch the data from related table
                $module = \Orangehill\Photon\Module::find($oneToManyField->relation_table
                );

                $this->data['oneToMany'][$oneToManyField->column_name] = $this->fetchRelationTable($module,
                    $oneToManyField->id
                );

            }

        }

        // Handle manyToMany fields
        if (isset($this->manyToManyFields) AND count($this->manyTDoManyFields) > 0) {

            // $data['manyToMany'] will be used to populate the dual select field
            $this->data['manyToMany'] = array();

            foreach ($this->manyToManyFields as $manyToManyField) {

                // Fetch the data from related table
                $module = \Orangehill\Photon\Module::find($manyToManyField->relation_table
                );

                $this->data['manyToMany'][$manyToManyField->column_name]['available'] = $this->fetchRelationTable($module,
                    $manyToManyField->id
                );

                $this->data['manyToMany'][$manyToManyField->column_name]['selected'] = array();

                // Fetch Selected Items
                if (is_numeric(\Request::segment(3))) {

                    $tableName = $module->table_name;
                    $selected  = $this->data['entry']->$tableName()->get();

                    foreach ($selected as $item) {
                        $this->data['manyToMany'][$manyToManyField->column_name]['selected'][] = $item->id;
                    }

                    // Set a table name inside an array
                    $this->data['manyToMany'][$manyToManyField->column_name]['tableName'] = $tableName;
                }

            }

        }

        // Set the breadcrumbs array
        $this->data['breadcrumbs'] = array(
            array(
                'url'    => 'javascript:;',
                'anchor' => $this->data['module']->name
            )
        );

    }

    /**
     * Fetches the data from relation table, and returns it as key value pairs
     *
     * @param  int $tableId
     * @param  int $fieldId ID of current oneToMany field as stored in fields table
     *
     * @return array
     */
    protected function fetchRelationTable($module, $fieldId)
    {

        $tableName = $module->table_name;

        // Get predefined relatinship names from app.php config file
        $predefinedRelationshipNames = \Config::get('app.predefinedRelationshipNames');

        // Check if config is not set for current $fieldId, and use first field if so
        if (!isset($predefinedRelationshipNames[$fieldId])) {

            $field = $module->fields()->first();

            // Set the column name
            $columnName = $field->column_name;

        }

        // Fetch the needed data via Query builder
        $data = \DB::table($module->table_name)->orderBy('lft')->get();

        $output = array();

        foreach ($data as $entry) {

            // Check if config is set for current $fieldId
            if (isset($predefinedRelationshipNames[$fieldId])) {

                $glued = '';

                foreach ($predefinedRelationshipNames[$fieldId] as $segment) {

                    // Check if there's need to pull from related table
                    if (strpos($segment, '.')) {

                        $params = explode('.', $segment);
                        $piece  = \DB::table($params[0])->find($entry->$params[1])->$params[2];

                    } else {

                        $piece = $entry->$segment;

                    }

                    $glued .= ' ' . $piece;

                }

                $output[$entry->id] = trim($glued);

            } else {

                $output[$entry->id] = $entry->$columnName;

            }

        }

        return $output;
    }

    /**
     * Responds to simple get request (either to display empty or hydrated form)
     *
     * @return object
     */
    public function getEntry()
    {

        return \View::make('photon::admin.module', $this->data);

    }

    /**
     * Processes POST requests (both insert and edit)
     *
     * @return object
     */
    public function postEntry()
    {

        // $imageFields will be used to skip image fields that were not used in POST
        $imageFields = array();

        // Handle file uploads, if image fields are present
        if (count($this->imageFields) > 0) {

            // If fieldId is not set get the next auto incremenet id (Needed for filename generation)
            $fieldId = is_numeric(\Input::get('id')) ? \Input::get('id')
                : AdminController::getNextAutoIncrement($this->data['module']->table_name);

            foreach ($this->imageFields as $imageField) {
                if (\Input::hasFile($imageField->column_name)) {

                    // Handle file upload
                    $fileName = $this->handleFileUpload($imageField->column_name, $imageField->id, $fieldId);

                    // Store a fileName in database
                    $fileName = is_null($fileName) ? '' : $fileName;
                    \Input::merge([$imageField->column_name => $fileName]);

                } else {

                    \Input::merge([$imageField->column_name => '']);
                    $imageFields[] = $imageField->column_name;

                }
            }
        }

        $manyToManyFields = array();

        // Handle many-to-many fields
        if (count($this->manyToManyFields) > 0) {

            foreach ($this->manyToManyFields as $manyToManyField) {
                $manyToManyFields[] = $manyToManyField->column_name;
            }

        }

        // If id is set then do entry editing
        if (is_numeric(\Input::get('id'))) {

            // Editing entry
            foreach (\Input::all() as $columnName => $value) {

                // Skip empty image upload fields
                if (!in_array($columnName, $imageFields)) {
                    $this->data['entry'][$columnName] = $value;
                }

            }

            // Handle many-to-many input differently
            foreach ($manyToManyFields as $field) {

                $tableName = $this->data['manyToMany'][$field]['tableName'];

                if (\Input::has($field)) {

                    $this->data['entry'][$field] = '';

                    if (is_array(\Input::get($field))) {
                        $this->data['entry']->$tableName()->sync(\Input::get($field));
                    }

                } else {
                    $this->data['entry']->$tableName()->detach();
                }
            }

            // Save the data
            $this->data['entry']->save();

            // Flash the success message to a user
            \Session::flash('success', 'Data has been saved.');

        } else {

            // Inserting new entry
            $newNode = $this->modelInstance->create(\Input::all());

            // If parent_id is provided move a newly created node under parent_id node
            if (is_numeric(\Input::get('parent_id'))) {
                $parentNode = $this->modelInstance->find(\Input::get('parent_id'));
                $newNode->makeChildOf($parentNode);
            }

        }

        // Render the view
        return \Redirect::to(\Request::path());

    }

    /**
     * Handles the file upload
     *
     * @param  string $columnName
     * @param  int    $fieldId
     *
     * @return string Filename
     */
    public function handleFileUpload($columnName, $columnId, $fieldId)
    {

        // Explode the original filename to pieces
        $filenamePieces = explode('.', \Input::file($columnName)->getClientOriginalName());
        $extension      = array_pop($filenamePieces);

        // Filename has a following structure 'fileName-{$fieldId}{$nextAutoIncrement}.ext'
        $fileName = implode('.', $filenamePieces) . '-' . $columnId . $fieldId . '.' . $extension;

        // Generate module storage path
        $module_storage_path = \Config::get('photon::photon.assets') . '/' . $this->data['module']->table_name;

        // Check if module storage folder exists in public/media folder
        if (!\File::isDirectory($module_storage_path)) {

            // Create new module storage folder if not present
            \File::makeDirectory($module_storage_path);

        }

        // Move the file
        \Input::file($columnName)->move($module_storage_path, $fileName);

        return $fileName;
    }

    public function deleteEntry()
    {

        $this->modelInstance->find(\Input::get('id'))->delete();

        return \Redirect::to('/admin/' . \Request::segment(2));

    }

    /**
     * Deletes a single field via ajax call (used to delete an image without deleting entire entry)
     *
     * @return object
     */
    public function deleteField()
    {

        // Get the values
        $id          = \Input::get('id');
        $column_name = \Input::get('column_name');

        // Get the entry record
        $entry = $this->modelInstance->find($id);

        // Remove the file
        $module_storage_path = \public_path() . '/media/' . $this->data['module']->table_name;
        \File::delete($module_storage_path . '/' . $entry[$column_name]);

        // Set selected field to null
        $entry[$column_name] = null;

        // Save entry
        $entry->save();

        // Render the view
        return \Response::json(array('Success'));

    }

}
