"use strict";
function AdminSettings() {
    // Store reference to this for use in methods
    var self = this,
    // Base api url for ajax calls
        baseApiUrl = '/admin/creator/',
        baseSettingsPath = '/admin/settings',
    // Create a jQ object out of a template and store it for replication
        fieldTemplate = (function() {
            var $tpl = $('#module_field_template').find('.module-field').clone();
            var $relTable = $tpl.find('[data-input-name=relation_table]').empty();
            $.ajax({
                url    : '/admin/dbinfo/modules',
                type   : 'get',
                async  : false,
                success: function(response) {
                    $relTable.append($('<option/>').val('').text('None'));
                    $.each(response.content, function(index, element) {
                        $relTable.append($('<option/>').val(element.table_name).text(element.name));
                    });

                    $('#module_field_template').remove();
                }
            });
            return $tpl;
        })(),
    // Store loader jQ object
        $loader = $('.loader'),
    // New fields counter
        fieldCounter = 0,
    // Settings form
        $creatorForm = $('#admin_settings_form');

    /**
     * Send a network request
     * @param string action
     * @param function callback
     * @returns void
     */
    this.postAction = function(action, successCallback, errorCallback, method) {
        var method = method || 'post',
            $form = $('#admin_settings_form');

        // Check if form is valid before submitting
        if (!$form[0].checkValidity()) {
            $form.find('input, select').each(function() {
                if (!this.validity.valid) {
                    $(this).focus();
                    return false;
                }
            });
            return false;
        }

        var moduleId = $('#module_id').val();
        if (['delete', 'update'].indexOf(action) >= 0 && !moduleId) {
            throw 'Trying to ' + action + ' with no module id specified';
        }

        // Make the call
        $.ajax({
            url     : baseApiUrl + action,
            type    : method,
            data    : $form.serialize(),
            dataType: 'json',
            success : function(response) {
                if (typeof successCallback === 'function') {
                    successCallback(response);
                }
            }, error: function(status, error, xhr) {
                if (typeof errorCallback === 'function') {
                    errorCallback(status, error, xhr);
                }
            }
        });

    }

    /**
     * Adds a new field to the form
     * @param json data
     * @returns void
     */
    function addField(data) {
        // Field data can also be an event
        var fieldData = $.extend({
            id            : !data ? fieldCounter++ : null,
            name          : '',
            type          : 'string',
            relation_table: '',
            column_name   : null,
            column_type   : null,
            tooltip_text  : '',
            locked        : !data ? false : true
        }, data || {});

        // Store used selectors
        var $newField = getFieldTemplate(),
            $newFieldInputs = $newField.find('.module-field-input'),
            $container = $("#module-fields"),

        // This will be inserted into name in case added field is new
            newFieldInsertion = !data ? 'new][' : '';

        // Go through all inputs, assign them names and fill them up

        $newFieldInputs.each(function(index, element) {
            var name = $(element).attr('data-input-name');
            $(element).attr('name', 'module[fields][' + newFieldInsertion + fieldData.id + '][' + name + ']')
                .val(fieldData[name])
                .attr('data-bound', fieldData[name] ? 0 : 1);
        }).filter("select").select2();

        if (!data) {
            $newFieldInputs.filter("[data-input-name=id]").prop('disabled', true);
        }

        $newFieldInputs.filter('' +
                '[data-input-name=column_name], ' +
                '[data-input-name=relation_table], ' +
                '[data-input-name=column_type]'
            ).prop('disabled', fieldData.locked)
            .filter('select')
            .addClass(fieldData.locked ? 'locked' : '')
            .select2();

        $newField.attr({
            'data-field-id': fieldData.id
        }).show().appendTo($container).find('.bootstrap-tooltip').tooltip();
        // Removal event bound at the bottom

        // Set focus to field_name
        $newField.find('.module-field-input[data-input-name=name]').focus();
    }

    /**
     * Get the field template object
     * @returns jQuery object
     */
    function getFieldTemplate() {
        return fieldTemplate.clone();
    }

    /**
     * Bind event handlers to various events on the page
     * @returns void
     */
    function bindEvents() {
        // Used multiple times, store it and improve speed
        var $moduleFields = $('#module-fields');

        // Add a blank field on click
        $('#add-new-field').click(function() {
            addField();
        });

        $creatorForm.add($moduleFields).on('change', '[type=checkbox]', function() {
            var num = Number($(this).is(':checked'));
            var name = $(this).attr('name');
            $('input[name="' + name + '"]').val(num);
            console.log("Changing to ", num);
        });

        // Delete a field on click
        $moduleFields.on('click', '.remove-field', function() {
            $(this).parents('.module-field').remove();
        });

        // Mirror module name to table name
        $('#name').on('change keyup', function() {
            mirrorToSnake($(this), '#table_name');
        });

        $('#cancel-commit').click(function() {
            $('#report').hide();
            $creatorForm.show();
        });

        // Mirror field name to column name
        $moduleFields.on('change keyup', '.module-field-input[data-input-name=name]', function() {
            mirrorToSnake($(this),
                $(this).parents('.module-field').find('.module-field-input[data-input-name=column_name]'));
        });

        // Bind field type changes
        $moduleFields.on('change', '.module-field-input[data-input-name=type]', function() {
            // Selectors used more than once
            var $moduleField = $(this).parents('.module-field');
            var $selected = $(this).find(':selected');

            // Shortcuts
            var $relTable = $moduleField.find('.module-field-input[data-input-name=relation_table]');
            var $colType = $moduleField.find('.module-field-input[data-input-name=column_type]');

            // Enable/disable relation table selection
            $relTable.prop('readonly', ['one-to-many', 'many-to-many'
            ].indexOf($selected.val()) == -1).select2();

            // Enable/disable column type selection
            if (!$colType.prop('readonly')) {
                $colType.prop('readonly', $selected.attr('data-column-type').toLowerCase() === 'readonly').select();
            }
        });

        // Bind Validate button
        $("#validate_form").click(function() {
            self.postAction('validate?with-changes=true', function(response) {
                if (!response.valid && response.messages) {
                    var $msgList = $('<div/>');
                    jQuery.each(response.messages, function(msgbi, block) {
                        jQuery.each(block, function(index, message) {
                            $('<li/>').text(message).appendTo($msgList);
                        });
                    });
                    $.pnotify({
                        title: 'Error',
                        type : 'info',
                        text : $msgList.html()
                    });
                } else {
                    var changesExist = false;
                    jQuery.each(response.changes, function(i, e) {
                        if (e.length) {
                            changesExist = true;
                            return false;
                        }
                    });
                    if (!changesExist) {
                        $.pnotify({
                            title: 'No changes',
                            type : 'info'
                        });
                        return false;
                    }
                    var $changeblock = generateChangeBlock(response.changes);
                    var $container = $('#report').show().find('.report-container').empty().append($changeblock);
                    $creatorForm.hide();
                }
            });
        });

        // Bind commit module
        $('#commit-module').click(function() {
            var $btn = $(this);
            var originalTitle = $btn.text();
            $btn.text('Please Wait...').prop('disabled', true);
            self.postAction('module', function() {
                window.location.reload();
            }, function() {
                $btn.prop('disabled', false).text(originalTitle);
            });
        });

        // Bind Save button
        $('#save_module').click(function(e) {
            self.postAction($('#module_id').val() ? 'update' : 'create');
        });

        // Bind delete button
        $('#remove_module').click(function(e) {
            self.postAction('module/' + $('#module_id').val(), function() {
                window.location = baseSettingsPath;
            }, null, 'delete');
        });

        // Bind field type change
        $moduleFields.on('change', '[data-input-name=type]', function() {
            var $moduleField = $(this).parents('.module-field');
            var $columnType = $moduleField.find('[data-input-name=column_type]');

            // Early break if this field shouldn't be touched
            if ($columnType.hasClass('locked')) {
                return;
            }

            var typeMap = {
                'input-text'  : 'string',
                'rich-text'   : 'text',
                'image'       : 'string',
                'inline-image': 'string',
                'boolean'     : 'smallInteger',
                'calendar'    : 'timestamp',
                'one-to-many' : 'integer',
                'many-to-many': 'disabled',
                'weight'      : 'integer',
                'hidden'      : 'string'
            }
            var value = typeMap[$(this).val()] || 'string';
            if ($columnType.val() != value) {
                $.pnotify({
                    title: 'Type change',
                    type : 'info',
                    text : 'Column type changed from ' + $columnType.val() + ' to ' + value
                });
            }
            $columnType.val(value).prop('disabled', value === 'disabled').select2();
            // Hide the column type field if it's binding suggests disabling
            $columnType.parents('.span2').prev().andSelf().toggleClass('invisible', $columnType.prop('disabled'));
        });
    }

    function generateChangeBlock(changeSections) {
        var $block = $('<div/>');

        jQuery.each(changeSections, function(sectionsIndex, section) {
            jQuery.each(section, function(changesetIndex, changeset) {
                var $setContainer = $('<div/>').addClass('changeset');

                createChangesetHeading(changeset).appendTo($setContainer);

                if (changeset.type !== 'delete') {
                    jQuery.each(changeset.changes, function(changeIndex, change) {
                        $setContainer.append(createChangeEntry(change));
                    });
                }

                $setContainer.appendTo($block);
            });
        });
        return $block;
    }

    function createChangesetHeading(changeset) {
        var $entry = $('<div/>').addClass('changeset-entry');

        var actionText = changeset.type + ' ' + changeset.item_type;
        var actionClass = 'label label-warning';

        if (changeset.type == 'delete') {
            actionClass = 'label label-important';
        }
        else if (changeset.type == 'create') actionClass = 'label label-success';

        var $title = $('<span/>').text(actionText.toUpperCase() + ':').addClass(actionClass);
        var $name = $('<span/>').text(changeset.item_name).addClass(actionClass);

        return $entry.append($title).append(' ').append($name);
    }

    function createChangeEntry(change) {
        if (change.type == 'same') {
            return false;
        }
        if (change.type == 'create') change.type = 'set';

        var titleText = change.type + ' ' + change.name + ':',
            $entry = $('<div/>').addClass('changeset-entry'),
            $title = $('<span/>').text(titleText.toTitleCase().ucword()),
            $from = $('<span/>').addClass('label label-important').text(change.original),
            $to = $('<span/>').addClass('label label-success').text(change.new);

        $entry.append($title).append(' ');
        if (change.original) {
            $entry.append($from).append(' → ');
        }
        $entry.append($to);
        return $entry;
    }

    /**
     * Copies snake_case content of one element into another
     * @param string|object From Selector string or jQuery object
     * @param string|object To Selector string or jQuery object
     * @param string fromMethod can be val or text
     * @param string toMethod can be val or text
     * @returns void
     */
    function mirrorToSnake(from, to, fromMethod, toMethod) {
        var fromMethod = fromMethod || 'val',
            toMethod = toMethod || 'val',
            $from = typeof from === 'string' ? $(from) : from,
            $to = typeof to === 'string' ? $(to) : to;

        if (!$to.prop("readonly") && parseInt($to.attr('data-bound'))) {
            var valFrom = (fromMethod === 'val' ? $from.val() : $from.text()).trim().toSnakeCase();
            toMethod === 'val' ? $to.val(valFrom) : $to.text(valFrom);
        }
    }

    /**
     * Adds field according to the existing data
     * @param json fields
     * @returns void
     */
    this.fillWithFields = function(fields) {
        var fields = fields || {};
        jQuery.each(fields, function(index, element) {
            addField(element);
        });
    }

    /**
     * Constructor function
     * @returns void
     */
    function construct() {
        $loader.show();
        bindEvents();
        $('select').select2();
        $loader.hide();
    }

    construct();
}

// Run all this stuff
(function() {
    var adminSettings = new AdminSettings();
    adminSettings.fillWithFields(fieldsJson);
})();