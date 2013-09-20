<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8">
        <title>{{$metaTitle}}</title>
        {{$head}}
    </head>
    <body class="body-login">
        <div class="container-login">
            <div class="form-centering-wrapper">
                <div class="form-window-login">
                    <div class="form-window-login-logo">
                        <div class="login-logo">
                            <img src="images/photon/login-logo@2x.png" alt="Photon UI"/>
                        </div>
                        <h2 class="login-title">Welcome to Pera UI!</h2>
                        <div class="login-member">Not a Member?&nbsp;<a href="{{\Request::root() . '/register'}}">Sign Up &#187;</a></div>
                        <div class="login-or">Or</div>
                        <div class="login-input-area">
                            <form method="post" id="login-form">
                                <span class="help-block">Login With Your Photon Account</span>
                                <input type="text" name="email" id="email" placeholder="Email">
                                <input type="password" name="password" id="password" placeholder="Password">
                                <button type="submit" class="btn btn-large btn-success btn-login">Login</button>
                            </form>
                            <a href="{{\Request::root() . '/password'}}" class="forgot-pass">Forgot Your Password?</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        {{$javascript}}
        <script type="text/javascript" src="js/plugins/jquery.validate.min.js"></script>
        <script>
            $(window).load(function() {
                $("#login-form").validate({
                    errorElement: "span",
                    errorClass: "error",
                    onclick: true,
                    onkeyup: false,
                    rules: {
                        "email": {
                            required: true,
                            email: true
                        },
                        "password": {
                            required: true
                        }
                    }
                });
            });
        </script>
    </body>
</html>
