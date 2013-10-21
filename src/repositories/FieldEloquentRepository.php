<?php namespace Orangehill\Photon;

class FieldEloquentRepository implements FieldRepositoryInterface {

    /**
     * Returns a model class
     * @return \Illuminate\Database\Eloquent\Model
     */
    public function model()
    {
        return new Field;
    }

    /**
     * Gets dependant field name
     * @param  int $fieldId
     * @return string
     */
    public function getDependantFieldName($fieldId)
    {
        $field = $this->model()
            ->where('relation_table', $fieldId)
            ->count();
        if(!is_null($field)) {
            return $field->field_name;
        }

        return null;
    }

}
