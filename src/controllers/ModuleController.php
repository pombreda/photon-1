<?php namespace Orangehill\Photon;

use Illuminate\Database\Eloquent\Model as EloquentModel;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Redirect;
use Illuminate\View\View;
use Orangehill\Photon\Library\ModuleManager;

class ModuleController extends \BaseController
{
    /**
     * Array of breadcrumb entries
     *
     * @var array Entry:array<ul><li>title</li><li>url</li></ul>
     */
    protected $breadcrumbs = array();

    /**
     * Returns a blank module view
     *
     * @param $module
     *
     * @return View
     */
    public function moduleIndex($module)
    {
        $module = $this->getModule($module);
        $view   = $this->makeView($module);
        return $view;
    }

    /**
     * Fetches the Module object based on a table name
     *
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

    /**
     * Creates a View based on a given model
     *
     * @param Module $module Module that will be sent to the view
     *
     * @return View
     */
    protected function makeView($module = null)
    {
        $this->breadcrumbs[] = array(
            'title' => $module->name
        );

        $view = \View::make('photon::admin.module',
            array(
                'module'      => $module,
                'breadcrumbs' => $this->breadcrumbs
            )
        );

        return $view;
    }

    /**
     * Initiates an entry creation on a given module, based on posted \Input data
     *
     * @param $moduleName
     *
     * @return RedirectResponse
     */
    public function createEntry($moduleName)
    {
        $module  = $this->getModule($moduleName);
        $manager = new ModuleManager($module);
        
        $row     = $manager->createEntry($module, \Input::all());
        $action  = \Config::get('photon::photon.row_creation_redirection');

        // If action is `entry`, proceed to edit this one, otherwise go back to the creation view
        return \Redirect::to("admin/{$moduleName}" . ($action == 'entry' ? "/{$row['id']}" : ''));
    }

    /**
     * Handles the entry deletion request
     *
     * @param string $moduleName
     * @param int    $row
     *
     * @return RedirectResponse
     */
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
     * @param $id int ID
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