<div class="control-group row-fluid">
    <div class="span3">
        <label class="control-label" for="{{$field->column_name}}">
            {{$field->name}}
            @if($field->tooltip_text)<a href="javascript:;" class="bootstrap-tooltip" data-placement="top" data-original-title="{{$field->tooltip_text}}"><i class="icon-photon info-circle"></i></a>@endif
        </label>
    </div>
    <div class="span9">
        <div class="controls">
            <input type="text" id="{{$field->column_name}}" name="{{$field->column_name}}" value="@if(isset($entry[$field->column_name])){{$entry[$field->column_name]}}@endif">
        </div>
    </div>
</div>
