<?php

/**
 * Description of Fields
 *
 * @author Ivan Batić <ivan.batic@live.com>
 */

namespace Orangehill\Photon\Library\Form\Core;

class Field {

    protected $attributes = array();
    protected $properties = array();
    protected $view = 'view';
    protected $value;
    protected $validationFunction = null;

    public function __construct() {
        $this->registerViewNamespace();
    }

    protected function registerViewNamespace() {
        $childClass = get_class($this);
        $exploded = explode('\\', $childClass);
        $className = array_pop($exploded);
        $path = __DIR__ . '/../fields/' . \Str::slug($className);
        \View::addNamespace($childClass, $path);
    }

    public function validate() {
        if (is_callable($this->validationFunction)) {
            return (bool) $this->validationFunction($this->value);
        }
    }

    public function setValue($value) {
        $this->value = $value;
        return $this;
    }

    public function getValue() {
        return $this->value;
    }

    public function getView() {
        return $this->view;
    }
    public function getProperties() {
        return $this->properties;
    }

    public function setProperties($properties) {
        $this->properties = $properties;
        return $this;
    }

    
    public function setView($view) {
        $this->view = $view;
        return $this;
    }

    public function setAttribute($name, $value) {
        $this->attributes['name'] = $value;
    }
    
    public function setAttributes(array $attrs){
        $this->attributes = array_merge($this->attributes, $attrs);
    }

    public function getAttribute($name) {
        return $this->attributes['name'];
    }

    public function getAttributes() {
        return $this->attributes;
    }

    public function setValidationFunction($validationFunction) {
        $this->validationFunction = $validationFunction;
        return $this;
    }

    public function getHtmlValue() {
        return $this->value;
    }

    public function setHtmlValue($value) {
        $this->value = $value;
    }

    public function compileView() {

        if (empty($this->view)) {
            throw new \Orangehill\Photon\FormFields\Exceptions\UndefinedFieldException("Undefined view");
        }
        $view = \View::make(get_class($this) . '::' . $this->view, array('field' => $this));
        return $view;
    }
    
    public function render() {
        echo $this->compileView();
    }

    public function __toString() {
        return "{$this->name}: {$this->getHtmlValue()}";
    }

}
