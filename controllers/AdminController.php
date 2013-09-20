<?php namespace Orangehill\Photon;

/**
 * Handles all requests related to managing the data models
 */
class AdminController extends \Controller
{

	private $settings, $module;

	/**
	 * Constructor
	 */
	public function __construct()
	{
		$this->settings = new SettingsController;
		$this->module = new ModuleController;
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
	 * Show the Settings Page.
	 *
	 * @return Response
	 */
	public function getSettings($id = false)
	{
		$this->settings->init($id);
		// Show the Settings Page
		return \View::make('photon::admin.settings', $this->settings->data);
	}

	/**
	 * Receive the Settings Page Form POST data.
	 *
	 * @return Response
	 */
	public function postSettings($id = false)
	{
		$this->settings->init($id);
		return $this->settings->postSettings();
	}

	/**
	 * Gets the main menu
	 * @return array
	 */
	public static function getMainMenu()
	{
		// Get all the folder type modules
		$folders = Module::roots()->where('is_folder', 1)->get()->toArray();

		// This is anonoptics specific way of creating a menu. Should be handled differently.
		foreach($folders as $folder)
		{
			$folder['children'] = Module::roots()->where('parent_module', $folder['id'])->get();
			$output[] = $folder;
		}

		return $output;

	}

	/**
	 * Gets the next auto generated ID
	 * This function should be better off somewhere else :/
	 * @param  string $table
	 * @return int
	 */
	public static function getNextAutoIncrement($table)
	{
		$users = \DB::select("SHOW TABLE STATUS LIKE '$table'");

        return $users[0]->Auto_increment;
	}

	/**
	 * Dispatches any resource that was not found to ModuleController
	 * @param  array $parameters
	 * @return Response
	 */
	public function dispatch($parameters) {

		$this->module->init($parameters[0]);

		if(\Request::getMethod() == 'GET') return $this->module->getEntry();
		if(\Request::getMethod() == 'POST') return $this->module->postEntry();
		if(\Request::getMethod() == 'DELETE') {
			if(\Request::ajax()) return $this->module->deleteField();
			else return $this->module->deleteEntry();
		}
	}

	/**
	 * Called when no other matching method is found on a given controller
	 * @param  array $parameters
	 * @return Response
	 */
	public function missingMethod($parameters)
	{
		return $this->dispatch($parameters);
	}


}
