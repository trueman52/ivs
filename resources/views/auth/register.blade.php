@extends('frontend.layout.app')

@section('header')
<title>{{ config('app.name', 'Laravel') }} : Sign Up</title>
@endsection

@section('content')

<body>

    <section class="login_wrapper" style="background-image: url({{ asset('images/frontend/login-page_bg.jpg') }})" id="app">
             
        <register logo-image="{{ asset('images/frontend/logo_login.png') }}"
                  >
        </register>
        
    </section

@endsection