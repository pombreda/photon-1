<?php namespace Orangehill\Photon;

class FieldEloquentRepository implements FieldRepository {

    /**
     * Returns a model class
     * @return object
     */
    public function model()
    {
        return Field;
    }

    /**
     * Counts dependant fields
     * @param  int $fieldId
     * @return int
     */
    public function countDependantFields($fieldId)
    {
        $field = $this->model()
            ->where('relation_table', $fieldId)
            ->count();
        if(!is_null($field)) {
            return $field->field_name;
        }

        return $false;
    }

}
