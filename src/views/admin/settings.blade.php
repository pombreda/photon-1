@extends('photon::layouts.settings')

{{-- Title --}}
@section('title')
<title>{{ Config::get('photon::photon.title') }} - Settings</title>
@stop

@section('menuTitle')
Settings
@stop

{{-- Content --}}
@section('content')
<div class="container-fluid admin-settings" id="module-settings">
	<div class="form-legend">General Module Settings</div>
    <input type="hidden" name="module_id" id="module_id" value="{{$moduleId}}" />
    <input type="hidden" name="remove_request" id="remove_request" value="0" />
    <input type="hidden" name="next-auto-increment" id="next-auto-increment" value="{{$nextAutoIncrement}}" />
    <input type="hidden" name="is_folder" id="is_folder" value="@if($isFolder){{$module->is_folder}}@endif" />
    <div class="control-group row-fluid">
        <div class="span2">
            <label class="control-label" for="module_name">
            	Module Name
            	<a href="javascript:;" class="bootstrap-tooltip" tabIndex="-1" data-placement="top" data-original-title="Module name as it will appear in admin panel menu, as module page title etc."><i class="icon-photon info-circle"></i></a>
            </label>
        </div>
        <div class="span4">
            <div class="controls">
                <input id="module_name" type="text" name="module_name" @if($module)value="{{$module->module_name}}"@endif/>
            </div>
        </div>
        <div class="span2">
            <label class="control-label" for="table_name">
            	Table Name
            	<a href="javascript:;" class="bootstrap-tooltip" tabIndex="-1" data-placement="top" data-original-title="Auto-generated MYSQL table name that can be changed by typing in a new value."><i class="icon-photon info-circle"></i></a>
            </label>
        </div>
        <div class="span4">
            <div class="controls">
                <input id="table_name" type="text" name="table_name" @if($module) disabled value="{{$module->table_name}}"@endif/>
            </div>
        </div>
    </div>
    <div class="control-group row-fluid">
        <div class="span2">
            <label class="control-label" for="parent_module">
            	Parent Module
            	<a href="javascript:;" class="bootstrap-tooltip" tabIndex="-1" data-placement="top" data-original-title="If selected, current module will be nest-able under selected parent module's items."><i class="icon-photon info-circle"></i></a>
            </label>
        </div>
        <div class="span4">
            <div class="controls">
                <select name="parent_module" id="parent_module">
                    <option value="0">None</option>
                    @foreach($parentModules as $parentModule)
                    <option @if($module && $parentModule->id == $module->parent_module)selected=""@endif value="{{$parentModule->id}}">{{$parentModule->module_name}}</option>
                    @endforeach
                </select>
            </div>
        </div>
        <div class="span3">
            <label class="control-label" for="nestable">
                <input @if($module && $module->nestable==1)checked="checked"@endif type="checkbox" name="nestable" id="nestable" class="uniformCheckbox" value="1"> Nestable
                <a href="javascript:;" class="bootstrap-tooltip" tabIndex="-1" data-placement="top" data-original-title="If nesting is allowed this module's items will be nestable under each other (e.g. to create a category tree)."><i class="icon-photon info-circle"></i></a>
            </label>
        </div>
        <div class="span3 span-inset">
            <button type="button" class="btn pull-right" id="save-as-folder" @if($moduleId) disabled="disabled" @endif>Save as Folder</button>
        </div>
    </div>
