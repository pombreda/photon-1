<?php namespace Orangehill\Photon;

class SettingsValidate {

	/**
	 * Check if there are dependant modules
	 * @param  int $moduleId
	 * @return mixed
	 */
	protected function checkDependantModules($moduleId)
	{
		$module = Module::where('parent_module', $moduleId)->first();
		if(!is_null($module)) {
			return "'" . $module->module_name . "' is a child module of current module. Removal operation is not possible.";
		}
		return false;
	}

	/**
	 * Check if there are dependant fields
	 * @param  int $moduleId
	 * @return mixed
	 */
	protected function checkDependantFields($moduleId)
	{
		$field = Field::where('relation_table', $moduleId)->first();
		if(!is_null($field)) {
			return "'" . $field->field_name . "' uses current module as a relation table. Removal operation is not possible.";
		}
		return false;
	}

	/**
	 * Validates submitted form data
	 * @param array $fieldsIds
	 * @param int $id
	 * @return Mixed
	 */
	public function validate(array $fieldIds, $id = null)
	{
		if (is_numeric($id)) $editing = true;

		if(empty($fieldIds) && \Input::get('is_folder')!=='1') {
			 return 'No module fields specified.';
		}

		// Check if remove module request is received
		if(\Input::get('remove_request')==1) {
			// Check if there are dependant modules
			$dependantModules = $this->checkDependantModules(\Input::get('module_id'));
			if($dependantModules !== false) {
				return $dependantModules;
			}

			// Check if there are dependant fields
			$dependantFields = $this->checkDependantFields(\Input::get('module_id'));
			if($dependantFields !== false) {
				return $dependantFields;
			}

		}

		$input = \Input::all();

		// Module Rules
		$rules = array();

		$rules['module_name'] = 'required|unique:modules,module_name';
		$rules['table_name'] = 'required|not_in:fields,groups,migrations,modules,throttle,users,users_groups|unique:modules,table_name';

		$messages = array();

		// Individual Fields Rules
		foreach ($fieldIds as $fieldId) {
			$rules['field_name' . $fieldId] = 'required|not_in:' . $this->getNotInArray($fieldIds, 'field_name', $fieldId);
			$rules['column_name' . $fieldId] = 'required|not_in:' . $this->getNotInArray($fieldIds, 'column_name', $fieldId);

			// Set custom messages
			$messages['field_name' . $fieldId . '.required'] = 'At least one field name is empty.';
			$messages['field_name' . $fieldId . '.not_in'] = 'Field name \'' . \Input::get('field_name' . $fieldId). '\' is not unique.';
			$messages['column_name' . $fieldId . '.required'] = 'At least one column name is empty.';
			$messages['column_name' . $fieldId . '.not_in'] = 'Column name \'' . \Input::get('column_name' . $fieldId). '\' is not unique.';
		}

		// Modify rules if in editing mode
		if (is_numeric($id)) {
			// Module Rules
			$rules['module_name'] .= "," . $id;
			unset($rules['table_name']);
		}

		$validator = $this->generateValidator($input, $rules, $messages);

		// If validation fails return first message
		if ($validator->fails()) {
		    $messages = $validator->messages();
		    return $messages->first();
		}

		return true;
	}

	/**
	 * Generates a validator
	 * @param  array $input
	 * @param  array $rules
	 * @param  array $messages
	 * @return object
	 */
	public function generateValidator($input, $rules, $messages)
	{
		return \Validator::make($input, $rules, $messages);
	}

	/**
	 * Builds a not_in comma delimited list
	 * @param  array $fieldIds
	 * @param  string $name
	 * @param  int $excludeId
	 * @return array
	 */
	protected function getNotInArray($fieldIds, $name, $excludeId)
	{
		$notIn = array();

		foreach ($fieldIds as $fieldId)
		{
			if ($fieldId == $excludeId) continue;
			$notIn[] = \Input::get($name . $fieldId);
		}

		return implode(",", $notIn);
	}

}
