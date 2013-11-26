<?php

/**
 * Module comparator
 *
 * @author Ivan Batić <ivan.batic@live.com>
 */

namespace Orangehill\Photon\Library\Creator\Report;

use Orangehill\Photon\Library\Creator\Report\Changes\Changeset;
use Orangehill\Photon\Library\Creator\Report\Changes\FieldChange;

class Comparator
{

    protected $input;
    protected $tableName;
    protected $identifier;
    protected $changeset;

    public function __construct()
    {
        $this->changeset = new Changeset();
    }

    public function getInput()
    {
        return $this->input;
    }

    public function setInput($input)
    {
        $this->input = $input;

        return $this;
    }

    public function getIdentifier()
    {
        return $this->identifier;
    }

    public function setIdentifier($identifier)
    {
        $this->identifier = $identifier;

        return $this;
    }

    /**
     * Compares a single entry to the existing one
     *
     * @param array $input
     * @param null  $original
     *
     * @return Changeset
     * @throws Exception
     */
    public function compareSingle(array $input = array(), $original = null)
    {
        // TODO: add a more specific exception
        if (!$this->getTableName()) {
            throw new Exception('Table name missing');
        }
        // Set the input array to be checked
        $input = !empty($input) ? $input : $this->input;
        // Init the changeset collection
        $this->changeset = new Changeset();
        // Init the record value
        $record = array();

        // If there is id set, get the record and compare to id
        if (isset($input['id']) && $input['id']) {
            if (is_array($original)) {
                $record = $original;
            } else {
                $record = (array)\DB::table($this->getTableName())->where('id', $input['id'])->first();
            }
        }

        foreach ($input as $key => $value) {
            $original = isset($record[$key]) ? $record[$key] : null;
            // New value is the same as the old one, skip this one
            if ($original == $value) {
                continue;
            }
            $change = new FieldChange(array(
                'table_name' => $this->getTableName(),
                'field_name' => $key,
                'new'        => $value,
                'original'   => isset($record[$key]) ? $record[$key] : null
            ));
            $this->changeset->addChange($change);
        }

        return $this->changeset;
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


}
