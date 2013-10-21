<?php

/**
 * Description of TestController
 *
 * @author Ivan Batić <ivan.batic@live.com>
 */

namespace Orangehill\Photon;

class TestController extends \BaseController {

    public function index() {
        $module = new Module();
        $validator = new Library\Creator\Validator\ModuleValidator();
        $module->validate($validator);
        var_dump("Hey controller!", $module);
        die();
//        $fs = new \Illuminate\Filesystem\Filesystem;
//        $cache = new \Way\Generators\Cache($fs);
//        $gen = new \Way\Generators\Generators\MigrationGenerator($fs, $cache);
//        $cmd = new \Way\Generators\Commands\MigrationGeneratorCommand($gen);
//        var_dump($gen);
//        MigrationGenerator::add('testRepo', array('name:text', 'id:int'));
    }

}
