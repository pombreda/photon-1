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
        $var = \Superhero::with('wonders')->get();
        var_dump($var->toArray());
        die();
    }

}
