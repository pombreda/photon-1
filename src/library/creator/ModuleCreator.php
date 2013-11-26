<?php

/**
 * @author Ivan Batić <ivan.batic@live.com>
 */

namespace Orangehill\Photon\Library\Creator;

use Illuminate\Support\MessageBag;
use Illuminate\Validation\Validator;
use Orangehill\Photon\Field;
use Orangehill\Photon\Library\Form\Core\FieldFactory;
use Orangehill\Photon\Library\Support\ArrayLib;
use Orangehill\Photon\Module;

class ModuleCreator
{

    /**
     * Runs the module data through validation
     *
     * @param array $input
     *
     * @return bool|MessageBag If the validation passed, returns true, MessageBag otherwise
     */
    public function validateModule($input)
    {
        $id = isset($input['id']) ? $input['id'] : null;
        /* @var $messages Validator */
        $messages = \Validator::make($input, array(
                'id'            => "unique:modules,id,{$id}",
                'name'          => "required|unique:modules,name,{$id}",
                'table_name'    => "required|alpha_dash|unique:modules,table_name,{$id}",
                'parent_module' => 'integer|exists:modules,id',
                'nestable'      => 'in:0,1',
            )
        );
        // Trigger the creation of a message bag
        $messages->passes();

        $messages = $messages->getMessageBag();

        // Extracts the newly added fields
        $newFields = $this->validateNewFields($input['fields']['new'] ? : array(), $id);

        if ($newFields instanceof MessageBag) {
            $messages->merge($newFields->getMessages());
        }

        return count($messages) ? $messages : true;
    }

    /**
     * Checks if the input request is doable
     *
     * @param array $fields
     * @param       $moduleId
     *
     * @return true|MessageBag
     */
    private function validateNewFields(array $fields = array(), $moduleId)
    {
        $customBag        = new MessageBag(); // Main bag
        $validatorBags    = array(); // Bags of different validators
        $takenColumnNames = array(); // Column names that already exist

        // Check if column name already exists
        $tmpField = new Field();
        $rules    = array_merge($tmpField->getRules(), array(
                'column_name' => 'unique:fields,column_name,NULL,id,module_id,' . $moduleId
            )
        );

        // Go through all fields and validate them
        foreach ($fields as $field) {
            $photonField = new Field($field);
            $formField   = FieldFactory::make($photonField);
            $formField->setRow($field);

            $validated = $formField->validate();
            if ($validated instanceof MessageBag) {
                $validatorBags[] = $validated;
            }

            /* @var $validator \Illuminate\Validation\Validator */
            $validator = \Validator::make($field, $rules);
            if ($validator->fails()) {
                $validatorBags[] = $validator->getMessageBag();
            }
            if (in_array($field['column_name'], $takenColumnNames)) {
                $customBag->add('new_field',
                    'Column name `' . $field['column_name'] . '` cannot be assigned to multiple fields'
                );
            }
            $takenColumnNames[] = $field['column_name'];
        }

        // Merge all bags
        /* @var $bag MessageBag */
        foreach ($validatorBags as $bag) {
            $customBag->merge($bag->getMessages());
        }

        // Return the bag if there are no errors, otherwise return true
        return $customBag->isEmpty() ? true : $customBag;
    }

    /**
     * Deletes a module by id
     *
     * @param $id
     */
    public function deleteModule($id)
    {
        $module = Module::find($id);
        if ($module) {
            \Schema::dropIfExists($module->table_name);
            $modelName = ucfirst(camel_case(str_singular($module->table_name)));
            $filePath  = app_path('models') . '/' . $modelName . '.php';
            if (file_exists($filePath)) {
                unlink($filePath);
            }
            $module->delete();
        }
    }

