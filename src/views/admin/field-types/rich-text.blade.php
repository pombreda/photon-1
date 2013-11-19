<div class="control-group row-fluid">
    <div class="span3">
        <label class="control-label" for="{{$field->column_name}}">
            {{$field->name}}
            @if($field->tooltip_text)<a href="javascript:;" class="bootstrap-tooltip" data-placement="top" data-original-title="{{$field->tooltip_text}}"><i class="icon-photon info-circle"></i></a>@endif
        </label>
    </div>
    <div class="span9">
        <div class="controls elrte-wrapper">
            <textarea id="{{$field->column_name}}" name="{{$field->column_name}}" rows="2" class="auto-resize rich-text">@if(isset($entry[$field->column_name])){{$entry[$field->column_name]}}@endif</textarea>
        </div>
    </div>
</div>
