@extends('frontend.layout.app')

@section('header')
<title>{{ config('app.name', 'Laravel') }} : Login</title>
@endsection

@section('content')

<body>
    <div class="login_wrapper" style="background-image:url({{ URL::to('/') }}/images/frontend/login-page_bg.jpg)" id="app">
        
        <login logo-image="{{ asset('images/frontend/logo_login.png') }}"
               redirect-url="{{ URL::previous() }}"
               >
        </login>
    </div>

@endsection