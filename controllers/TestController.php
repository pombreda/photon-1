<?php

/**
 * Description of TestController
 *
 * @author Ivan Batić <ivan.batic@live.com>
 */

namespace Orangehill\Photon;

class TestController extends \BaseController {

    /**
     * Module repository instance
     * @var Orangehill\Photon\ModuleRepository
     */
    public $module;

    public function __construct(ModuleRepositoryInterface $module) {
        $this->module = $module;
    }


    public function index() {
        dd($this->module->test());
        $field = new \Orangehill\Photon\Library\Form\Fields\Text\Text();
        $field->setAttribute('name', 'my-name');
        $field->setValue('Ivan Batic');
        $field->render();
//        $ivan = new Input\TextInputField();
//        $ivan->setName('First Name')
//            ->setValue('Ivan')
//            ->setTitle('Your First Name')
//            ->setTooltip('Enter your first name here');
//
//        $marko = clone $ivan;
//        $marko->setValue('Marko');
//
//        $ages = new Input\TextArrayField();
//        $ages->setName('Ages of Youth')->setValue(array(13, 14, 16, 19, 21));
//
//        $fields = new Input\FieldCollection();
//        $fields->add($ivan)->add($marko)->add($ages);
//
//
//        $view = \View::make('photon::admin.field_poc', array(
//                'input_fields' => $fields
//        ));
//        return $view;
    }

}
