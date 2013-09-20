<div class="breadcrumb-container">
    <ul class="xbreadcrumbs">
        <li>
            <a href="{{\Request::root()}}/admin">
                <i class="icon-photon home"></i>
            </a>
        </li>
        <li class="current">
            @foreach($breadcrumbs as $breadcrumb)
            <a href="{{$breadcrumb['url']}}">{{$breadcrumb['anchor']}}</a>
            @endforeach
        </li>
    </ul>
</div>
