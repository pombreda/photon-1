@extends('layouts.admin')

{{-- Title --}}
@section('title')
<title>{{ Config::get('app.projectName') }} - Settings</title>
@stop

@section('menuTitle')
Settings
@stop



{{-- Content --}}
@section('content')
	<div class="form-legend">Input Text</div>
    <!--Input Text begin-->
    <div class="control-group row-fluid">
        <div class="span3">
            <label class="control-label" for="inputText">
            	Input Text
            	<a href="javascript:;" class="bootstrap-tooltip" data-placement="top" data-original-title="Please enter some data."><i class="icon-photon info-circle"></i></a>
            </label>
        </div>
        <div class="span9">
            <div class="controls">
                <input id="inputText" type="text" name="inputText" />
            </div>
        </div>
    </div>
    <!--Input Text end-->
    <!--WYSIWYG Editor - Normal Options begin-->
    <div class="control-group row-fluid">
        <div class="span3">
            <label class="control-label">Rich Text</label>
        </div>
        <div class="span9">
            <div class="controls elrte-wrapper">
                <textarea id="normal" rows="2" class="auto-resize"></textarea>
            </div>
        </div>
    </div>
    <!--WYSIWYG Editor - Normal Options end-->
    <!--Image Upload begin-->
    <div class="control-group row-fluid">
        <div class="span3">
            <label class="control-label">Image Upload</label>
        </div>
        <div class="span9">
			<div class="fileupload fileupload-new" data-provides="fileupload" style="margin-bottom:15px;">
				<div class="fileupload-preview thumbnail" style="width: 200px; height: 150px;"></div>
				<div>
					<span class="btn btn-file"><span class="fileupload-new">Select image</span><span class="fileupload-exists">Change</span><input type="file" /></span>
					<a href="#" class="btn fileupload-exists" data-dismiss="fileupload">Remove</a>
				</div>
			</div>
		</div>
    </div>
    <!--Image Upload end-->
    <!--Boolean begin-->
    <div class="control-group row-fluid">
        <div class="span3">
            <label class="control-label">Boolean</label>
        </div>
        <div class="span9 span-inset">
        	<div data-on-label="<i class='icon-photon check'></i>" data-off-label="<i class='icon-photon x'></i>" class="switch switch-small" data-on="success" data-off="danger">
                <input type="checkbox" checked="checked">
            </div>
		</div>
    </div>
    <!--Boolean end-->
    <!--Date Picker begin-->
    <div id="Inline_Date_Picker" class="control-group row-fluid">
        <div class="span3">
            <label class="control-label">Date Picker</label>
        </div>
        <div class="span9">
            <div class="controls">
                <div id="datepickerInline"></div>
            </div>
        </div>
    </div>
    <!--Date Picker end-->
    <!--One to Many begin-->
        <div class="control-group row-fluid">
            <div class="span3">
                <label class="control-label" for="selectBoxFilter">One to Many</label>
            </div>
            <div class="span9">
                <div class="controls">
                    <select name="selectBoxFilter" id="selectBoxFilter">
                        <option selected="" value="All">All</option>
                        <option value="Beige">Beige</option>
                        <option value="Black">Black</option>
                        <option value="Blue">Blue</option>
                        <option value="Bronze">Bronze</option>
                        <option value="Brown">Brown</option>
                        <option value="Gold">Gold</option>
                        <option value="Gray">Gray</option>
                        <option value="Green">Green</option>
                        <option value="Orange">Orange</option>
                        <option value="Pink">Pink</option>
                        <option value="Purple">Purple</option>
                        <option value="Red">Red</option>
                        <option value="Silver">Silver</option>
                        <option value="Turquoise">Turquoise</option>
                        <option value="White">White</option>
                        <option value="Yellow">Yellow</option>
                    </select>
                </div>
            </div>
        </div>
        <!--Select Box with Filter Search end-->
        <!--Many to Many begin-->
        <div class="control-group row-fluid">
            <div class="span3">
                <label class="control-label" for="multiFilter">Many to Many</label>
            </div>
            <div class="span9">
                <div class="controls">
                    <select multiple name="multiFilter" id="multiFilter">
                        <option value="Beige">Beige</option>
                        <option value="Black">Black</option>
                        <option value="Blue">Blue</option>
                        <option value="Bronze">Bronze</option>
                        <option value="Brown">Brown</option>
                        <option value="Gold">Gold</option>
                        <option value="Gray">Gray</option>
                        <option value="Green">Green</option>
                        <option value="Orange">Orange</option>
                        <option value="Pink">Pink</option>
                        <option value="Purple">Purple</option>
                        <option value="Red">Red</option>
                        <option value="Silver">Silver</option>
                        <option selected="" value="Turquoise">Turquoise</option>
                        <option value="White">White</option>
                        <option value="Yellow">Yellow</option>
                    </select>
                </div>
            </div>
        </div>
        <!--Many to Many end-->
