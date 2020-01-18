@extends('frontend.layout.app')

@section('header')
<title>{{ config('app.name', 'Laravel') . ' : ' . $space->name . ' Booking' }}</title>
@endsection

@section('content')

<body class="f-header booking-page">

    <nav class="navbar navbar-expand-lg">
        <div class="container-fluid">
            <div class="logo">
                <a href="{{ route('home') }}">
                    <img class="logo__white" src="{{ URL::to('/') }}/images/frontend/logo-white.svg" alt="Logo">
                    <img class="logo__pink" src="{{ URL::to('/') }}/images/frontend/logo-pink.svg" alt="Logo">
                </a>
            </div>
            <div class="menu-list d-block d-lg-none">
                <ul class="navbar-nav">
                    <li class="active"><a href="">1. Booking</a></li>
                    <li><a href="#">2. Things to Note</a></li>
                    <li><a href="#">3. Check Out &amp; Pay</a></li>
                </ul>

                <ul class="navbar-nav number-list d-none">
                    <li class="active"><a href="">1</a></li>
                    <li><a href="#">2</a></li>
                    <li><a href="#">3</a></li>
                </ul>
            </div>

            <div class="bottom-list d-none">
                <ul class="navbar-nav ml-auto">
                    <li><a href="#"><img src="{{ URL::to('/') }}/images/frontend/left-arrow-4059C1.svg" alt=""> Back to Booking</a></li>

                    <li class="dropdown currency_select">
                        <a href="#" class="dropdown-toggle" data-toggle="dropdown">SGD</a>
                        <ul class="dropdown-menu">
                            <li><a href="#">Singaporean Dollar - SGD</a></li>
                            <li><a href="#">Malaysian Ringgit - MYR</a></li>
                        </ul>
                    </li>

                    <li><a href="#"><img src="{{ URL::to('/') }}/images/frontend/help-4059C1.svg" alt=""/></a></li>
                </ul>
            </div>

            <div class="mobile_menu d-block d-lg-none">
                <a href="#" class="mobile_menu_opener"></a>
            </div>
            
            <div class="collapse navbar-collapse" id="main-nav">
                <ul class="navbar-nav mr-auto logged-in">
                    <li class="active"><a href="">1. Booking</a></li>
                    <li><a href="#">2. Things to Note</a></li>
                    <li><a href="#">3. Check Out &amp; Pay</a></li>
                </ul>

                <ul class="navbar-nav ml-auto">
                    <li><a href="{{ URL::previous() }}"><img src="{{ URL::to('/') }}/images/frontend/left-arrow-4059C1.svg" alt=""></a></li>
                    <li><a href="#"><img src="{{ URL::to('/') }}/images/frontend/help-4059C1.svg" alt=""/></a></li>
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
        <space-booking :space='@json($space)'
                       :unit='@json($space->units->where('id', request()->unitId)->first())'
                       user="{{ auth()->id() }}"
                       redirect-url="{{ route('space.thingsToNote', $space->id) }}"
                       >
        </space-booking>
    </main>

@include('frontend.layout.partials.footer')

@endsection

@section('page-js')
<script>
    $(document).ready(function(){
        $('.booking-page .sticky-content .sticky-left, .booking-page .sticky-content .sticky-right').theiaStickySidebar({
            additionalMarginTop: 149
        });
        $(".option-select").chosen({disable_search_threshold: 10});
    });
</script>
@endsection