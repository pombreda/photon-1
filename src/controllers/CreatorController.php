<?php

/**
 * Description of CreatorController
 *
 * @author Ivan Batić <ivan.batic@live.com>
 */

namespace Orangehill\Photon;

use Orangehill\Photon\ApiController;

//use Orangehill\Photon\Library\Creator\ModuleCreator;

class CreatorController extends ApiController {

    public function getIndex($moduleId = null) {
        return $this->getResponse();
    }

    public function postIndex($moduleId = null) {
//        $this->apiResponse
//        return $this->ap
    }

    public function postValidate() {
        $moduleValid = \Photon\ModuleCreator::validateModule(\Input::get('module'));
        echo 'Should validate ' . print_r($moduleInput, true);
        return $this->apiResponse->addMessage('success', 'Yeah, validate!')->toJsonResponse();
    }

    public function postModuleDiff() {
        
    }

    public function getValidate() {
        var_dump('Getting validate', \Photon\ModuleCreator::sayHello(), func_get_args());
    }

}
