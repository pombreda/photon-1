<?php

/**
 * @author Ivan Batić <ivan.batic@live.com>
 */

namespace Orangehill\Photon;

use Illuminate\Http\JsonResponse;
use Orangehill\Photon\Library\Support\ApiResponse;

class ApiController extends \BaseController
{

    /** @var ApiResponse */
    protected $apiResponse;

    public function __construct(ApiResponse $apiResponse)
    {
        $this->apiResponse = $apiResponse;
    }

    /**
     *
     * Returns a json response made from the apiResponse Object
     *
     * @todo Implement different types of responses
     * @return JsonResponse
     */
    protected function getResponse($type = 'json')
    {
        return \Response::json($this->apiResponse->toArray());
    }

}
