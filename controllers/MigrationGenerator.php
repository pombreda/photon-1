<?php namespace Orangehill\Photon;

class MigrationGenerator extends \BaseController {

	/**
	 * Generates a migration that creates a new table
	 * Original generator CLI command example: php artisan generate:migration create_posts_table --fields="title:string, body:text"
	 * @param string $table
	 * @param array $fields
	 * @return void
	 */
	public function create($table, $fields)
	{
		$params = array();
		$params['name'] = 'create_' . $table;
		$params['--fields'] = $this->concatFields($fields);

		\Artisan::call('generate:migration', $params);
	}

	/**
	 * Generates a migration that adds a field to existing table
	 * Original generator CLI command example: php artisan generate:migration add_user_id_to_posts_table --fields="title:string, body:text"
	 * @param string $table
	 * @param array $fields
	 * @return void
	 */
	public function add($table, $fields)
	{
		if (!is_array($fields)) return false;
		$params = array();
		$params['name'] = 'add_' . key($fields) . '_to_' . $table . '_table';
		$params['--fields'] = $this->concatFields($fields);

		return \Artisan::call('generate:migration', $params);

	}

	/**
	 * Generates a migration that removes a field from existing table
	 * Original generator CLI command example: php artisan generate:migration remove_user_id_from_posts_table --fields="title:string, body:text"
	 * @param string $table
	 * @param array $fields
	 * @return void
	 */
	public function remove($table, $fields)
	{
		if (!is_array($fields)) return false;
		$params = array();
		$params['name'] = 'remove_' . key($fields) . '_from_' . $table . '_table';
		$params['--fields'] = $this->concatFields($fields);


		return \Artisan::call('generate:migration', $params);

	}

	/**
	 * Generates a migration that destroys a table
	 * Original generator CLI command example: php artisan generate:migration destroy_posts_table --fields="title:string, body:text"
	 * @param string $table
	 * @param array $fields
	 * @return void
	 */
	public function destroy($table, $fields)
	{
		if (!is_array($fields)) return false;
		$params = array();
		$params['name'] = 'destroy_' . $table;
		$params['--fields'] = $this->concatFields($fields);

		return \Artisan::call('generate:migration', $params);

	}

	/**
	 * Returns a string formatted as e.g. 'key0:val0, key1:val1'
	 * @param  array $array
	 * @return string
	 */
	protected function concatFields($array)
	{
		if(is_array($array))
		{

			$output = array();

			foreach($array as $key=>$val)
			{
				$output[] = $key . ':' . $val;
			}
			return implode(', ', $output);
		}
		return false;
	}

}
