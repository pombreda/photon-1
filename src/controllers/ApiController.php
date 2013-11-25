<?php

/**
 * Description of ApiController
 *
 * @author Ivan Batić <ivan.batic@live.com>
 */

namespace Orangehill\Photon;

use Orangehill\Photon\Library\Support\ApiResponse;

class ApiController extends \BaseController {

    /** @var ApiResponse */
    protected $apiResponse;

    public function __construct(ApiResponse $apiResponse) {
        $this->apiResponse = $apiResponse;
    }

    protected function getResponse($type = 'json') {
        return \Response::json($this->apiResponse->toArray());
    }

}
