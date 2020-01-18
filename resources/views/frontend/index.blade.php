@extends('frontend.layout.app')

@section('header')
<title>{{ config('app.name', 'Laravel') }}</title>
@endsection

@section('content')

<body>
    @include('frontend.layout.partials.navbar')

    <main id="app">

        <home-page-banner banner-url="{{ route('spaces') }}"
                          banner-image="{{ asset('images/frontend/banner_photo_home.jpg') }}"
                          >
        </home-page-banner>

        <home-page-event :top-section='@json($topSection)'
                         :left-section='@json($leftSection)'
                         :right-section='@json($rightSection)'
                         url="{{ route('spaces') }}"
                         >
        </home-page-event>

        <section class="joiningevents">
            <div class="container small-container">
                <div class="row">
                    <div class="col-md-12">
                        <div class="joiningevents__top">
                            <h2>Joining our events?</h2>
                            <p>It’s super simple and easy.</p>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-4">
                        <div class="joiningevents__card">
                            <a href="{{ route('register') }}">
                                <div class="joiningevents__card__image"><img src="{{ asset('images/frontend/icon_photo_1.png') }}" alt=""></div>
                                <div class="joiningevents__card__content">
                                    <h3>Create a profile</h3>
                                    <p>Tell us about you and your business.</p>
                                </div>
                            </a>
                        </div><!--step_single-->
                    </div>
                    <div class="col-md-4">
                        <div class="joiningevents__card">
                            <a href="{{ route('spaces') }}">
                                <div class="joiningevents__card__image"><img src="{{ asset('images/frontend/icon_photo_2.png') }}" alt=""></div>
                                <div class="joiningevents__card__content">
                                    <h3>Book a space</h3>
                                    <p>Browse our various events and book a space to appear in our events.</p>
                                </div>
                            </a>
                        </div><!--step_single-->
                    </div>
                    <div class="col-md-4">
                        <div class="joiningevents__card">
                            <a href="#">
                                <div class="joiningevents__card__image"><img src="{{ asset('images/frontend/icon_photo_3.png') }}" alt=""></div>
                                <div class="joiningevents__card__content">
                                    <h3>Get ready</h3>
                                    <p>We’ll send you a checklist so that you can start working on your space!.</p>
                                </div>
                            </a>
                        </div><!--step_single-->
                    </div>
                </div>
            </div>
        </section>

        <section class="experiences">
            <div class="container small-container">
                <div class="row">
                    <div class="col-md-4">
                        <div class="experiences__top">
                            <h2>Crafting immersive experiences from spaces of potential</h2>
                            <div class="view_btn"><a href="{{ route('spaces') }}" class="btn-link btn-link__secondary"><span>View more</span></a></div>
                        </div>
                    </div>
                    <div class="col-md-8">
                        <div class="experiences__paragraph">
                            <p>We create spaces of potential and make them accessible to people while remaining personal and distinctly human in scale. Through these spaces, we offer opportunity and possibility to better quality of lives.</p>
                        </div>
                    </div>
                </div>
                <div class="row experiences_gap">
                    <div class="col-xs-12 col-sm-5 col-md-5">
                        <div class="experiences__singleimage">
                            <img src="{{ asset('images/frontend/placeholder_photo_1.jpg') }}" alt="">
                        </div>
                    </div>
                    <div class="col-xs-12 col-sm-7 col-md-7">
                        <div class="experiences__thumnimages">
                            <div class="experiences__thumnimages__single">
                                <img src="{{ asset('images/frontend/placeholder_photo_small.jpg') }}" alt="">
                            </div>
                            <div class="experiences__thumnimages__single">
                                <img src="{{ asset('images/frontend/placeholder_photo_small.jpg') }}" alt="">
                            </div>
                            <div class="experiences__thumnimages__single">
                                <img src="{{ asset('images/frontend/placeholder_photo_small.jpg') }}" alt="">
                            </div>
                            <div class="experiences__thumnimages__single">
                                <img src="{{ asset('images/frontend/placeholder_photo_small.jpg') }}" alt="">
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <section class="members">
            <div class="container small-container">
                <div class="row">
                    <div class="col-xs-12">
                        <div class="members__top">
                            <h2>Who we work with</h2>
                            <p>Partnerships are central to the way we do business.</p>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-xs-12">
                        <div class="members__list">
                            <ul>
                                <li><img src="{{ asset('images/frontend/memberbg.jpg') }}" alt=""></li>
                                <li><img src="{{ asset('images/frontend/memberbg.jpg') }}" alt=""></li>
                                <li><img src="{{ asset('images/frontend/memberbg.jpg') }}" alt=""></li>
                                <li><img src="{{ asset('images/frontend/memberbg.jpg') }}" alt=""></li>
                                <li><img src="{{ asset('images/frontend/memberbg.jpg') }}" alt=""></li>

                                <li><img src="{{ asset('images/frontend/memberbg.jpg') }}" alt=""></li>
                                <li><img src="{{ asset('images/frontend/memberbg.jpg') }}" alt=""></li>
                                <li><img src="{{ asset('images/frontend/memberbg.jpg') }}" alt=""></li>
                                <li><img src="{{ asset('images/frontend/memberbg.jpg') }}" alt=""></li>
                                <li><img src="{{ asset('images/frontend/memberbg.jpg') }}" alt=""></li>
                            </ul>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-12">
                        <div class="load_btn text-center"><a href="#" class="button button__primary">Work with us</a></div>
                    </div>
                </div>
            </div>
        </section>

    </main>

@include('frontend.layout.partials.footer')

@endsection

@section('page-js')
<script type="text/javascript">
    $(document).ready(function () {
        var pageBoby = $('body');
        var topBar = 2;
        $(window).on('scroll', function () {
            if ($(this).scrollTop() >= topBar) {
                pageBoby.addClass("f-header");
            } else {
                pageBoby.removeClass("f-header");
            }
        });
    });
</script>
@endsection