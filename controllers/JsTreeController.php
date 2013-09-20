<?php namespace Orangehill\Photon;

class JsTreeController extends \BaseController {

	/**
	 * $modelInstance holds the current module model instance
	 * @var \Baum\Node
	 */
	protected $modelInstance;

	/**
	 * Show the Dashboard.
	 *
	 * @return Response
	 */
	public function getIndex()
	{

		$id = \Input::get('id') ?: null;
		$name = \Input::get('column_name') ?: null;
		$nameSecond = \Input::has('column_name_second') ? \Input::get('column_name_second') : null;
		$nameThird = \Input::has('column_name_third') ? \Input::get('column_name_third') : null;
		$tableName = \Input::get('table_name') ?: null;
		$moduleName = \Input::get('module_name') ?: null;

		$output = array();

		// Instantiate model
		$model = '\\Orangehill\\Photon\\' . studly_case($tableName);
		$this->modelInstance = new $model;

		if(is_null($id)) {

			// Get roots
			$entries = $this->modelInstance->roots()->get();

		} else {

			// Get parent by id
			$parent = $this->modelInstance->find($id);

			// Get children
			$entries = $parent->children()->get();

		}

		foreach($entries as $entry) {

			$node = array();

			// Check if entry name is from one to many relation
			$entry->$name = $this->checkOneToManyRelation($name, $entry);

			// Check if entry nameSecond is from one to many relation
			if (!is_null($nameSecond))
			{

				$entry->$name .= ' ' . $this->checkOneToManyRelation($nameSecond, $entry);

			}

			// Check if entry nameThird is from one to many relation
			if (!is_null($nameThird))
			{

				$entry->$name .= ' ' . $this->checkOneToManyRelation($nameThird, $entry);

			}

			$node['data'] = array(
				'title' => $entry->$name,
				'attr' => array(
					'href' => '/admin/' . $moduleName . '/' . $entry->id
				)
			);

			$node['attr'] = array(
				'data-module-name' => $moduleName,
				'id' => $moduleName . '_' . $entry->id
			);

			if (count($entry->children()->get())) $node['state'] = 'closed';

			$output[] = $node;

		}

		return \Response::json($output);

	}

	/**
	 * Checks if given custom name is from one to many relation
	 * @param  string $name
	 * @param  object $entry
	 * @return string
	 */
	public function checkOneToManyRelation($name, $entry){

		if(strpos($name, '.'))
		{
			$params = explode('.', $name);
			return \DB::table($params[0])->find($entry->$params[1])->$params[2];
		}

		return $entry->$name;
	}

	/**
	 * Receive the Settings Page Form POST data.
	 *
	 * @return Response
	 */
	public function postIndex()
	{

		$rules = array();

		$rules['id'] = 'required|numeric';
		$rules['node_name'] = 'required';
		$rules['parent_node_id'] = 'required_without:next_sibling,previous_sibling';

		$validator = \Validator::make(\Input::all(), $rules);

		// If validation fails return first message
		if ($validator->fails())
		{
		    $messages = $validator->messages();
		    return \Response::json($messages->first());
		}

		$node = \Orangehill\Photon\Jstree::find(\Input::get('id'));

		if(is_numeric(\Input::get('next_sibling'))) {
			$nextSibling = \Orangehill\Photon\Jstree::find(\Input::get('next_sibling'));
			$node->makePreviousSiblingOf($nextSibling);
		} elseif(is_numeric(\Input::get('previous_sibling'))) {
			$previousSibling = \Orangehill\Photon\Jstree::find(\Input::get('previous_sibling'));
			$node->makeNextSiblingOf($previousSibling);
		} elseif(is_numeric(\Input::get('parent_node_id'))) {
			$parent = \Orangehill\Photon\Jstree::find(\Input::get('parent_node_id'));
			$node->makeChildOf($parent);
		}

		return \Response::json(array('Success.'));

	}

}
