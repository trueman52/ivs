@extends('frontend.layout.app')

@section('header')
<title>{{ config('app.name', 'Laravel') }} : Forget Password</title>
@endsection

@section('content')

<body class="f-header">
    
    @include('frontend.layout.partials.navbar')
    
    <section class="login_wrapper login_wrapper__forgotpass" style="background-image:url({{ asset('images/frontend/login-page_bg.png') }})" id="app">
        <forget-password-checkemail url="{{ route('home') }}">
        </forget-password-checkemail>
    </section>
@endsection