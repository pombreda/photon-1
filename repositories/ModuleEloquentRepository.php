<?php namespace Orangehill\Photon;

class ModuleEloquentRepository implements ModuleRepository {

    /**
     * Returns a model class
     * @return object
     */
    public function model()
    {
        return Module;
    }

    /**
     * Counts dependant modules
     * @param  int $moduleId
     * @return int
     */
    public function countDependantModules($moduleId)
    {
        $module = $this->model()
            ->where('parent_module', $moduleId)
            ->first();
        if(!is_null($module)) {
            return $module->module_name;
        }

        return false;
    }

}
