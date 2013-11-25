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

    public static function make($fieldData)
    {
        $field     = new Field($fieldData);
        $modelName = studly_case($fieldData['type']);
        $className = sprintf('\Orangehill\Photon\Library\Form\Fields\%s\%s', $modelName, $modelName);
        $formField = new $className($field);
        $formField->install();

        return $field;
    }
} 