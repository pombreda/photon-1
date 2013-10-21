<?php namespace Orangehill\Photon;

class ModuleEloquentRepository implements ModuleRepositoryInterface {

    /**
     * Returns a model class
     * @return \Illuminate\Database\Eloquent\Model
     */
    public function model()
    {
        return new Module;
    }

    /**
     * Gets dependant module name
     * @param  int $moduleId
     * @return string
     */
    public function getDependantModuleName($moduleId)
    {
        $module = $this->model()
            ->where('parent_module', $moduleId)
            ->first();
        if(!is_null($module)) {
            return $module->name;
        }

        return null;
    }

}
