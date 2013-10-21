<div class="btn-toolbar btn-mobile-menus">
    <button class="btn btn-main-menu"></button>
</div>

<div class="nav-fixed-left" style="visibility: hidden">
    <ul class="nav nav-side-menu">
        <li class="shadow-layer"></li>
        <li>
            <a href="{{\Request::root()}}/admin" >
                <i class="icon-photon home"></i>
                <span class="nav-selection">Dashboard</span>
            </a>
        </li>
        @foreach($mainMenu as $item)
        <li>
            <a href="javascript:/" >
                <i class="icon-photon @if($item['icon']!=''){{$item['icon']}}@else{{'document_alt_stroke'}}@endif"></i>
                <span class="nav-selection">{{$item['name']}}</span>
            </a>
            @if ($item['children'])
            <div class="sub-nav">
                <ul class="nav">
                    @foreach ($item['children'] as $child)
                    <li>
                        <a href="{{\Request::root()}}/admin/{{$child->table_name}}">{{$child->name}}</a>
                    </li>
                    @endforeach
                </ul>
            </div>
            @endif
        </li>
        @endforeach
        <li class="nav-logout">
            <a href="{{\Request::root()}}/users/logout">
                <i class="icon-photon key_stroke"></i><span class="nav-selection">Logout</span>
            </a>
        </li>
    </ul>
</div>
