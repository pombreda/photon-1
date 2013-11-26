<?php

/**
 * Description of Changeset
 *
 * @author Ivan Batić <ivan.batic@live.com>
 */

namespace Orangehill\Photon\Library\Creator\Report\Changes;

class Changeset implements \Countable
{

    protected $type;
    protected $itemType;
    protected $itemName;
    protected $tableName;
    protected $itemId;
    protected $changes = array();

    public function __construct(array $changes = array())
    {
        foreach ($changes as $change) {
            if ($change instanceof Change) {
                $this->addChange($change);
            }
        }
    }

    public function addChange(Change $change)
    {
        $this->changes[] = $change;
    }

    public function toArray()
    {
        $changes = array();
        foreach ($this->changes as $change) {
            $changes[] = $change->toArray();
        }
        $cmp = array(
            'type'       => $this->getType(),
            'item_type'  => $this->getItemType(),
            'item_name'  => $this->getItemName(),
            'item_id'    => $this->getItemId(),
            'changes'    => $changes,
            'table_name' => $this->getTableName()
        );

        return $cmp;
    }

    public function getType()
    {
        return $this->type;
    }

    public function setType($type)
    {
        $this->type = $type;

        return $this;
    }

    public function getItemType()
    {
        return $this->itemType;
    }

    public function setItemType($itemType)
    {
        $this->itemType = $itemType;

        return $this;
    }

    public function getItemName()
    {
        return $this->itemName;
    }

    public function setItemName($itemName)
    {
        $this->itemName = $itemName;

        return $this;
    }

    public function getItemId()
    {
        return $this->itemId;
    }

    public function setItemId($itemId)
    {
        $this->itemId = $itemId;

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

    public function getChanges()
    {
        return $this->changes;
    }

    public function setChanges($changes)
    {
        $this->changes = $changes;

        return $this;
    }

    public function count()
    {
        return count($this->changes);
    }

}
