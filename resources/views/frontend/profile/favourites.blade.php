@extends('frontend.layout.app')

@section('header')
<title>{{ config('app.name', 'Laravel') }} : Favourites</title>
@endsection

@section('content')

<body class="f-header">
    
    @include('frontend.layout.partials.navbar')
    <main id="app">
        
        @include('frontend.layout.partials.sidebar')
        
        @if(Auth::user()->favourites->count())
        <favourites>
        </favourites>
        @else 
        <empty-favourite>
        </empty-favourite>
        @endif
        
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