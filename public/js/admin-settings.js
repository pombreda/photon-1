var adminSettings = {};

(function(context) {
	context.elements = new Array (
		'field-title',
		'field_id',
		'field_name',
		'field_type',
		'relation_table',
		'column_name',
		'column_type',
		'tooltip_text'
	),
	context.lockedColumnNames = {},
	context.id = $('#next-auto-increment').val()*1,
	context.template = false,
	context.editingMode = false,
	context.init = function(){
		// Determine if we're inserting new or editing existing module
		if(!$.isEmptyObject(fieldsJson)) context.editingMode = true;

		if(context.template===false) {
			context.template = $('#module-field-template').html();
			$('#module-field-template').remove();
		}
		$('#parent_module').select2();
		$('#add-new-field').click(function(){
			adminSettings.addField({});
		});
		// Generate existing fields
		if(context.editingMode) {
			$('.loader').hide();
			$(fieldsJson).each(function(index, value){
				adminSettings.addField(value);
			});
			context.editingMode = false;
		}

		// Handle automatic table name generation
		context.tableNameLocked = false;

		$("#table_name").keyup(function(){
			if($(this).val()==='') {
				context.tableNameLocked = false;
				context.convertToTableName();
			} else {
				context.tableNameLocked = true;
			}
		});
		$("#module_name").keyup(function(){
			if (context.tableNameLocked || context.editingMode) return false;
			context.convertToTableName();
		});
		$('#save-module').click(function(){
			// $('#admin-settings-form').submit();
			// return false;
			$.ajax({
				type: "POST",
				url: $('#admin-settings-form').attr('action'),
				data: $('#admin-settings-form').serialize(),
				success: function(response) {
					if(response.status==='success') {
						context.showReport(response.data.report);
					} else if(response.status==='error') {
						$.pnotify({
							title: 'Error',
							type: 'info',
							text: response.message
						});
					}
				}
			});
		});
		$('#remove-module').click(function(){
			$('#remove_request').val(1);
			$('#save-module').click();
			return false;
		});
		$('#commit-module').click(function(){
			// Set the button to loading state (Twitter Bootstrap feature)
			$(this).button('loading');
			// Hide the cancel button
			$('#cancel-commit').hide();
			// Submit the form
			$('#admin-settings-form').submit();
			return false;
		});
		$('#save-as-folder').click(function(){
			// Set the button to loading state (Twitter Bootstrap feature)
			$(this).button('loading');
			// Set the save_as_folder option true
			$('#is_folder').val('1');
			// Submit the form
			$('#admin-settings-form').submit();
			return false;
		});
		$('#cancel-commit').click(function(){
			context.cancelCommit();
			$('#remove_request').val(0);
			return false;
		});
	},
	context.showReport = function(report){
		$('.reportContainer').html(report);
		$('#report').show();
		$('#form-controls').hide();
		$('#module-fields').hide();
		$('#module-settings').hide();
	},
	context.cancelCommit = function(){
		$('.reportContainer').empty();
		$('#report').hide();
		$('#form-controls').show();
		$('#module-fields').show();
		$('#module-settings').show();
	},
    context.addField = function(fieldData){
		var addFieldId = (context.editingMode) ? fieldData.id : context.id;
		$('#module-fields').append(context.template);
		$('#module-fields > div:last').attr('id', addFieldId);
		// Change id and name attributes all field menu form items:
		$(context.elements).each(function(key, val){
			$('[for="' + val + '"]').attr('for', val + addFieldId);
			$('#' + addFieldId + ' #' + val).attr('id', val + addFieldId).attr('name', val + addFieldId);
		});
		// Populate the form if fieldData object is not empty
		if(context.editingMode) {
			$('#field_id' + addFieldId).val(fieldData.id);
			$('#field-title' + addFieldId).html(fieldData.field_name).removeClass('muted');
			$('#field_name' + addFieldId).val(fieldData.field_name);
			$('#field_type' + addFieldId + ' [value="' + fieldData.field_type + '"]').prop('selected', true);
			if(fieldData.relation_table!==null) {
				$('#relation_table' + addFieldId).prop('disabled', false);
				$('#relation_table' + addFieldId).select2();
				$('#relation_table' + addFieldId + ' [value="' + fieldData.relation_table + '"]').prop('selected', true);
			}
			$('#column_name' + addFieldId).val(fieldData.column_name);
			$('#column_type' + addFieldId + ' [value="' + fieldData.column_type + '"]').prop('selected', true);
			$('#tooltip_text' + addFieldId).val(fieldData.tooltip_text);
		}
		// Bind Select2 plugin to select menus
		$('#' + addFieldId + ' select').select2();
		// Bind Tooltip plugin
		$('.bootstrap-tooltip').tooltip();
		// Bind onclick event to enable module field removal
		$('#' + addFieldId + ' .remove-field').click(function(){
			$(this).closest('.admin-settings').remove();
		});
		// Handle automatic column_type option selection and disabling of column_type/
			$('#field_type' + addFieldId).change(function(){
				var dataColumnType = $(this).find(":selected").attr('data-column-type');
				var fieldId = $(this).attr('id').replace('field_type' , '');
				// enable/disable column_type select
				if($(this).find(":selected").val()==='one-to-many' || $(this).find(":selected").val()==='many-to-many') {
					$('#relation_table' + fieldId).prop('disabled', false);
					$('#relation_table' + fieldId).select2();
				} else {
					$('#relation_table' + fieldId).prop('disabled', 'disabled');
					$('#relation_table' + fieldId).select2();
				}
				// enable/disable column_type select
				if(dataColumnType==='DISABLED') {
					$('#column_type' + fieldId).prop('disabled', 'disabled');
					$('#column_type' + fieldId).select2();
					return false;
				} else {
					$('#column_type' + fieldId).prop('disabled', false);
					$('#column_type' + fieldId).select2();
				}
				// notify that the column_type select option has changed
				if($('#column_type' + fieldId).select2("val")!==dataColumnType) {
					$('#column_type' + fieldId).select2("val", dataColumnType);
					$.pnotify({
						title: 'Notification',
						type: 'info',
						text: 'Column Type for current field has automatically been set to ' + dataColumnType + '.'
					});
				}
			});
		// Handle automatic column name generation
		context.lockedColumnNames[addFieldId] = false;
		$('#field_name' + addFieldId).keyup(function(){
			context.nameGenerator($(this).attr('id'));
		});
		$('#column_name' + addFieldId).keyup(function(){
			var fieldId = $(this).attr('id').replace('column_name' , '');
			if($(this).val()==='') {
				context.lockedColumnNames[fieldId] = false;
				context.convertToColumnName(fieldId);
			} else {
				context.lockedColumnNames[fieldId] = true;
			}
		});
		// Set focus to field_name
		if(!context.editingMode) {
			$('#field_name' + addFieldId).focus();
		}
		// Trigger the change() event to unlock any one-to-many or many-to-many field type rel.table selects
		$('#field_type' + addFieldId).change();
		if(!context.editingMode) context.id += 1;
    },
    context.nameGenerator = function(id) {
		var fieldId = id.replace('field_name' , '');
		var value = $($('#' + id)).val();
		if(value==='') {
			$('#field-title' + fieldId).html('Empty Field').addClass('muted');
		} else {
			$('#field-title' + fieldId).html(value).removeClass('muted');
		}
		if (context.lockedColumnNames[fieldId]===false) context.convertToColumnName(fieldId);
    },
    context.convertToColumnName = function(id){
		var value = $('#field_name' + id).val();
		value = value.replace(/ /g,"_").toLowerCase();
		$('#column_name' + id).val(value);
    },
    context.convertToTableName = function(){
		var key = $("#module_name").val();
		key = key.replace(/ /g,"_").toLowerCase();
		$("#table_name").val(key);
	};
})(adminSettings);

$().ready(function(){
	adminSettings.init();
});
