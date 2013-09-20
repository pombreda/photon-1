@extends('layouts.auth')

{{-- Title --}}
@section('title')
<title>{{Config::get('app.projectName')}} - Register</title>
@stop

{{-- Content --}}
@section('content')
                        <h2 class="login-title">Account Creation</h2>
                        <div class="login-member">Already a Member?&nbsp;<a href="{{\Request::root() . '/users/login'}}">Log In &#187;</a></div>
                        <div class="login-or">Or</div>
                        <div class="login-input-area">
                            <form method="post" action="{{ Request::fullUrl() }}" id="register-form">
                                <span class="help-block">Create Your {{ Config::get('app.projectName') }} Account</span>
                                <input type="hidden" name="_token" id="_token" value="{{ Session::getToken() }}" />
                                <input type="text" name="email" value="{{ Request::old('email') }}" id="email" placeholder="Email" {{ ($errors->has('email')) ? 'class="error"' : '' }}>
                                {{ ($errors->has('email') ? '<span for="email" class="error">' . $errors->first('email') . '</span>' : '') }}
                                <input type="password" name="password" id="password" placeholder="Password" {{ ($errors->has('password')) ? 'class="error"' : '' }}>
                                {{ ($errors->has('password') ?  '<span for="password" class="error">' . $errors->first('password') . '</span>' : '') }}
                                <input type="password" name="password_confirmation" id="password_confirmation" placeholder="Retype Password" {{ ($errors->has('password_confirmation')) ? 'class="error"' : '' }}>
                                {{ ($errors->has('password_confirmation') ? '<span for="password" class="error">' . $errors->first('password_confirmation') . '</span>': '') }}
                                <button type="submit" class="btn btn-large btn-success btn-login">Register</button>
                            </form>
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
                        },
                        "password": {
                            required: true
                        },
                        "password_confirmation": {
                            required: true
                        }
                    }
                });
            });
        </script>
@stop
