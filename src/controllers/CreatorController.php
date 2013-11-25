<?php

namespace Orangehill\Photon;

use Illuminate\Support\MessageBag;
use Illuminate\Validation\Validator;
use Orangehill\Photon\Library\Support\ArrayLib;

class CreatorController extends ApiController
{

    public function postValidate()
    {
        $validated = $this->passInputThroughModuleValidation(\Input::get('with-changes', true));

        return $this->makeValidationResponse($validated['message_bag'], $validated['changes'])->toJsonResponse();
    }

    private function passInputThroughModuleValidation($withChanges = false)
    {
        $passed = \Photon\ModuleCreator::validateModule(\Input::get('module'));
        if ($passed === true && $withChanges) {
            $changes = Library\Creator\Report\ModuleDiff::diffChanges(\Input::get('module'));
        }

        return array(
            'message_bag' => $passed,
            'changes'     => isset($changes) ? $changes : array()
        );
    }

    private function makeValidationResponse($messageBag, array $changes = array())
    {
        return $this->apiResponse
            ->setField('valid', $messageBag === true ? true : false)
            ->setField('changes', $changes ? : array())
            ->setMessageBag($messageBag === true ? new MessageBag() : $messageBag);
    }

    /* Utility functions */

    public function postModule()
    {
        $validated = $this->passInputThroughModuleValidation(true);
        if ($validated['message_bag'] === true && !ArrayLib::isDeepEmpty($validated['changes'])) {
            \Photon\ModuleCreator::applyModuleChanges(\Input::get('module'), $validated['changes']);
            if (\Input::has('run_migrations') && \Input::get('run_migrations', false)) {
                \Artisan::call('migrate');
            }
        }
    }

    public function deleteModule($id = null)
    {
        if (is_numeric($id)) {
            \Photon\ModuleCreator::deleteModule($id);
        }

        return \Response::json($this->apiResponse);
    }

}
