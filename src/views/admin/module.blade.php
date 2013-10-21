@extends('photon::layouts.module')

{{-- Title --}}
@section('title')
<title>{{ Config::get('app.projectName') }} - {{ $module->name }}</title>
@stop

@section('menuTitle')
{{$module->name}}
@stop

{{-- Content --}}
@section('content')
<div class="container-fluid" id="module-settings">
    <div class="form-legend">{{ $module->name }}</div>
    @if(isset($entry->id))<input type="hidden" name="id" id="id" value="{{$entry->id}}" />@endif
    @foreach($fields as $field)
    @include('photon::admin.field-types.' . $field->field_type)
    @endforeach

@stop

@section('templates')
    <!-- Hover Template begin -->
        <div class="gallery-hover-template">
            <div class="hover-menu">
                <p>
                    <a id="show-caption" href="#modal-edit-dialog" data-toggle="modal">Enter Caption</a>
                </p>
                <div class="menu-cell-wrap">
                    <a href="javascript:;" class="menu-cell eye-cell"></a>
                    <!-- <a href="javascript:;" class="menu-cell">&nbsp;</a> -->
                    <!-- <a href="javascript:;" class="menu-cell">&nbsp;</a> -->
                    <a href="javascript:;" class="menu-cell menu-delete"></a>
                </div>
                <div class="confirm-delete">
                    <span>Delete?</span>
                    <a id="del-confirm" href="javascript:;">Yes</a>
                    <a id="del-cancel" href="javascript:;">No</a>
                </div>
            </div>
        </div>
    <!-- Hover Template end -->
    <!-- Bootstrap Lightbox Template begin -->
        <div id="galleryLightbox" class="lightbox hide fade"  tabindex="-1" role="dialog" aria-hidden="true">
            <div class='lightbox-header'>
                <button type="button" class="close" data-dismiss="lightbox" aria-hidden="true">&times;</button>
            </div>
            <div class='lightbox-content'>
                <img src="" alt="profile"/>
                <div class="lightbox-caption"><p>Caption</p></div>
            </div>
        </div>
    <!-- Bootstrap Lightbox Template end -->
@stop

{{-- Javascript --}}
@section('javascript')
        <script>
            $().ready(function(){

                // Initialise tiny WYSIWYG editor
                $('.rich-text').elrte({
                    lang: "en",
                    styleWithCSS: false,
                    height: 200,
                    toolbar: 'tiny'
                });

                // Initialise calendars
                $(".calendar").datepicker({
                    'dateFormat' : 'yy-mm-dd'
                });

                // Initialise one to many Select2
                $(".one-to-many, .weight").select2();

                // Initialise many to many picklist
                $(".many-to-many").pickList();

                // Initialise jsTree
                $("#jstree").jstree({

                    "json_data" : {

                        "ajax" : {
                            // the URL to fetch the data
                            "url" : "{{\Request::root()}}/admin/jstree/{{\Request::segment(2)}}",
                            // the `data` function is executed in the instance's scope
                            // the parameter is the node being loaded
                            // (may be -1, 0, or undefined when loading the root nodes)
                            "data" : function (n) {
                                // the result is fed to the AJAX request `data` option
                                return {
                                    "id" : n.attr ? n.attr("id").replace(n.attr("data-module-name") + "_","") : null,
                                    @if(isset($predefinedFields))
                                    "column_name" : "{{$predefinedFields[0]}}",
                                    @if(isset($predefinedFields[1]))"column_name_second" : "{{$predefinedFields[1]}}",@endif
                                    @if(isset($predefinedFields[2]))"column_name_third" : "{{$predefinedFields[2]}}",@endif
                                    @else
                                    "column_name" : "{{$fields[0]->column_name}}",
                                    @endif
                                    "table_name" : "{{\Request::segment(2)}}",
                                    "name" : "{{$module->table_name}}"
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
                        "types",
                        "cookies"
                    ],

                    // "ui" : {
                    //     "initially_select" : ["{{\Request::segment(2)}}_{{\Request::segment(3)}}"]
                    // },

                    "core" : {
                        "initially_open" : ["{{\Request::segment(2)}}_{{\Request::segment(3)}}"]
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
                                if($(m.o[0]).attr('data-module-name') == '{{\Request::segment(2)}}') {
                                    return ($(m.np[0]).attr('id') == 'jstree' || $(m.np[0]).attr('data-module-name') == '{{\Request::segment(2)}}') ? true : false;
                                }
                                return false;
                            }
                        }
                    },

                    "types" : {
                        "types" : {
                            "default" : {
                                "select_node": function(item){
                                    $('#parent_id').val($(item).attr("id").replace($(item).attr("data-module-name") + "_",""));
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
                        url : '{{\Request::root()}}/admin/jstree/{{\Request::segment(2)}}',
                        data : {
                            "id" : id,
                            "node_name" : node_name,
                            "parent_node_id" : parent_node_id,
                            "parent_node_name" : parent_node_name,
                            "next_sibling" : next_sibling ? next_sibling.replace(node_name + '_', '') : '',
                            "previous_sibling" : previous_sibling ? previous_sibling.replace(node_name + '_', '') : ''
                        }
                    });
                })

                .bind("dblclick.jstree", function (event) {
                    var item = $(event.target).closest("li");
                    window.location = $(item).find('a').attr('href');
                })

                .one("reopen.jstree", function (event, data) {
                    @if(\Request::segment(3))
                    $(this).jstree('deselect_all');
                    $(this).jstree('select_node', '#{{\Request::segment(2)}}_{{\Request::segment(3)}}');
                    @endif
                    var item = $(this).jstree('get_selected');
                    $('#parent_id').val($(item).attr("id").replace($(item).attr("data-module-name") + "_",""));
                });

            });
        </script>
        <script type="text/javascript" src="{{ Config::get('photon::photon.package_assets_uri') }}/js/admin-module.js"></script>
@stop
