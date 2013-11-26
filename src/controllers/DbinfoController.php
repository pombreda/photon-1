<?php
/**
 * User: ivan
 * Date: 11/12/13
 * Time: 3:43 PM
 */

namespace Orangehill\Photon;

use Illuminate\Database\Eloquent\Collection;

class DbinfoController extends ApiController
{
    public function getModules()
    {
        $modules = Module::all() ? : new Collection();

        return \Response::json(
            $this->apiResponse->setContent($modules->toArray())->toArray()
        );
    }
} 