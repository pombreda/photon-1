<?php

/**
 * Description of TestController
 *
 * @author Ivan Batić <ivan.batic@live.com>
 */

namespace Orangehill\Photon;
use Orangehill\Photon\Module;
class TestController extends \BaseController {

    public function index() {
        $mod = Module::with('Fields')->find(1)->toArray();
        var_dump($mod);
        die();
    }

}
