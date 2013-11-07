<?php

/**
 * Description of Change
 *
 * @author Ivan Batić <ivan.batic@live.com>
 */

namespace Orangehill\Photon\Library\Creator\Report\Changes;

class Change
{

    protected $original;
    protected $new;
    protected $type;
    protected $name;
    protected $tableName;
    protected $id;

    public function __construct($from = null, $to = null)
    {
        $this->setOriginal($from)->setNew($to);
    }

    public function getOriginal()
    {
        return $this->original;
    }

    public function getNew()
    {
        return $this->new;
    }

    public function setOriginal($original)
    {
        $this->original = $original;
        return $this;
    }

    public function setNew($new)
    {
        $this->new = $new;
        return $this;
    }

    public function getType()
    {
        return $this->type ? : $this->detectType();
    }

    public function setType($type)
    {
        $this->type = $type;
        return $this;
    }

    public function getName()
    {
        return $this->name;
    }

    public function setName($name)
    {
        $this->name = $name;
        return $this;
    }

    public function detectType()
    {
        $original = $this->getOriginal();
        $new = $this->getNew();
        $type = '';
        if ($original == $new) {
            $type = 'same';
        } elseif (isset($original) && isset($new) && $original != $new) {
            $type = 'update';
        } elseif (isset($original) && !isset($new)) {
            $type = 'delete';
        } elseif (!isset($original) && isset($new)) {
            $type = 'create';
        }
        return $type;
    }

    public function getId()
    {
        return $this->id;
    }

    public function setId($id)
    {
        $this->id = $id;
        return $this;
    }

    public function getTableName()
    {
        return $this->tableName;
    }

    public function setTableName($tableName)
    {
        $this->tableName = $tableName;
        return $this;
    }

    public function toArray()
    {
        return array(
            'name'       => $this->getName(),
            'new'        => $this->getNew(),
            'original'   => $this->getOriginal(),
            'type'       => $this->getType(),
            'id'         => $this->getId(),
            'table_name' => $this->getTableName()
        );
    }

}
