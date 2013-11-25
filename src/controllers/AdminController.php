<?php namespace Orangehill\Photon;

//error_reporting(0);
use Illuminate\Database\Eloquent\Collection;

/**
 * Handles all requests related to managing the data models
 */
class AdminController extends \Controller
{


    /**
     * Gets the main menu
     *
     * @return array
     */
    public static function getMainMenu()
    {
        $folders = Folder::with('modules')->orderBy('lft')->get()->filter(function ($e) {
                return !$e->modules->isEmpty();
            }
        );

        $freeModules = Module::whereNull('folder_id')->orderBy('lft')->get();

        return $folders->merge($freeModules);
    }

    /**
     * Gets the next auto generated ID
     * This function should be better off somewhere else :/
     *
     * @param  string $table
     *
     * @return int
     * @deprecated
     * @todo Check usages and remove
     */
    public static function getNextAutoIncrement($table)
    {
        $users = \DB::select("SHOW TABLE STATUS LIKE '$table'");

        return $users[0]->Auto_increment;
    }

    /**
     * Show the Dashboard.
     *
     * @return Response
     */
    public function getIndex()
    {
        // Show the Dashboard
        return \View::make('photon::dashboard.default');
    }

    /**
     * Called when no other matching method is found on a given controller
     *
     * @param  array $parameters
     *
     * @return Response
     */
    public function missingMethod($parameters)
    {
        return $this->dispatch($parameters);
    }

    /**
     * Dispatches any resource that was not found to ModuleController
     *
     * @param  array $parameters
     *
     * @return Response
     */
    public function dispatch($parameters)
    {
        $tableName = snake_case(str_plural($parameters[0]));

        $this->module->init($parameters[0], $parameters[1]);
        $method   = \Request::getMethod();
        $response = null;

        if ($method == 'GET') {
            $response = $this->module->getEntry();
        } elseif ($method == 'POST') {
            $response = $this->module->postEntry();
        } elseif ($method == 'DELETE') {
            if (\Request::ajax()) {
                $response = $this->module->deleteField();
            } else {
                $response = $this->module->deleteEntry();
            }
        }

        return $response;
    }


}
