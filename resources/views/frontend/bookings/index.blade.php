@extends('frontend.layout.app')

@section('header')
<title>{{ config('app.name', 'Laravel') }} : Bookings</title>
@endsection

@section('content')

<body class="f-header">
    
    @include('frontend.layout.partials.navbar')
    <main id="app">
        
        @include('frontend.layout.partials.sidebar')
        
        <bookings>
        </bookings>
        
    </main>
    
    @include('frontend.layout.partials.footer')

@endsection