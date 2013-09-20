<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8">
        @yield('title')
        @include('common.head')
    </head>
    <body class="body-login">
        <div class="container-login">
            <div class="form-centering-wrapper">
                <div class="form-window-login">
                    <div class="form-window-login-logo">
                        <div class="login-logo">
                            <img src="{{ Request::root() }}/images/photon/login-logo@2x.png" alt="{{ Config::get('app.projectName') }}"/>
                        </div>
                        @yield('content')
                    </div>
                </div>
            </div>
        </div>
        @include('common.javascript')
        @include('notifications')
        <script type="text/javascript" src="{{ Request::root() }}/js/plugins/jquery.validate.min.js"></script>
        @yield('javascript')
    </body>
</html>
