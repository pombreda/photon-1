<div id="Dual_Multi_Select_with_Filter_Search" class="control-group row-fluid">
    <div class="span3">
        <label class="control-label" for="{{$field->column_name}}">
            {{$field->name}}
            @if($field->tooltip_text)<a href="javascript:;" class="bootstrap-tooltip" data-placement="top" data-original-title="{{$field->tooltip_text}}"><i class="icon-photon info-circle"></i></a>@endif
        </label>
    </div>
    <div class="span9">
        <div class="controls">
            <select multiple id="{{$field->column_name}}" name="{{$field->column_name}}[]" class="many-to-many">
                @foreach($manyToMany[$field->column_name]['available'] as $id => $anchor)
                <option value="{{$id}}" @if(in_array($id, $manyToMany[$field->column_name]['selected']))selected=""@endif>{{$anchor}}</option>
                @endforeach
            </select>
        </div>
    </div>
</div>
