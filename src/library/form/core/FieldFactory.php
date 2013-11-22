<?php
/**
 * Created by PhpStorm.
 * User: ivan
 * Date: 11/17/13
 * Time: 11:38 PM
 */

namespace Orangehill\Photon\Library\Form\Core;


use Orangehill\Photon\Field;

class FieldFactory
{

    /**
     * @param $fieldType
     *
     * @return Field
     */
    public static function make($fieldType)
    {
        $fieldName = is_a($fieldType, '\Orangehill\Photon\Field') ? $fieldType->type : $fieldType;
        $classStub = '\Orangehill\Photon\Library\Form\Fields\%s\%s';
        $class     = sprintf($classStub, studly_case($fieldName), studly_case($fieldName));

        return new $class($fieldName === $fieldType ? null : $fieldType);
    }
}