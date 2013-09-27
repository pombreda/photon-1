<?php namespace Orangehill\Photon;

class TestController extends \BaseController {

    public $repo;

    public function __construct(ModuleRepository $repo)
    {
        $this->repo = $repo;
    }

    public function test()
    {
        dd($this->repo->test());
        dd('finre');
    }

}
