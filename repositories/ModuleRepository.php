<?php namespace Orangehill\Photon;

interface ModuleRepository {

    /**
     * Returns a model class
     * @return object
     */
    public function model();

    /**
     * Counts dependant modules
     * @param  int $moduleId
     * @return int
     */
    public function countDependantModules($moduleId);

}
