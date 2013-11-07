<?php

namespace Orangehill\Photon;

use \Baum\Node;
use Orangehill\Photon\Library\Creator\Validator\ModuleValidator;

class Module extends Node
{

    protected $fillable = array('name', 'table_name', 'parent_module', 'nestable', 'is_folder');

    public function fields()
    {
        return $this->hasMany('\Orangehill\Photon\Field')->orderBy('lft');
    }

    public function delete()
    {
        // delete all related fields
        foreach ($this->fields as $field) {
            $field->delete();
        }

        // delete the module
        return parent::delete();
    }

    public static function getTableName()
    {
        $instance = new Module();
        return $instance->getTable();
    }

}
