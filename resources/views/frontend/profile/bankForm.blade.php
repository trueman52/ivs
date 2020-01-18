@extends('frontend.layout.app')

@section('header')
<title>{{ config('app.name', 'Laravel') }} : Bank Account</title>
@endsection

@section('content')

<body class="f-header">    
    @include('frontend.layout.partials.navbar')
    <main id="app">
        
        @include('frontend.layout.partials.sidebar')
        
        <bank-account-form>
        </bank-account-form>
        
    </main>
    
    @include('frontend.layout.partials.footer')

@endsection