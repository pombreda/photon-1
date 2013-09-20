<?php namespace Orangehill\Photon;

use \Baum\Node;

class Jstree extends Node {

    protected $table;

    public function __construct(array $attributes = array())
    {

        $this->setTable(\Request::segment(3));
        parent::__construct($attributes);

    }

    /**
     * Magic method __call is used to automatically attempt to create belongsToMany relationship
     * -- TODO: Unfortunatelly this doesn't work out of the box; A model needs to exist in order for relation to work.
     * -- Need to create a custom Photon Model that all models inherit from, and that extends a Node model.
     * -- This custom model will overload belongsToMany() Eloquent Model function to
     * -- re-route all instantiation to custom model.
     * @param  string $name
     * @param  mixed $arguments
     * @return object
     */
    public function __call($name, $arguments)
    {

        return $this->belongsToMany($name);

    }

}