</div>
@if(!$isFolder)
<div id="module-fields">@if($fieldsJson)<img src="{{ Config::get('photon::photon.package_assets_uri') }}/images/photon/preloader/2.gif" class="loader" alt="loader">@endif</div>
@endif
<div id="module-field-template">
	<!-- Module Field Template begins -->
	<div class="container-fluid admin-settings">
		<div class="form-legend"><span id="field-title" class="muted">Empty Field</span> <a href="javascript:;" class="remove-field" tabIndex="-1"><i class="icon-minus-sign"></i></a></div>
        <input type="hidden" name="field_id" id="field_id" value=""/>
	    <div class="control-group row-fluid">
	        <div class="span2">
	            <label class="control-label" for="field_name">
	            	Field Name
	            	<a href="javascript:;" class="bootstrap-tooltip" tabIndex="-1" data-placement="top" data-original-title="Field name as it will appear in module form page."><i class="icon-photon info-circle"></i></a>
	            </label>
	        </div>
	        <div class="span2">
	            <div class="controls">
	                <input id="field_name" type="text" name="field_name" />
	            </div>
	        </div>
	        <div class="span2">
	            <label class="control-label" for="field_type">
	            	Field Type
	            	<a href="javascript:;" class="bootstrap-tooltip" tabIndex="-1" data-placement="top" data-original-title="Module form field type."><i class="icon-photon info-circle"></i></a>
	            </label>
	        </div>
	        <div class="span2">
	            <div class="controls">
	                <select name="field_type" id="field_type">
	                	@foreach($fieldData['field_types'] as $field_type)
	                    <option value="{{$field_type[0]}}" data-column-type="{{$field_type[1]}}">{{ucwords(str_replace('-', ' ', $field_type[0]))}}</option>
	                    @endforeach
	                </select>
	            </div>
	        </div>
	        <div class="span2">
	            <label class="control-label" for="relation_table">
	            	Rel. Table
	            	<a href="javascript:;" class="bootstrap-tooltip" tabIndex="-1" data-placement="top" data-original-title="Relationship table (used only for 'One To One' and 'One To Many' Field Type options.)."><i class="icon-photon info-circle"></i></a>
	            </label>
	        </div>
	        <div class="span2">
	            <div class="controls">
	                <select name="relation_table" id="relation_table" disabled>
	                    <option value="">None</option>
                        @foreach($parentModules as $parentModule)
                        <option @if($module && $parentModule->id == $module->parent_module)selected=""@endif value="{{$parentModule->id}}">{{$parentModule->module_name}}</option>
                        @endforeach
	                </select>
	            </div>
	        </div>
	    </div>
	    <div class="control-group row-fluid">
	        <div class="span2">
	            <label class="control-label" for="column_name">
	            	Column Name
	            	<a href="javascript:;" class="bootstrap-tooltip" tabIndex="-1" data-placement="top" data-original-title="Auto-generated MYSQL column name that can be changed by typing in a new value."><i class="icon-photon info-circle"></i></a>
	            </label>
	        </div>
	        <div class="span2">
	            <div class="controls">
	                <input id="column_name" type="text" name="column_name" />
	            </div>
	        </div>
	        <div class="span2">
	            <label class="control-label" for="column_type">
	            	Column Type
	            	<a href="javascript:;" class="bootstrap-tooltip" tabIndex="-1" data-placement="top" data-original-title="MYSQL column type."><i class="icon-photon info-circle"></i></a>
	            </label>
	        </div>
	        <div class="span2">
	            <div class="controls">
	                <select name="column_type" id="column_type">
	                	@foreach($fieldData['column_types'] as $column_type)
	                    <option value="{{$column_type}}">{{$column_type}}</option>
	                    @endforeach
	                </select>
	            </div>
	        </div>
	        <div class="span2">
	            <label class="control-label" for="tooltip_text">
	            	Tooltip Text
	            	<a href="javascript:;" class="bootstrap-tooltip" tabIndex="-1" data-placement="top" data-original-title="Optional tooltip info text that would be displayed next to Field Name just like this tooltip info."><i class="icon-photon info-circle"></i></a>
	            </label>
	        </div>
	        <div class="span2">
	            <div class="controls">
	                <input id="tooltip_text" name="tooltip_text" type="text" />
	            </div>
	        </div>
	    </div>
	</div>
	<!-- Module Field Template ends -->
</div>

@stop

@section('form-controls')
<div class="container-fluid terminal" id="report">
    <div class="form-legend">Pending Actions</div>
    <div class="control-group row-fluid">
        <div class="span12 span-inset reportContainer"></div>
        <div class="span12 span-inset">
            <button type="button" class="btn btn-primary" id="commit-module" data-loading-text="Commiting...">Commit</button>
            <button type="button" class="btn" id="cancel-commit">Cancel</button>
        </div>
    </div>
</div>
<div class="container-fluid" id="form-controls">
    <!-- Form Controls begin -->
    <div class="control-group form-control row-fluid">
        <div class="span12">
            <div class="controls">
	        	<label class="radio" style="display: inline-block;">
	        		<input checked="checked" type="checkbox" name="run-migrations" class="uniformCheckbox" value="1">
                    Automatically run database migrations
                    <a href="javascript:;" class="bootstrap-tooltip" tabIndex="-1" data-placement="top" data-original-title="If not checked you will need to run migrations ('php artisan migrate') from the command line to update the database with current changes."><i class="icon-photon info-circle"></i></a>
                </label>
                @if(!$isFolder)
	            <button type="button" class="btn" id="add-new-field">Add New Field</button>
                @endif
	            <button type="button" class="btn btn-primary" id="save-module">Save Module Settings</button>
	            <button type="button" class="btn btn-danger form-control-reset">Remove Module</button>
	            <span class="confirm-reset-wrapper">
	                <span class="sure">Are you sure?</span>
	                <button type="button" class="btn btn-danger" id="remove-module">Yes</button>
	                <button type="button" class="btn form-control-cancel">No</button>
	            </span>
	        </div>
	    </div>
	</div>
	<!-- Form Controls end -->
