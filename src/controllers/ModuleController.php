<?php namespace Orangehill\Photon;

use Illuminate\Database\Eloquent\Model as EloquentModel;
use Illuminate\View\View;
use Orangehill\Photon\Library\ModuleManager;

class ModuleController extends \BaseController
{

    protected $breadcrumbs = array();

    public function moduleIndex($module)
    {
        $module = $this->getModule($module);
        $view   = $this->makeView($module);

        return $view;
    }

    /**
     * @param $tableName
     *
     * @return Module
     */
    protected function getModule($tableName)
    {
        $tableName = snake_case(str_plural($tableName));
        $module    = Module::where('table_name', $tableName)->first();

        return $module;
    }

    protected function makeView($module = null)
    {
        $this->breadcrumbs[] = array(
            'title' => $module->name
        );
        $view                = \View::make('photon::admin.module',
            array(
                'module'      => $module,
                'breadcrumbs' => $this->breadcrumbs
            )
        );

        return $view;
    }

    public function createEntry($moduleName)
    {
        $module  = $this->getModule($moduleName);
        $manager = new ModuleManager($module);
        $row     = $manager->createEntry($module, \Input::all());

        return $this->makeView($module->setFieldValues($row));
    }

    public function deleteIndex($moduleName, $id)
    {
        $module  = $this->getModule($moduleName);
        $manager = new ModuleManager($module);
        $manager->deleteEntry($id);

        return \Redirect::to("admin/{$moduleName}");
    }

    /**
     * Get Request on a module entry
     *
     * @param $moduleName
     * @param $id Entry ID
     */
    public function getIndex($moduleName, $id)
    {
        $module = $this->getModule($moduleName);
        $row    = (array)\DB::table($module->table_name)->find($id);

        return $this->makeView($module->setFieldValues($row));
    }

    /**
     * Entry Update
     *
     * @param $moduleName
     * @param $id Entry ID
     *
     * @return View
     */
    public function postIndex($moduleName, $id)
    {
        $module  = $this->getModule($moduleName);
        $manager = new ModuleManager($module);

        $manager->updateEntry($id, \Input::all());

        $row = (array)\DB::table($module->table_name)->find($id);

        return $this->makeView($module->setFieldValues($row));
    }
}