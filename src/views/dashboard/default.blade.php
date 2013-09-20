@extends('photon::layouts.dashboard')

{{-- Title --}}
@section('title')
<title>{{ Config::get('photon::photon.title') }} - Dashboard</title>
@stop

{{-- Content --}}
@section('content')

@stop

{{-- Javascript --}}
@section('javascript')
        <script>
            $(window).load(function() {
                // $(".uniformCheckbox").uniform();
            });
        </script>
@stop
