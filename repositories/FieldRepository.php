<?php namespace Orangehill\Photon;

interface FieldRepository {

    /**
     * Returns a model class
     * @return object
     */
    public function model();

    /**
     * Counts dependant fields
     * @param  int $fieldId
     * @return int
     */
    public function countDependantFields($fieldId);
}
