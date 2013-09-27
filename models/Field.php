<?php

namespace Orangehill\Photon;

use \Baum\Node;

class Field extends Node {

    protected $fillable = array('field_name', 'field_type', 'relation_table', 'column_name', 'column_type', 'tooltip_text', 'module_id');

}
