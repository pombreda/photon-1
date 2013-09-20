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
                        <h2 class="login-title">Password Reset</h2>
                        <div class="login-input-area" style="margin-top: 0;">
                            <form method="post" id="register-form">
                                <span class="help-block">Enter Your Email Address</span>
                                <input type="text" name="email" id="email" placeholder="Email">
                                <button type="submit" class="btn btn-large btn-success btn-login">Submit</button>
                            </form>
                            <a href="{{\Request::root() . '/login'}}" class="forgot-pass">Back to Log In</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        {{$javascript}}
        <script type="text/javascript" src="js/plugins/jquery.validate.min.js"></script>
        <script>
            $(window).load(function() {
                $("#register-form").validate({
                    errorElement: "span",
                    errorClass: "error",
                    onclick: true,
                    onkeyup: false,
                    rules: {
                        "email": {
                            required: true,
                            email: true
                        }
                    }
                });
            });
        </script>
    </body>
</html>
