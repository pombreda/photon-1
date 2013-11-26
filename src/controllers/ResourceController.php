<?php
/**
 * Handles resources like photos and sounds
 * User: Ivan Batic
 */

namespace Orangehill\Photon;


use Illuminate\Http\JsonResponse;
use Orangehill\Photon\Library\Form\Core\FieldFactory;

class ResourceController extends ApiController
{

    /**
     * Deletes a particular resource
     *
     * @param $tableName Table Name
     * @param $fieldName Field Name
     * @param $entryId   Entry ID
     * @param $fileName  File Name
     *
     * @return JsonResponse
     */
    public function deleteIndex($tableName, $fieldName, $entryId, $fileName)
    {
        $module      = Module::where('table_name', $tableName)->first();
        $moduleField = Field::where('module_id', $module->id)->where('column_name', $fieldName)->first();
        FieldFactory::make($moduleField)->delete($entryId);

        return \Response::json($this->apiResponse->toArray());
    }
}