@stop

{{-- Javascript --}}
@section('javascript')
        <script>
            $().ready(function(){
	            $('#normal').elrte({
	                lang: "en",
	                styleWithCSS: false,
	                height: 200,
	                toolbar: 'normal'
	            });
	            $("#datepickerInline").datepicker();
	            $("#selectBoxFilter").select2();
	            $("#multiFilter").select2();
                var searchTags = [
                    "Dashboard",
                    "Form Elements",
                    "Graphs and Statistics",
                    "Typography",
                    "Grid",
                    "Tables",
                    "Maps",
                    "Sidebar Widgets",
                    "Error Pages",
                    "Help",
                    "Input Fields",
                    "Masked Input Fields",
                    "Autotabs",
                    "Text Areas",
                    "Select Menus",
                    "Other Form Elements",
                    "Form Validation",
                    "UI Elements",
                    "Graphs",
                    "Statistical Elements",
                    "400 Bad Request",
                    "401 Unauthorized",
                    "403 Forbidden",
                    "404 Page Not Found",
                    "500 Internal Server Error",
                    "503 Service Unavailable"
                ];
                $( "#panelSearch" ).autocomplete({
                    source: searchTags
                });

                $("#jstree").jstree({
                    "json_data" : {
                        "data" : [
                            {
                                "data" : {
                                    "title" : "Get Started",
                                    "attr" : { "href" : "#" }
                                },
                                "children" : [
                                    {
                                        "data" : {
                                            "title" : "Signing Up",
                                            "attr" : { "href" : "#" }
                                        }
                                    },
                                    {
                                        "data" : {
                                            "title" : "Logging In",
                                            "attr" : { "href" : "#" }
                                        }
                                    }
                                ]
                            },
                            {
                                "data" : {
                                    "title" : "Manage Your Account",
                                    "attr" : { "href" : "#" }
                                },
                                "attr" : { "id" : "node1" },
                                "children" : [
                                    {
                                        "data" : {
                                            "title" : "Account Settings",
                                            "attr" : { "href" : "#" }
                                        }
                                    },
                                    {
                                        "data" : {
                                            "title" : "Warnings and Blocks",
                                            "attr" : { "href" : "#" }
                                        }
                                    },
                                    {
                                        "data" : {
                                            "title" : "Reseting Your Password",
                                            "attr" : { "href" : "#" }
                                        },
                                        "attr" : { "id" : "node2" },
                                        "children" : [
                                            {
                                                "data" : {
                                                    "title" : "How Do I Reset My Password?",
                                                    "attr" : { "href" : "#" }
                                                },
                                                "attr" : { "id" : "node3" },
                                            },
                                            {
                                                "data" : {
                                                    "title" : "I Forgot My Password",
                                                    "attr" : { "href" : "#" }
                                                }
                                            },
                                            {
                                                "data" : {
                                                    "title" : "Can You Send Me a Copy of Password Without Reseting It?",
                                                    "attr" : { "href" : "#" }
                                                }
                                            },
                                            {
                                                "data" : {
                                                    "title" : "How Can I Recover My Secret Question?",
                                                    "attr" : { "href" : "#" }
                                                }
                                            }
                                        ]
                                    },
                                    {
                                        "data" : {
                                            "title" : "Deactivating, Deleting, Saving",
                                            "attr" : { "href" : "#" }
                                        }
                                    },
                                    {
                                        "data" : {
                                            "title" : "Downloading Your Info",
                                            "attr" : { "href" : "#" }
                                        }
                                    }
                                ]
                            },
                            {
                                "data" : {
                                    "title" : "Security",
                                    "attr" : { "href" : "#" }
                                }
                            },
                            {
                                "data" : {
                                    "title" : "Privacy",
                                    "attr" : { "href" : "#" }
                                }
                            }
                        ]
                    },
                    "plugins" : [ "themes", "json_data", "ui" ],
                    "ui" : {
                        "initially_select" : [ "node3" ]
                    },
                    "core" : {
                        "initially_open" : [ "node1" , "node2" ]
                    }
                });

            });
        </script>
@stop
