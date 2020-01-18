@extends('frontend.layout.app')

@section('header')
<title>{{ config('app.name', 'Laravel') }} : Business Information</title>
@endsection

@section('content')

<body class="f-header info_bar">
    <div class="info-box d-none d-lg-block">
        <div class="container">
            <div class="row">
                <div class="col-md-12">
                    <div class="info-box__content">
                        <div class="info-box__content__left">
                            <p>Complete your profile and get SGD 10 discount for your next booking </p>
                        </div>
                        <div class="info-box__content__right">
                            <a href="#">Yes, let’s do it!</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <a href="#" class="info-box__cancel"><img src="{{ asset('images/frontend/cancel_bg.png') }}" alt=""></a>
    </div>
    
    @include('frontend.layout.partials.navbar')
    <main id="app">
        
        @include('frontend.layout.partials.sidebar')
        
        <business-information :age-sizes='@json(\App\Enums\BusinessAgeSize::toArray())'
                              :team-sizes='@json(\App\Enums\BusinessTeamSize::toArray())'
                              :ticket-sizes='@json(\App\Enums\BusinessTicketSize::toArray())'
                              :revenue-sizes='@json(\App\Enums\BusinessRevenueSize::toArray())'
                              :characteristics='@json(\App\Models\Tag::where('admin_only', 0)->where('type', \App\Enums\TagType::BUSINESS_CHARACTERISTICS())->get()->toArray())'
                              :tags='@json(auth()->user()->business ? auth()->user()->business->businessCharacteristics()->pluck('tag_id')->all() : [])'
                              >
        </business-information>
        
    </main>
    
    @include('frontend.layout.partials.footer')

@endsection