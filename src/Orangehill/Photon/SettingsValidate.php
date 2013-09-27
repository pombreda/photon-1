<?php namespace Orangehill\Photon;

class SettingsValidate {

    /**
     * Laravel application
     * @var Illuminate\Foundation\Application
     */
    public $app;

    /**
     * Module repository instance
     * @var Orangehill\Photon\ModuleRepository
     */
	public $module;

    /**
     * Field repository instance
     * @var Orangehill\Photon\FieldRepository
     */
	public $field;

	public function __construct(ModuleRepository $module, FieldRepository $field) {
		$this->module = $module;
		$this->field = $field;
		$this->app = app();
	}

	/**
	 * Check if there are dependant modules
	 * @param  int $moduleId
	 * @return mixed
	 */
	public function checkDependantModules($moduleId)
	{
		$module = $this->module->countDependantModules($moduleId);
		if($module !== false) {
			return "'" . $module . "' is a child module of current module. Removal operation is not possible.";
		}

		return false;
	}

	/**
	 * Check if there are dependant fields
	 * @param  int $fieldId
	 * @return mixed
	 */
	public function checkDependantFields($fieldId)
	{
		$field = $this->field->countDependantFields($fieldId);
		if($field !== false) {
			return "'" . $field . "' uses current module as a relation table. Removal operation is not possible.";
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
		if(empty($fieldIds) && $this->app['request']->get('is_folder')!=='1') {
			 return 'No module fields specified.';
		}

		// Check if remove module request is received
		if($this->app['request']->get('remove_request')==1) {
			// Check if there are dependant modules
			$dependantModules = $this->checkDependantModules($this->app['request']->get('module_id'));
			if($dependantModules !== false) {
				return $dependantModules;
			}

			// Check if there are dependant fields
			$dependantFields = $this->checkDependantFields($this->app['request']->get('module_id'));
			if($dependantFields !== false) {
				return $dependantFields;
			}

		}

		$input = $this->app['request']->all();

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
			$messages['field_name' . $fieldId . '.not_in'] = 'Field name \'' . $this->app['request']->get('field_name' . $fieldId). '\' is not unique.';
			$messages['column_name' . $fieldId . '.required'] = 'At least one column name is empty.';
			$messages['column_name' . $fieldId . '.not_in'] = 'Column name \'' . $this->app['request']->get('column_name' . $fieldId). '\' is not unique.';
		}

		// Modify rules if in editing mode
		if (is_numeric($id)) {
			$rules['module_name'] .= "," . $id;
			unset($rules['table_name']);
		}

		$validator = $this->app['validator']->make($input, $rules, $messages);

		// If validation fails return first message
		if ($validator->fails()) {
		    $messages = $validator->messages();
		    return $messages->first();
		}

		return true;
	}

	/**
	 * Builds a not_in comma delimited list
	 * @param  array $fieldIds
	 * @param  string $name
	 * @param  int $excludeId
	 * @return array
	 */
	public function getNotInArray($fieldIds, $name, $excludeId)
	{
		$notIn = array();
		foreach ($fieldIds as $fieldId) {
			if ($fieldId == $excludeId) {
				continue;
			}
			$notIn[] = $this->app['request']->get($name . $fieldId);
		}

		return implode(",", $notIn);
	}

}
