<?php

/**
 * Description of TestController
 *
 * @author Ivan Batić <ivan.batic@live.com>
 */

namespace Orangehill\Photon;

class TestController extends \BaseController
{

    public function index()
    {
        $heroes = \DB::connection()->select('show columns from high_mountains');
        var_dump($heroes);
        die();
    }

}
