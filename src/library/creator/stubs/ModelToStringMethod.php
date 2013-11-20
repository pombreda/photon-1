<?php
/**
 * Created by PhpStorm.
 * User: ivan
 * Date: 11/20/13
 * Time: 2:28 PM
 */

namespace Orangehill\Photon\Library\Creator\Stubs;


class ModelToStringMethod extends Stub
{

    public function initContent()
    {
        $this->setContent("
    public function __toString(){
        return \$this->{$this->args['field']};
    }"
        );
    }
}