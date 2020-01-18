@extends('frontend.layout.app')

@section('header')
<title>{{ config('app.name', 'Laravel') }} : Personal Information</title>
@endsection

@section('content')

<body class="f-header">
    
    @include('frontend.layout.partials.navbar')
    <main id="app">
        
        @include('frontend.layout.partials.sidebar')
        
        <profile :countries='@json(Config::get('constants.COUNTRY_CODES'))'
                          >
                 
        </profile>
        
    </main>
    
    @include('frontend.layout.partials.footer')

@endsection
