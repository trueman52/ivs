@extends('frontend.layout.app')

@section('header')
<title>{{ config('app.name', 'Laravel') . ' : ' . $space->name . ' Details' }}</title>
@endsection

@section('content')

<body class="f-header">

    @include('frontend.layout.partials.navbar')
    <main id="app">
        <space-details :slider-images='@json($sliderImages)'
                       :units='@json($units)'
                       :features='@json($features)'
                       :highlights='@json($highlights)'
                       :space='@json($space)'
                       map-url="{{ $mapUrl }}"
                       map-address="{{ $mapAddress }}"
                       favourite='@json($favourite)'
                       >
    </space-details>
</main>
    
@include('frontend.layout.partials.footer')

@endsection

@section('page-js')
<script>
    $(document).on('click', '.favourite', function (event) {
        var spaceId = $(this).parent().attr('spaceAttr'); 
        var id = $(this).parent().attr('id'); 
        $.ajax({
            type: "POST",
            data: {"_token": "{{ csrf_token() }}", spaceId: spaceId},
            url: "/web/my-favourites",
            success: function (response) {
                $('#' + id + ' img.unfavourite').removeClass('active');
                $('#' + id + ' img.favourite').addClass('active');
            },
            error: function () {
                window.location.href = '/login';
            }
        });
    });
    
    $(document).on('click', '.unfavourite', function (event) {
        var favourite = $(this).parent().attr('favourite'); 
        var spaceId = $(this).parent().attr('spaceAttr');
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
    $(document).ready(function(){
        $('.spaces_details_header__left ul li a').click(function(){
            $('.spaces_details_header__left ul li').removeClass('active');
            var windowWidth = jQuery(window).width();
            $(this).parent().addClass('active');
            var $anchor = jQuery(this);
            var topOffset = 0;
            if(jQuery('.spaces_details_header').hasClass('fixed')){
                if(windowWidth > 768){
                    topOffset = 188;
                }else{
                    topOffset = 100;
                }

            }else {
                if(windowWidth > 768){
                    topOffset = 281;
                }else{
                    topOffset = 200;
                }

            }
            $('html, body').stop().animate({
                scrollTop: jQuery($anchor.attr('href')).offset().top-topOffset
            }, 500);
            event.preventDefault();
        });
        $('.button__primary.button__book').click(function(){
            var $anchor = jQuery(this);
            $('html, body').stop().animate({
                scrollTop: jQuery($anchor.attr('href')).offset().top-188
            }, 500);
            event.preventDefault();
        });
    });
</script>
@endsection