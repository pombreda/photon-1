<?php

/**
 * Description of ModuleValidator
 *
 * @author Ivan Batić <ivan.batic@live.com>
 */

namespace Orangehill\Photon\Library\Creator\Validator;

use Orangehill\Photon\Module;

class ModuleValidator {

    protected $input = array();

    /**
     * @return bool
     */
    public static function validateCreate(array $input) {
        $rules = array(
            'id'            => null,
            'name'   => null,
            'table_name'    => null,
            'parent_module' => null,
            'nestable'      => null,
            'is_folder'     => null,
            'icon'          => null,
            'parent_id'     => null
        );
        $fields = array_merge(array_map(function($e) {
                    $e = null;
                }, $rules), $input);

        $validator = \Validator::make($fields, $rules);
    }

    public function isValid() {
        
    }

}
