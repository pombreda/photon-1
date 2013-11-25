<?php

/**
 * Description of TestController
 *
 * @author Ivan Batić <ivan.batic@live.com>
 */

namespace Orangehill\Photon;

use Illuminate\Validation\Validator;

class TestController extends \BaseController
{

    public function index()
    {
        $var = \Folder::with('modules')->get();
        var_dump($var);
        die();
    }

}
