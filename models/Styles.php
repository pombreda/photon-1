<?php namespace Orangehill\Photon;

use \Baum\Node;

class Styles extends Node {

    protected $table = 'styles';

    /**
     * Magic method __call is used to automatically attempt to create belongsToMany relationship
     * @param  string $name
     * @param  mixed $arguments
     * @return object
     */
    public function __call($name, $arguments)
    {
        // Create an array of tables used in many to many relationship
        $manyToManyTables = [$name, $this->table];

        // Sort alphabetically so that L4 naming convention (table name is derived from the alphabetical order of the related model names)
        sort($manyToManyTables);

        // Generate a pivot table name
        $pivotTable = implode('_', $manyToManyTables);

        return $this->belongsToMany('\\Orangehill\\Photon\\' . studly_case($name), $pivotTable, $this->table . '_id', $name . '_id');

    }

}
