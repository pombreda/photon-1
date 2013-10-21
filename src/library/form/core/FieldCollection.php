<?php

/**
 * Description of FieldCollection
 *
 * @author Ivan Batić <ivan.batic@live.com>
 */

namespace Orangehill\Photon\Library\Form\Core;

class FieldCollection implements \ArrayAccess, \Iterator {

    protected $fields = array();

    public function offsetExists($offset) {
        return $this->fields[$offset] instanceof Field;
    }

    public function offsetGet($offset) {
        return $this->fields[$offset];
    }

    public function offsetSet($offset, $value) {
        $this->fields[$offset] = $value;
    }

    public function offsetUnset($offset) {
        array_splice($this->fields, $offset, 1);
    }

    public function current() {
        return current($this->fields);
    }

    public function key() {
        return key($this->fields);
    }

    public function next() {
        return next($this->fields);
    }

    public function prev() {
        return prev($this->fields);
    }

    public function rewind() {
        reset($this->fields);
    }

    public function valid() {
        return $this->current() instanceof Field;
    }

    public function add(Field $field) {
        $this->fields[] = $field;
        return $this;
    }

    public function addCollection(FieldCollection $collection) {
        foreach ($collection as $field) {
            $this->add($field);
        }
    }

}
