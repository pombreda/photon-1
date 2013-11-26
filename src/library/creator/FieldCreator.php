<?php
/**
 * Created by PhpStorm.
 * User: ivan
 * Date: 11/18/13
 * Time: 1:10 PM
 */

namespace Orangehill\Photon\Library\Creator;


use Orangehill\Photon\Field;

class FieldCreator
{

    /**
     * Creates a Form Field based on Photon Field and runs the Install routine if needed
     *
     * @param $fieldData
     *
     * @return Field
     */
    public static function make($fieldData, $install = true)
    {
        $field     = new Field($fieldData);
        $modelName = studly_case($fieldData['type']);
        $className = sprintf('\Orangehill\Photon\Library\Form\Fields\%s\%s', $modelName, $modelName);
        $formField = new $className($field);
        if ($install) {
            $formField->install();
        }

        return $field;
    }
} 