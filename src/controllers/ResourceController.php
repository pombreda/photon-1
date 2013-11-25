<?php
/**
 * Created by PhpStorm.
 * User: ivan
 * Date: 11/21/13
 * Time: 4:36 PM
 */

namespace Orangehill\Photon;


use Orangehill\Photon\Library\Form\Core\FieldFactory;

class ResourceController extends ApiController
{

    public function deleteIndex($tableName, $fieldName, $entryId, $fileName)
    {
        $module      = Module::where('table_name', $tableName)->first();
        $moduleField = Field::where('module_id', $module->id)->where('column_name', $fieldName)->first();
        $field       = FieldFactory::make($moduleField);

        $field->delete($entryId);

        return \Response::json($this->apiResponse->toArray());
    }

    public function getIndex($tableName, $fieldName, $entryId, $fileName)
    {
        $module      = Module::where('table_name', $tableName)->first();
        $moduleField = Field::where('module_id', $module->id)->where('column_name', $fieldName)->first();
        $field       = FieldFactory::make($moduleField);

        $field->delete($entryId);

        return \Response::json($this->apiResponse->toArray());
    }

}