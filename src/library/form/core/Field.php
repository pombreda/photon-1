<?php

/**
 * Description of Fields
 *
 * @author Ivan Batić <ivan.batic@live.com>
 */

namespace Orangehill\Photon\Library\Form\Core;

class Field
{

    protected $attributes = array();
    protected $properties = array();
    protected $view = 'view';
    protected $value;
    protected $validationFunction;
    protected $hasColumn = true;
    /** @var  \Orangehill\Photon\Field */
    protected $field;
    protected $row;

    public function __construct(\Orangehill\Photon\Field $field)
    {
        $this->field = $field;
        $this->registerViewNamespace();
    }

    protected function registerViewNamespace()
    {
        $childClass = get_class($this);
        $exploded   = explode('\\', $childClass);
        $className  = array_pop($exploded);
        $path       = __DIR__ . '/../fields/' . str_replace('_', '-', snake_case($className));
        \View::addNamespace($childClass, $path);
    }

    /**
     * @return boolean
     */
    public function getHasColumn()
    {
        return $this->hasColumn;
    }

    /**
     * @param boolean $hasColumn
     */
    public function setHasColumn($hasColumn)
    {
        $this->hasColumn = $hasColumn;

        return $this;
    }

    /**
     * @return mixed
     */
    public function getRow()
    {
        return $this->row;
    }

    /**
     * @param mixed $row
     */
    public function setRow($row)
    {
        $this->row = $row;

        return $this;
    }

    public function validate()
    {
        if (is_callable($this->validationFunction)) {
            return $this->validationFunction($this->value);
        } else {
            return true;
        }
    }

    public function getValue()
    {
        return $this->value;
    }

    public function setValue($value)
    {
        $this->value = $value;

        return $this;
    }

    public function getView()
    {
        return $this->view;
    }

    public function setView($view)
    {
        $this->view = $view;

        return $this;
    }

    public function getProperties()
    {
        return $this->properties;
    }

    public function setProperties($properties)
    {
        $this->properties = $properties;

        return $this;
    }

    public function setAttribute($name, $value)
    {
        $this->attributes['name'] = $value;
    }

    public function getAttribute($name)
    {
        return $this->attributes['name'];
    }

    public function getAttributes()
    {
        return $this->attributes;
    }

    public function setAttributes(array $attrs)
    {
        $this->attributes = array_merge($this->attributes, $attrs);
    }

    public function setValidationFunction($validationFunction)
    {
        $this->validationFunction = $validationFunction;

        return $this;
    }

    public function setHtmlValue($value)
    {
        $this->value = $value;
    }

    public function render()
    {
        echo $this->compileView();
    }

    public function compileView()
    {
        $view = \View::make(get_class($this) . '::' . $this->view, array('field' => $this));

        return $view;
    }

    public function __toString()
    {
        try {
            return $this->compileView()->render();

        } catch (\Exception $ex) {
            return $ex->getMessage();
        }
    }

    public function getHtmlValue()
    {
        return $this->value;
    }

    /**
     * @return \Orangehill\Photon\Field
     */
    public function getField()
    {
        return $this->field;
    }

    /**
     * @param \Orangehill\Photon\Field $field
     */
    public function setField(\Orangehill\Photon\Field $field)
    {
        $this->field = $field;

        return $this;
    }

    public function install()
    {
        return $this;
    }

    public function uninstall()
    {
        return $this;
    }

    public function create()
    {
        return $this;
    }

    public function update()
    {

        return $this;
    }

    public function delete($id = null)
    {
        return $this;
    }

    public function __get($name)
    {
        return $this->field ? $this->field->$name : $this->$name;
    }

    public function parse($input = null)
    {
        return $input;
    }


}
