<?php

namespace Orangehill\Photon;

class JsTreeSettingsController extends \BaseController {

    /**
     * Show the Dashboard.
     *
     * @return Response
     */
    public function getIndex() {

        $id = \Input::get('id') ? : null;

        $output = array();

        if (is_null($id)) {

            // Get modules
            $modules = Module::roots()->get();

            foreach ($modules as $module) {

                $node = array();

                $node['data'] = array(
                    'title' => $module->name,
                    'attr'  => array(
                        'href' => '/admin/settings/' . $module->id
                    )
                );

                $node['attr'] = array(
                    'data-module-name' => 'module',
                    'id'               => 'module_' . $module->id
                );

                if (count($module->fields))
                    $node['state'] = 'closed';

                $output[] = $node;
            }
        } else {

            $fields = Field::roots()->where('module_id', $id)->get();

            foreach ($fields as $field) {

                $node = array();

                $node['data'] = array(
                    'title' => $field->name,
                    'attr'  => array(
                        'href' => '#'
                    )
                );
                $node['attr'] = array(
                    'data-module-name' => 'field',
                    'rel'              => 'file',
                    'data-parent-id'   => 'module_' . $id,
                    'id'               => 'field_' . $field->id
                );

                $output[] = $node;
            }
        }

        return \Response::json($output);
    }

    /**
     * Receive the Settings Page Form POST data.
     *
     * @return Response
     */
    public function postIndex() {
        /**
          id:field_8
          node_name:field
          parent_node_id:module_1
          parent_node_name:module
          next_sibling:field_2
         */
        $rules = array();

        $rules['id'] = 'required|numeric';
        $rules['node_name'] = 'required';
        // $rules['parent_node_id'] = 'required';
        // $rules['parent_node_name'] = 'required';
        $rules['previous_sibling'] = 'required_without:next_sibling';

        $validator = \Validator::make(\Input::all(), $rules);

        // If validation fails return first message
        if ($validator->fails()) {
            $messages = $validator->messages();
            return \Response::json($messages->first());
        }

        $modelName = '\\Orangehill\\Photon\\' . ucfirst(\Input::get('node_name'));

        $model = new $modelName;

        $node = $model->find(\Input::get('id'));

        if (is_numeric(\Input::get('next_sibling'))) {
            $nextSibling = $model->find(\Input::get('next_sibling'));
            $node->makePreviousSiblingOf($nextSibling);
        } else {
            $previousSibling = $model->find(\Input::get('previous_sibling'));
            $node->makeNextSiblingOf($previousSibling);
        }

        return \Response::json(array('Success.'));
    }

}
