<?php

namespace Orangehill\Photon;

use \Baum\Node;

class Field extends Node
{

    protected $fillable = array('name', 'type', 'relation_table', 'column_name', 'column_type', 'tooltip_text', 'module_id');
    protected $rules = array(
        'column_name' => 'unique:fields,column_name',
        'column_type' => 'alpha',
        'module_id'   => 'integer|exists:modules,id'
    );

    public static function getTableName()
    {
        $instance = new Field();
        return $instance->getTable();
    }

    public function getRules()
    {
        return $this->rules;
    }

}
