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
            <form class="form-horizontal" id="admin-module-form" method="post" action="{{\Request::url()}}" enctype="multipart/form-data">
                @yield('content')
                @section('form-controls')
                    <!-- Form Controls begin -->
                    <div class="control-group form-control row-fluid">
                        <div class="span12">
                            <div class="controls">
                                <button id="save-module" type="button" class="btn btn-primary">Save</button>
                                <button type="button" class="btn btn-danger form-control-reset">Delete</button>
                                <span class="confirm-reset-wrapper">
                                    <span class="sure">Are you sure?</span>
                                    <button type="button" class="btn btn-danger" id="delete-module">Yes</button>
                                    <button type="button" class="btn form-control-cancel">No</button>
                                </span>
                            </div>
                        </div>
                    </div>
                    <!-- Form Controls end -->
                </div>
                @show
            </form>
        </div>
        @include('photon::common.javascript')
        @include('photon::notifications')
        @yield('templates')
        @yield('javascript')
    </body>
</html>
