<?php

namespace Orangehill\Photon;

class MigrationGenerator {

    /**
     * Generates a migration that creates a new table
     * Original generator CLI command example: php artisan generate:migration create_posts_table --fields="title:string, body:text"
     * @param string $table
     * @param array $fields
     * @return void
     */
    public function create($table, array $fields) {
        $params = array(
            'name'     => 'create_' . $table,
            '--fields' => $this->concatFields($fields)
        );
        \Artisan::call('generate:migration', $params);
    }

    /**
     * Generates a migration that adds a field to existing table
     * Original generator CLI command example: php artisan generate:migration add_user_id_to_posts_table --fields="title:string, body:text"
     * @param string $table
     * @param array $fields
     * @return void
     */
    public function add($table, array $fields) {
        $key = key($fields);
        $params = array(
            'name'     => "add_{$key}_to_{$table}_table",
            '--fields' => $this->concatFields($fields)
        );
        \Artisan::call('generate:migration', $params);
    }

    /**
     * Generates a migration that removes a field from existing table
     * Original generator CLI command example: php artisan generate:migration remove_user_id_from_posts_table --fields="title:string, body:text"
     * @param string $table
     * @param array $fields
     * @return void
     */
    public function remove($table, array $fields) {
        $key = key($fields);
        $params = array(
            'name'     => "remove_{$key}_from_{$table}_table",
            '--fields' => $this->concatFields($fields)
        );
        \Artisan::call('generate:migration', $params);
    }

    /**
     * Generates a migration that destroys a table
     * Original generator CLI command example: php artisan generate:migration destroy_posts_table --fields="title:string, body:text"
     * @param string $table
     * @param array $fields
     * @return void
     */
    public function destroy($table, array $fields) {
        $params = array(
            'name'     => "destroy_{$table}",
            '--fields' => $this->concatFields($fields)
        );
        \Artisan::call('generate:migration', $params);
    }

    /**
     * Returns a string formatted as e.g. 'key0:val0, key1:val1'
     * @param  array $array
     * @return string
     */
    protected function concatFields(array $array) {
        $output = array();
        foreach ($array as $key => $val) {
            $output[] = $key . ':' . $val;
        }
        return implode(', ', $output);
    }

}
