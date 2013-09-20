<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8">
        @yield('title')
        @include('photon::common.head')
    </head>
    <body class="body-dashboard">
        @include('photon::common.main-menu')
        <div class="container-fluid dashboard dashboard-title">
            <div class="row-fluid">
                <div class="span12">
                    <h1>Dashboard</h1>
                </div>
            </div>
        </div>
        @include('photon::common.javascript')
        @include('photon::notifications')
        @yield('javascript')
    </body>
</html>
