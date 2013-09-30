<?php namespace Orangehill\Photon;

class SettingsValidator {

    /**
     * Laravel application
     * @var Illuminate\Foundation\Application
     */
    public $app;

    /**
     * Module repository instance
     * @var Orangehill\Photon\ModuleRepositoryInterface
     */
	public $module;

    /**
     * Field repository instance
     * @var Orangehill\Photon\FieldRepositoryInterface
     */
	public $field;

	public function __construct(ModuleRepositoryInterface $module, FieldRepositoryInterface $field) {
		$this->module = $module;
		$this->field = $field;
		$this->app = app();
	}

	/**
	 * Check if there are dependant modules
	 * @param  int $moduleId
	 * @return array
	 */
	public function checkDependantModules($moduleId)
	{
		$module = $this->module->getDependantModuleName($moduleId);
		if($module !== false) {
			return $this->formatResponse("'" . $module . "' is a child module of current module. Removal operation is not possible.");
		}

		return $this->formatResponse('', false);
	}

	/**
	 * Check if there are dependant fields
	 * @param  int $fieldId
	 * @return array
	 */
	public function checkDependantFields($fieldId)
	{
		$field = $this->field->getDependantFieldName($fieldId);
		if($field !== false) {
			return $this->formatResponse("'" . $field . "' uses current module as a relation table. Removal operation is not possible.");
		}

		return $this->formatResponse('', false);
	}

	/**
	 * Validates submitted form data
	 * @param array $fieldsIds
	 * @param int $id
	 * @return array e.g. on failure ['fails' => true, 'message' => 'Some message.'] or
	 * or on success: ['fails' => false]
	 */
	public function validate(array $fieldIds, $id = null)
	{
		if(empty($fieldIds) && $this->app['request']->get('is_folder')!=='1') {
			 // return 'No module fields specified.';
			 return $this->formatResponse('No module fields specified.');
		}

		// Check if remove module request is received
		if($this->app['request']->get('remove_request')==1) {
			// Check if there are dependant modules
			$dependantModules = $this->checkDependantModules($this->app['request']->get('module_id'));
			if($dependantModules['fails']) {
				return $dependantModules;
			}

			// Check if there are dependant fields
			$dependantFields = $this->checkDependantFields($this->app['request']->get('module_id'));
			if($dependantFields['fails']) {
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
		    return $this->formatResponse($messages->first());
		}

		// Set fails to true in response array
	    return $this->formatResponse('', false);
	}

	/**
	 * Formats a response array
	 * @param string $message
	 * @param bool $fails
	 * @return array [description]
	 */
	public function formatResponse($message, $fails = true)
	{
		$output = array();
		$output['fails'] = $fails;
		if ($message AND $message != '') {
			$output['message'] = $message;
		}
		return $output;
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