    /**
     * Take the module creator form input and apply changes
     *
     * @param array $input
     * @param array $changeGroups
     *
     * @todo Break this up into smaller parts
     */
    public function applyModuleChanges(array $input = array(), array $changeGroups = array())
    {
        // Initialize needed variables
        $tableName       = $input['table_name']; // Module table name
        $moduleId        = $input['id']; // Module ID
        $moduleChangeset = ArrayLib::getKey(array_shift($changeGroups), 0); // Get the module changeset

        // Initialize the generator map array
        $generatorMap = array(
            'created_fields' => array(),
            'deleted_fields' => array(),
            'updated_fields' => array()
        );

        // Define artisan generator methods to be used with generator map keys
        $generatorMethods = array(
            'created_fields' => 'add',
            'deleted_fields' => 'remove',
        );

        // If there's no module id, spawn a migration for creation of a new module
        if (!$moduleId) {
            MigrationGenerator::create($tableName);
            sleep(1); // Pause a second so succeeding migrations are named (ordered) properly
        }

        // Gather fields
        foreach ($changeGroups as $groupType => $changesets) {

            // Skip if we're dealing with updates
            if ($groupType === 'updated_fields') {
                continue;
            }

            foreach ($changesets as $changeset) {
                // Skip if changeset is empty
                if (!is_array($changeset['changes']) || empty($changeset['changes'])) {
                    continue;
                }

                // Retrieve the column type of a current changeset
                $columnType = ArrayLib::getKey(
                    ArrayLib::findByKeyValue($changeset['changes'], 'name', 'column_type') ? : array(),
                    'new'
                );

                // Retrieve the column name of a current changeset
                $columnName = ArrayLib::getKey(
                    ArrayLib::findByKeyValue($changeset['changes'], 'name', 'column_name') ? : array(),
                    'new'
                );

                $generatorMap[$groupType][$columnName] = $columnType;
            }
        }

        // Generate migrations for new fields
        foreach ($generatorMap as $key => $fields) {
            // Check if a migration should be generated
            if (array_key_exists($key, $generatorMethods) && !empty($fields)) {
                // Bam!
                MigrationGenerator::$generatorMethods[$key]($tableName, array_filter($fields, function ($value) {
                            return !empty($value);
                        }
                    )
                );
            }
        }

        // Update model
        if (!empty($moduleChangeset['changes'])) {
            // Map of field => value entries
            $changemap = [];

            // Fill the changemap
            foreach ($moduleChangeset['changes'] as $change) {
                $changemap[$change['name']] = $change['new'];
            }

            // If module already exists, update it with new entries
            if ($moduleChangeset['item_id']) {
                \DB::table(\Orangehill\Photon\Module::getTableName())
                    ->where('table_name', $tableName)
                    ->update($changemap);
            } else {
                // Module doesn't exist yet, it should be created
                // Create a new module based on an form input entry
                $newModule = new Module($changemap);

                // Generate a model file 
                $modelName = ucfirst(camel_case(str_singular($tableName)));
                \Artisan::call('generate:model', array('name' => $modelName));

                // Store the path to the model file
                $pathToModel = app_path('models') . '/' . $modelName . '.php';

                // Open the newly created file and make the model extend Baum's Node instead of Eloquent
                $fileContent = file_get_contents($pathToModel);
                $replaced    = str_replace('extends Eloquent', 'extends Baum\Node', $fileContent);

                // Try to find a name of first added column, fallback to the id
                $toStringField = 'id';
                if (is_array($changeGroups['created_fields'])) {
                    $head = head($changeGroups['created_fields']);
                    foreach ($head['changes'] as $fieldSegment) {
                        if ($fieldSegment['name'] == 'column_name') {
                            $toStringField = $fieldSegment['new'];
                        }
                    }
                }

                // Create a __toString method stub
                $toStringStub = StubFactory::make('ModelToStringMethod', array('field' => $toStringField))
                    ->append("\n}")
                    ->get();

                // Add the method to the model files
                $replaced = substr_replace($replaced, $toStringStub, strrpos($replaced, '}'));
                file_put_contents($pathToModel, $replaced);

                // Save a model and update the module id
                $newModule->save();
                $moduleId = $newModule->id;
            }
        }

        // Add newborn fields
        foreach ($changeGroups['created_fields'] as $field) {
            $fieldData = array('module_id' => $moduleId);

            foreach ($field['changes'] as $element) {
                $fieldData[$element['name']] = $element['new'];
            }

            $field = FieldCreator::make($fieldData);
            $field->save();
        }

        // Update fields
        foreach ($changeGroups['updated_fields'] as $field) {
            $fieldData = array();

            foreach ($field['changes'] as $element) {
                $fieldData[$element['name']] = $element['new'];
            }

            \DB::table(Field::getTableName())->where('id', $field['item_id'])->update($fieldData);
        }

        // Delete fields
        $deletedFieldIds = array_fetch($changeGroups['deleted_fields'], 'item_id') + array(0);
        $moduleFields    = Field::whereIn('id', $deletedFieldIds)->get();

        foreach ($moduleFields as $moduleField) {
            FieldFactory::make($moduleField)->uninstall();
        }

        foreach ($changeGroups['deleted_fields'] as $field) {
            \DB::table(Field::getTableName())->where('id', $field['item_id'])->delete();
        }

    }

}
