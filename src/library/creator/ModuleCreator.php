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

        $newFields = $this->validateNewFields($input['fields']['new'] ? : array(), $id);
        if ($newFields instanceof MessageBag) {
            $messages->merge($newFields->getMessages());
        }

        return count($messages) ? $messages : true;
    }

    private function validateNewFields(array $fields = array(), $moduleId)
    {
        $customBag        = new MessageBag();
        $validatorBags    = array();
        $takenColumnNames = array();

        $tmpField = new Field();
        $rules    = array_merge($tmpField->getRules(), array(
                'column_name' => 'unique:fields,column_name,NULL,id,module_id,' . $moduleId
            )
        );

        foreach ($fields as $field) {
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
        /* @var $bag MessageBag */
        foreach ($validatorBags as $bag) {
            $customBag->merge($bag->getMessages());
        }

        return $customBag->isEmpty() ? true : $customBag;
    }

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
     *
     * @param array $input
     * @param array $changeGroups
     *
     * @todo Break this up into smaller parts
     */
    public function applyModuleChanges(array $input = array(), array $changeGroups = array())
    {
        // Generate a module
        $tableName        = $input['table_name'];
        $moduleId         = $input['id'];
        $moduleChangesets = array_shift($changeGroups);
        $moduleChangeset  = $moduleChangesets[0];

        $generatorMap     = array(
            'created_fields' => array(),
            'deleted_fields' => array(),
            'updated_fields' => array()
        );
        $generatorMethods = array(
            'created_fields' => 'add',
            'deleted_fields' => 'remove',
        );

        // Generate model creation migration
        if (!$moduleId) {
            MigrationGenerator::create($tableName);
            sleep(1);
        }
        // Pause a second so migrations are named (ordered)s properly
        // Gather fields
        foreach ($changeGroups as $groupType => $changesets) {
            if ($groupType === 'updated_fields') {
                continue;
            }
            foreach ($changesets as $changeset) {
                if (!is_array($changeset['changes']) || empty($changeset['changes'])) {
                    continue;
                }

                $columnType = ArrayLib::getKey(ArrayLib::findByKeyValue(
                        $changeset['changes'],
                        'name',
                        'column_type'
                    ) ? : array(), 'new'
                );
                $columnName = ArrayLib::getKey(ArrayLib::findByKeyValue(
                        $changeset['changes'],
                        'name',
                        'column_name'
                    ), 'new'
                );
                // Break this up
                $generatorMap[$groupType][$columnName] = $columnType;
            }
        }

        // Generate migrations for fields

        foreach ($generatorMap as $key => $fields) {
            if (array_key_exists($key, $generatorMethods) && !empty($fields)) {
                MigrationGenerator::$generatorMethods[$key]($tableName, array_filter($fields, function ($value) {
                            return !empty($value);
                        }
                    )
                );
            }
        }
        // Update model
        if (!empty($moduleChangeset['changes'])) {
            $changemap = [];
            foreach ($moduleChangeset['changes'] as $change) {
                $changemap[$change['name']] = $change['new'];
            }
            if ($moduleChangeset['item_id']) {
                \DB::table(\Orangehill\Photon\Module::getTableName())->where('table_name', $tableName)
                    ->update($changemap);
            } else {
                $newModule = new Module($changemap);

                // Generate a model file 
                $modelName = ucfirst(camel_case(str_singular($tableName)));
                \Artisan::call('generate:model', array('name' => $modelName));
                $pathToModel = app_path('models') . '/' . $modelName . '.php';
                file_put_contents(
                    $pathToModel,
                    str_replace('extends Eloquent', 'extends Baum\Node', file_get_contents($pathToModel))
                );

                $newModule->save();
                $moduleId = $newModule->id;
            }
        }
        // Add fields
        foreach ($changeGroups['created_fields'] as $field) {
            $fieldData = array(
                'module_id' => $moduleId
            );
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
        $moduleFields = Field::whereIn('id', $deletedFieldIds)->get();
        foreach ($moduleFields as $moduleField) {
            FieldFactory::make($moduleField)->uninstall();
        }
        foreach ($changeGroups['deleted_fields'] as $field) {
            \DB::table(Field::getTableName())->where('id', $field['item_id'])->delete();
        }

    }

}
