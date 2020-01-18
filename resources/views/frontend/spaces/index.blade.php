@extends('frontend.layout.app')

@section('header')
<title>{{ config('app.name', 'Laravel') }} : Spaces</title>
@endsection


@section('content')

<body class="f-header">
    
    @include('frontend.layout.partials.navbar')
    
    <main>
        <section class="spacebanner">
            
            <div class="spacebanner__image" style="background-image: url(images/frontend/banner_photo_space.jpg);">
            </div>
            <div class="spacebanner__content">
                <h2>Spaces</h2>
                <p>No clue where to start? Explore the spaces below.</p>
            </div>
        </section>
        
        <section class="spaces">
            <div class="container">
                <div class="row">
                    <div class="col-md-12">
                        <div class="spaces__top">
                            <ul>
                                <li class="active"><a href="#upcoming-space" data-toggle="tab">UPCOMING</a></li>
                                <li><a href="#past-space" data-toggle="tab">PAST</a></li>
                            </ul>
                        </div>
                    </div>
                </div>
                
                <div class="spaces__content">
                    <div class="tab-content" id="app">
                        <upcoming-space country="{{ request()->country }}" 
                                        favourites='@json(Auth::check() ? Auth::user()->favourites->pluck("id", "space_id")->all() : "")'
                            >
                        </upcoming-space>
                        <past-space country="{{ request()->country }}" 
                                    favourites='@json(Auth::check() ? Auth::user()->favourites->pluck("id", "space_id")->all() : "")'
                                    >
                            
                        </past-space>
                    </div>
                </div>
            </div>
        </section>
                
        <section class="subscribe">
            <div class="container">
                <div class="row align-items-center">
                    <div class="col-md-6">
                        <div class="subscribe__image">
                            <img src="{{ asset('images/frontend/sectionbg_1.png') }}" alt="">
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="subscribe__content">
                            <h3>Waiting for the future?</h3>
                            <h4>Pass us your email, and we’ll let you know when future events are here. </h4>
                            <div class="subscribe__content__form">
                                <div class=" form_input"><input type="text" class="form-control" name="email" placeholder="example@email.com"></div>
                                <div class="form_submit"><input type="submit" class="button button__primary button__subscribe" value="Subscribe"></div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <section class="contact">
            <div class="container">
                <div class="row">
                    <div class="col-md-12">
                        <div class="contact__card" style="background-image:url({{ asset('images/frontend/sectionbg_2.jpg') }}">
                            <div class="contact__card__inner">
                                <div class="contact__card__inner__left">
                                    <h3>Looking to host an event?</h3>
                                    <h4>We work with space owners to bring our concepts to their spaces.</h4>
                                </div>
                                <div class="contact__card__inner__right">
                                    <a href="#" class="button button__primary button__contact">Contact us</a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </main>
    
    @include('frontend.layout.partials.footer')

@endsection

@section('page-js')
<script>
    $(document).on('click', '.favourite', function (event) {
        var spaceId = $(this).parent().attr('id'); 
        $.ajax({
            type: "POST",
            data: {"_token": "{{ csrf_token() }}", spaceId: spaceId},
            url: "/web/my-favourites",
            success: function (response) {
                $('#' + spaceId + ' img.unfavourite').removeClass('active');
                $('#' + spaceId + ' img.favourite').addClass('active');
            },
            error: function () {
                window.location.href = '/login';
            }
        });
    });
    
    $(document).on('click', '.unfavourite', function (event) {
        var favourite = $(this).parent().attr('favourite'); 
        var spaceId = $(this).parent().attr('id');
        $.ajax({
            type: "DELETE",
            data: {"_token": "{{ csrf_token() }}", favourite: favourite},
            url: "/web/my-favourites/" + favourite,
            success: function (response) {
                $('#' + spaceId + ' img.unfavourite').addClass('active');
                $('#' + spaceId + ' img.favourite').removeClass('active');
            }
        });
    });
</script>
@endsection