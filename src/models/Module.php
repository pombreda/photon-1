<?php

namespace Orangehill\Photon;

use Baum\Node;
use Orangehill\Photon\Library\Form\Core\FieldCollection;

class Module extends Node
{
    protected $fillable = array('name', 'table_name', 'folder_id', 'nestable', 'is_folder');
    /** @var  FieldCollection */
    protected $fieldCollection;

    public static function getTableName()
    {
        $instance = new Module();

        return $instance->getTable();
    }

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

    public function folder()
    {
        return $this->belongsTo('Orangehill\Photon\Folder', 'folder_id');
    }

    /**
     * Fills the field collection (created if needed) with field values of this particular instance
     *
     * @param array $row Array of field_name => value entries
     *
     * @return $this
     */
    public function setFieldValues(array $row = array())
    {
        // Go through the field collection and assign a value to each field
        foreach ($this->getFieldCollection() as $field) {
            /** @var $field Field */
            $value = $row[$field->getField()->column_name];
            $field->setValue($value)->setRow($row);
        }

        return $this;
    }

    /**
     * Creates a FieldCollection based the current Module instance
     *
     * @return FieldCollection
     */
    public function getFieldCollection()
    {
        if ($this->fieldCollection instanceof FieldCollection) {
            return $this->fieldCollection;
        }

        $collection = new FieldCollection();
        foreach ($this->fields as $field) {
            $className = studly_case($field->type);
            $fullClass = sprintf('Orangehill\Photon\Library\Form\Fields\%s\%s', $className, $className);
            $rField    = new $fullClass($field);
            $collection->add($rField, $field->column_name);
        }
        $this->fieldCollection = $collection;

        return $collection;
    }

}
