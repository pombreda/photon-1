<?php

/**
 * Description of Module
 *
 * @author Ivan Batić <ivan.batic@live.com>
 */

namespace Orangehill\Photon\Library\Creator\Facades;
use Illuminate\Support\Facades\Facade;

class ModuleCreator extends Facade {

    protected static function getFacadeAccessor() {
        return 'ModuleCreator';
    }

}
