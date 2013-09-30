<?php namespace Orangehill\Photon;

interface ModuleRepositoryInterface {

    /**
     * Returns a model class
     * @return \Illuminate\Database\Eloquent\Model
     */
    public function model();

    /**
     * Gets dependant module name
     * @param  int $moduleId
     * @return string
     */
    public function getDependantModuleName($moduleId);

}