</div>
@stop

{{-- Javascript --}}
@section('javascript')
        <script>
            var fieldsJson = {};
            @if($fieldsJson)fieldsJson = {{$fieldsJson}};@endif
        </script>
        <script type="text/javascript" src="{{ Config::get('photon::photon.package_assets_uri') }}/js/admin-settings.js"></script>
        <script>
            $().ready(function(){

                // Initialise checkboxes
            	$(".uniformCheckbox").uniform();

                var searchTags = [
                    "Dashboard",
                    "Form Elements",
                    "Graphs and Statistics",
                    "500 Internal Server Error",
                    "503 Service Unavailable"
                ];
                $( "#panelSearch" ).autocomplete({
                    source: searchTags
                });

                // Set selected node based on URL fragment
                var selectedNode = 'module_' + $.url().segment(3);

                // Initialise jsTree
                $("#jstree").jstree({

                    "json_data" : {

                        "ajax" : {
                            // the URL to fetch the data
                            "url" : "{{ \Request::root() }}/admin/jstree-settings",
                            // the `data` function is executed in the instance's scope
                            // the parameter is the node being loaded
                            // (may be -1, 0, or undefined when loading the root nodes)
                            "data" : function (n) {
                                // the result is fed to the AJAX request `data` option
                                return {
                                    "id" : n.attr ? n.attr("id").replace(n.attr("data-module-name") + "_","") : null
                                };
                            }
                        }

                    },

                    "plugins" : [
                        "themes",
                        "json_data",
                        "ui",
                        "themes",
                        "dnd",
                        "crrm",
                        "types"
                    ],

                    "ui" : {
                        "initially_select" : selectedNode
                    },

                    "core" : {
                        @if(isset($moduleId) AND is_numeric($moduleId))"initially_open" : ["module_{{$moduleId}}"]@endif
                    },

                    "themes" : {
                        "theme" : "photonui",
                        "url" : "{{ Config::get('photon::photon.package_assets_uri') }}/js/plugins/jstree/themes/photonui/style.css"
                    },

                    "crrm" : {
                        "move" : {
                            "default_position" : "first",
                            "check_move" : function (m) {
                                // m.np new parent
                                // m.o the node being moved
                                // $(m.np[0]).attr('rel') holds the node type name
                                // field nodes can be moved only inside it's parents
                                if($(m.o[0]).attr('data-module-name') == 'field') {
                                    return ($(m.o[0]).attr('data-parent-id') == $(m.np[0]).attr('id')) ? true : false;
                                }
                                // module nodes can be moved only inside root
                                if($(m.o[0]).attr('data-module-name') == 'module') {
                                    return ($(m.np[0]).attr('id') == 'jstree') ? true : false;
                                }
                                return false;
                            }
                        }
                    },

                    "types" : {
                        "types" : {
                            "default" : {
                                "select_node": function(item){
                                    window.location = $(item).find('a').attr('href');
                                    return true;
                                }
                            },
                            "file" : {
                                "select_node": function(){
                                    return false;
                                }
                            }
                        }
                    }
                })

                .bind("move_node.jstree", function (e, data) {
                    var node_name = $(data.rslt.o[0]).attr('data-module-name');
                    var id = $(data.rslt.o[0]).attr('id').replace(node_name + '_', '');
                    var parent_node_name = $(data.rslt.o[0].parentNode.parentNode).attr('data-module-name');
                    var parent_node_id = $(data.rslt.o[0].parentNode.parentNode).attr('id').replace(parent_node_name + '_', '');
                    var next_sibling = $(data.rslt.o[0].nextSibling).attr('id');
                    var previous_sibling = $(data.rslt.o[0].previousSibling).attr('id');
                    $.ajax({
                        async : false,
                        type: 'POST',
                        url : '{{ \Request::root() }}/admin/jstree-settings',
                        data : {
                            "id" : id,
                            "node_name" : node_name,
                            "parent_node_id" : parent_node_id,
                            "parent_node_name" : parent_node_name,
                            "next_sibling" : next_sibling ? next_sibling.replace(node_name + '_', '') : '',
                            "previous_sibling" : previous_sibling ? previous_sibling.replace(node_name + '_', '') : ''
                        }
                    });
                });

            });
        </script>
@stop
