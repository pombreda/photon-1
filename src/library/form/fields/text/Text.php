<?php

/**
 * Description of Text
 *
 * @author Ivan Batić <ivan.batic@live.com>
 */

namespace Orangehill\Photon\Library\Form\Fields\Text;

use Orangehill\Photon\Library\Form\Core\Field;

class Text extends Field {
    
    public function getHtmlValue() {
        return htmlentities($this->value);
    }


}
