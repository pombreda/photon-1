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
                        <h2 class="login-title">Account Creation</h2>
                        <div class="login-member">Already a Member?&nbsp;<a href="{{\Request::root() . '/login'}}">Log In &#187;</a></div>
                        <div class="login-or">Or</div>
                        <div class="login-input-area">
                            <form method="post" id="register-form">
                                <span class="help-block">Create Your Photon Account</span>
                                <input type="text" name="email" id="email" placeholder="Email">
                                <input type="text" name="firstName" id="firstName" placeholder="First Name">
                                <input type="text" name="lastName" id="lastName" placeholder="Last Name">
                                <input type="password" name="password" id="password" placeholder="Password">
                                <input type="password" name="repeatPassword" id="repeatPassword" placeholder="Retype Password">
                                <button type="submit" class="btn btn-large btn-success btn-login">Register</button>
                            </form>
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
