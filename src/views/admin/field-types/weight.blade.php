<div class="control-group row-fluid">
    <div class="span3">
        <label class="control-label" for="{{$field->column_name}}">
            {{$field->field_name}}
            @if($field->tooltip_text)<a href="javascript:;" class="bootstrap-tooltip" data-placement="top" data-original-title="{{$field->tooltip_text}}"><i class="icon-photon info-circle"></i></a>@endif
        </label>
    </div>
    <div class="span9">
        <div class="controls">
            <select id="{{$field->column_name}}" name="{{$field->column_name}}" class="one-to-many">
                <? $selected = isset($entry[$field->column_name]) ? $entry[$field->column_name] : 0 ?>
                @for($i = -10; $i <= 10; $i++)
                <option value="{{$i}}" @if($selected == $i)selected=""@endif>{{$i}}</option>
                @endfor
            </select>
        </div>
    </div>
</div>
