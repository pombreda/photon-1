<div class="control-group row-fluid">
    <div class="span3">
        <label class="control-label" for="{{$field->getName()}}">
            {{$field->getName()}}
            @if($field->getTooltip())<a href="javascript:;" class="bootstrap-tooltip" data-placement="top" data-original-title="{{$field->getTooltip()}}"><i class="icon-photon info-circle"></i></a>@endif
        </label>
    </div>
    <div class="span9">
        <div class="controls">
            <input type="text" id="{{$field->getName()}}" name="{{$field->getName()}}" value="{{$field->getHtmlValue()}}">
        </div>
    </div>
</div>
