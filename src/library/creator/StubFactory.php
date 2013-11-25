<?php
/**
 * Created by PhpStorm.
 * User: ivan
 * Date: 11/20/13
 * Time: 2:27 PM
 */

namespace Orangehill\Photon\Library\Creator;


class StubFactory
{

    public static function make($type, array $args = array())
    {
        $stub = null;
        switch ($type) {
            default:
                $stubName = __NAMESPACE__ . '\Stubs\\' . studly_case($type);
                $stub     = new $stubName($args);
                break;
        }

        return $stub;
    }
} 