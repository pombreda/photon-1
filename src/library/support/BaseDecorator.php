<?php

/**
 * Description of BaseDecorator
 *
 * @author Ivan Batić <ivan.batic@live.com>
 */

namespace Orangehill\Photon\Library\Support;

abstract class BaseDecorator
{

    protected $decoratedObject;

    public function __construct($decoratedObject)
    {
        $this->decoratedObject = $decoratedObject;
    }

    public function __call($name, $arguments)
    {
        return call_user_func('$this->decoratedObject->' . $name, $arguments);
    }

    public function getDecoratedObject()
    {
        return $this->decoratedObject;
    }

}
