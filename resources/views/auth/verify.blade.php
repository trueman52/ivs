@extends('frontend.layout.app')

@section('header')
<title>{{ config('app.name', 'Laravel') }} : Verify Email</title>
@endsection

@section('content')

<body class="f-header">
    
    <nav class="navbar navbar-expand-lg">
        <div class="container-fluid">
            <div class="logo">
                <a href="{{ route('home') }}">
                    <img class="logo__white" src="images/logo-white.svg" alt="Logo">
                    <img class="logo__pink" src="images/logo-pink.svg" alt="Logo">
                </a>
            </div>
            <button class="navbar-toggler collapsed" type="button" data-toggle="collapse" data-target="#main-nav" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="main-nav">
                <ul class="navbar-nav mr-auto logged-in">
                    @php
                    $unit = Session::get('unit');
                    $space = $unit ? $unit->space : null;
                    @endphp
                    @if($space)
                    <li><a href="{{ route('space.booking', $space->id) }}">1. Booking</a></li>
                    <li><a href="{{ route('space.thingsToNote', $space->id) }}">2. Things to Note</a></li>
                    @else 
                    <li><a href="#">1. Booking</a></li>
                    <li><a href="#">2. Things to Note</a></li>
                    @endif
                    <li class="active"><a href="">3. Check Out &amp; Pay</a></li>
                </ul>

                <ul class="navbar-nav ml-auto">
                    <li><a href="#"><img src="images/left-arrow-4059C1.svg" alt=""></a></li>
                    <li><a href="#"><img src="images/help-4059C1.svg" alt=""/></a></li>
                    <li class="dropdown currency_select">
                        <a href="#" class="dropdown-toggle" data-toggle="dropdown">SGD</a>
                        <ul class="dropdown-menu">
                            <li><a href="#">Singaporean Dollar - SGD</a></li>
                            <li><a href="#">Malaysian Ringgit - MYR</a></li>
                        </ul>
                    </li>
                </ul>
            </div>
        </div>
    </nav>
    
    <main id="app">
        <verify-email token="{{ $token }}"
                      url="{{ route('email.resend', $token) }}"
                      expires-at="{{ Carbon\Carbon::parse($verificationCode->expires_at)->gte(Carbon\Carbon::now()) }}"
                      >
        </verify-email>
    </main>
    
    @include('frontend.layout.partials.footer')
    
@endsection
