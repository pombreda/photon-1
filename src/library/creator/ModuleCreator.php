<?php

/**
 * @author Ivan Batić <ivan.batic@live.com>
 */

namespace Orangehill\Photon\Library\Creator;

class ModuleCreator {

    public function sayHello() {
        var_dump('Hello, dude!');
    }

    public function validateModule($input) {
        $id = $input['id'];
        $validator = \Validate::make($input, array(
                'id'            => "unique:modules,id,{$id}",
                'name'          => "required|unique:modules,name,{$id}",
                'table_name'    => "required|alpha_dash|unique:modules,table_name,{$id}",
                'parent_module' => 'integer,exists:module,id',
                'nestable'      => 'in:0,1',
        ));
    }

}
