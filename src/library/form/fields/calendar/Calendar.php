<?php

/**
 * Description of Text
 *
 * @author Ivan Batić <ivan.batic@live.com>
 */

namespace Orangehill\Photon\Library\Form\Fields\Calendar;

use Orangehill\Photon\Library\Form\Core\Field;
use Orangehill\Photon\Module;

class Calendar extends Field
{

    public function getHtmlValue(){
        return date('Y-m-d', strtotime($this->getValue()));
    }
}
