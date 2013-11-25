<div class="control-group row-fluid">
    <div class="span3">
        <label class="control-label" for="{{$field->column_name}}">
            {{$field->name}}
            @if($field->tooltip_text)<a href="javascript:;" class="bootstrap-tooltip" data-placement="top" data-original-title="{{$field->tooltip_text}}"><i class="icon-photon info-circle"></i></a>@endif
        </label>
    </div>
    <div class="span9">
        @if(isset($entry[$field->column_name]) AND $entry[$field->column_name]!='')
        <div id="image-cell-{{$field->column_name}}" class="image-cell" style="margin-top:10px;" data-column-name='{{$field->column_name}}' data-entry-id='{{$entry->id}}'>
            <img class="user-image" src="{{ Config::get('photon::photon.package_assets_uri') }}/assets/{{$module->table_name}}/{{$entry[$field->column_name]}}" alt="{{$entry[$field->column_name]}}" />
            <img class="row-shadow" src="{{ Config::get('photon::photon.package_assets_uri') }}/images/photon/w_shadow.png" alt="shadow">
        </div>
        @endif
        <div class="fileupload fileupload-new" data-provides="fileupload">
            <div class="input-append">
                <div class="uneditable-input span3"><i class="icon-file fileupload-exists"></i> <span class="fileupload-preview"></span></div><span class="btn btn-file"><span class="fileupload-new">Select file</span><span class="fileupload-exists">Change</span><input type="file" id="{{$field->column_name}}" name="{{$field->column_name}}"></span><a href="javascript:;" class="btn fileupload-exists" data-dismiss="fileupload">Remove</a>
            </div>
        </div>
    </div>
</div>
