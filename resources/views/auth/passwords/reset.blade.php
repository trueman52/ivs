@extends('frontend.layout.app')

@section('header')
<title>{{ config('app.name', 'Laravel') }} : Reset Password</title>
@endsection

@section('content')

<body class="f-header">
    
    @include('frontend.layout.partials.navbar')
    
    <main id="app">
        <reset-password token="{{ $token }}">
        </reset-password>
    </main>
    
    @include('frontend.layout.partials.footer')
@endsection