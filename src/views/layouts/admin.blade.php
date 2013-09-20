<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8">
        @yield('title')
        @include('common.head')
    </head>
    <body class="body-inner">
        @include('common.main-menu')
        @include('common.panel')
        <div class="main-content">
            @include('common.breadcrumbs')
            <header>
                <i class="icon-big-notepad"></i>
                <h2><small>Edit Item</small></h2>
                <h3><small>Use the form below to alter the item values.</small></h3>
            </header>
            <form class="form-horizontal">
                <div class="container-fluid">
                    @yield('content')
                    @section('form-controls')
                    <!-- Form Contols begin -->
                    <div class="control-group form-control row-fluid">
                        <div class="span12">
                            <div class="controls">
                                <button type="button" class="btn btn-primary">Save</button>
                                <button type="button" class="btn btn-danger form-control-reset">Reset</button>
                                <span class="confirm-reset-wrapper">
                                    <span class="sure">Are you sure?</span>
                                    <button type="button" class="btn btn-danger">Yes</button>
                                    <button type="button" class="btn form-control-cancel">No</button>
                                </span>
                            </div>
                        </div>
                    </div>
                    <!-- Form Contols end -->
                    @show
                </div>
            </form>
        </div>
        @include('common.javascript')
        @include('notifications')
        @yield('javascript')
    </body>
</html>
