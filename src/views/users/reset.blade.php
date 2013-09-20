@extends('layouts.auth')

{{-- Title --}}
@section('title')
<title>{{Config::get('app.projectName')}} - Password Reset</title>
@stop

{{-- Content --}}
@section('content')
                        <h2 class="login-title">Password Reset</h2>
                        <div class="login-input-area" style="margin-top: 0;">
                            <form method="post" id="register-form">
                                <span class="help-block">Enter Your Email Address</span>
                                <input type="text" name="email" id="email" value="{{ Request::old('email') }}" {{ ($errors->has('email')) ? 'class="error"' : '' }} placeholder="Email">
                                {{ ($errors->has('email') ? '<span for="email" class="error">' . $errors->first('email') . '</span>' : '') }}
                                <button type="submit" class="btn btn-large btn-success btn-login">Reset Password</button>
                            </form>
                            <a href="{{\Request::root() . '/users/login'}}" class="forgot-pass">Back to Log In</a>
                        </div>
@stop

{{-- Javascript --}}
@section('javascript')
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
@stop
