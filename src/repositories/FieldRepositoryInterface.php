<?php namespace Orangehill\Photon;

interface FieldRepositoryInterface {

    /**
     * Returns a model class
     * @return \Illuminate\Database\Eloquent\Model
     */
    public function model();

    /**
     * Gets dependant field name
     * @param  int $fieldId
     * @return string
     */
    public function getDependantFieldName($fieldId);

}
