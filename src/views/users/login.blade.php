@extends('layouts.auth')

{{-- Title --}}
@section('title')
<title>{{ Config::get('app.projectName') }} - Log in</title>
@stop

{{-- Content --}}
@section('content')
                        <h2 class="login-title">Welcome to {{ Config::get('app.projectName') }}!</h2>
                        <div class="login-member">Not a Member?&nbsp;<a href="{{ Request::root() . '/users/register' }}">Sign Up &#187;</a></div>
                        <div class="login-or">Or</div>
                        <div class="login-input-area">
                            <form method="post" action="{{ Request::fullUrl() }}" id="login-form">
                                <span class="help-block">Login With Your {{ Config::get('app.projectName') }} Account</span>
                                <input type="hidden" name="_token" value="{{ Session::getToken() }}">
                                <input type="text" name="email" id="email" value="{{ Request::old('email') }}" placeholder="Email" {{ ($errors->has('email')) ? 'class="error"' : '' }}>
                                {{ ($errors->has('email') ? '<span for="email" class="error">' . $errors->first('email') . '</span>' : '') }}
                                <input type="password" name="password" id="password" placeholder="Password" {{ ($errors->has('password')) ? 'class="error"' : '' }}>
                                {{ ($errors->has('password') ?  '<span for="password" class="error">' . $errors->first('password') . '</span>' : '') }}
                                <label class="control-label remember-me">
                                    <input type="checkbox" class="uniformCheckbox" value="1" name="rememberMe">
                                    Remember me
                                </label>
                                <button type="submit" class="btn btn-large btn-success btn-login">Login</button>
                            </form>
                            <a href="{{\Request::root() . '/users/resetpassword'}}" class="forgot-pass">Forgot Your Password?</a>
                        </div>
@stop

{{-- Javascript --}}
@section('javascript')
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
                $(".uniformCheckbox").uniform();
            });
        </script>
@stop
