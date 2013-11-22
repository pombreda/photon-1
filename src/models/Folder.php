<?php
namespace Orangehill\Photon;

use Baum\Node;

class Folder extends Node
{
    public static $rules = array();
    protected $guarded = array();

    public function modules()
    {
        return $this->hasMany('Orangehill\Photon\Module', 'folder_id');
    }

    public function __toString()
    {
        return $this->name;
    }
}