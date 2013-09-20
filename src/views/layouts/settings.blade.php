<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8">
        @yield('title')
        @include('photon::common.head')
    </head>
    <body class="body-inner">
        @include('photon::common.main-menu')
        @include('photon::common.panel')
        <div class="main-content">
            @include('photon::common.breadcrumbs')
            <header>
                <i class="icon-big-notepad"></i>
                <h2><small>Edit Module</small></h2>
                <h3><small>Use the form below to set module parameters.</small></h3>
            </header>
            <form class="form-horizontal" id="admin-settings-form" method="post" action="{{\Request::url()}}">
                @yield('content')
                @yield('form-controls')
            </form>
        </div>
        @include('photon::common.javascript')
        @include('photon::notifications')
        @yield('javascript')
    </body>
</html>
