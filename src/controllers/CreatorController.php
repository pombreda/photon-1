<?php

namespace Orangehill\Photon;

use Illuminate\Http\JsonResponse;
use Illuminate\Support\MessageBag;
use Orangehill\Photon\Library\Support\ArrayLib;

class CreatorController extends ApiController
{

    /**
     * Posts the form for validation
     *
     * @return JsonResponse
     */
    public function postValidate()
    {
        $validated = $this->passInputThroughModuleValidation(\Input::get('with-changes', true));

        return $this->makeValidationResponse($validated['message_bag'], $validated['changes'])->toJsonResponse();
    }

    /**
     * @param bool $withChanges If diff results should be included with the validation status
     *
     * @return array <ul><li>message_bag : MessageBag</li><li>changes : array</li></ul>
     */
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

    /**
     * @param       $messageBag
     * @param array $changes
     *
     * @return $this
     */
    private function makeValidationResponse($messageBag, array $changes = array())
    {
        return $this->apiResponse
            // Validation passed if there is no MessageBag
            ->setField('valid', $messageBag === true ? true : false)
            ->setField('changes', $changes ? : array())
            ->setMessageBag($messageBag === true ? new MessageBag() : $messageBag);
    }

    /**
     * Handles module updates and creations
     */
    public function postModule()
    {
        // Validate the input
        $validated = $this->passInputThroughModuleValidation(true);

        // If validation passed and there are actual changes...
        if ($validated['message_bag'] === true && !ArrayLib::isDeepEmpty($validated['changes'])) {

            // Apply all changes
            \Photon\ModuleCreator::applyModuleChanges(\Input::get('module'), $validated['changes']);

            // Run migrations if needed
            if (\Input::has('run_migrations') && \Input::get('run_migrations', false)) {
                \Artisan::call('migrate');
            }
        }
    }

    /**
     * @param null $id Module ID
     *
     * @return JsonResponse
     */
    public function deleteModule($id = null)
    {
        if (is_numeric($id)) {
            \Photon\ModuleCreator::deleteModule($id);
        }

        return \Response::json($this->apiResponse);
    }

}
