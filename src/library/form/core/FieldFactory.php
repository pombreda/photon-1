<?php
/**
 * Created by PhpStorm.
 * User: ivan
 * Date: 11/17/13
 * Time: 11:38 PM
 */

namespace Orangehill\Photon\Library\Form\Core;


class FieldFactory
{

    public static function make($field)
    {
        $fieldName = is_a($field, '\Orangehill\Photon\Field') ? $field->type : $field;
        $classStub = '\Orangehill\Photon\Library\Form\Fields\%s\%s';
        $class     = sprintf($classStub, studly_case($fieldName), studly_case($fieldName));

        return new $class($fieldName === $field ? null : $field);
    }
}