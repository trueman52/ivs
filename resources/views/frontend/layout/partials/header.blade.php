<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, shrink-to-fit=no">
    <meta name="description" content="">
    <meta name="author" content="">
    
    @yield('header')

    <meta name="csrf-token" content="{{ csrf_token() }}">

    <link href="{{ asset('css/frontend/bootstrap.min.css') }}" rel="stylesheet">
    <link href="{{ asset('css/frontend/owl.carousel.css') }}" rel="stylesheet">
    <link href="{{ asset('css/frontend/chosen.css') }}" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css?family=Catamaran:300,400,500,600,700,800,900&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css?family=Bitter:400,700&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css?family=Signika:300,400,600,700&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css?family=Montserrat:200,300,400,500,600,700,800&display=swap" rel="stylesheet">
    <link href="{{ asset('css/frontend/app.css') }}" rel="stylesheet">

    <!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
    <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
    <!--[if lt IE 9]>
        <script src="https://oss.maxcdn.com/libs/html5shiv/3.7.0/html5shiv.js"></script>
        <script src="https://oss.maxcdn.com/libs/respond.js/1.4.2/respond.min.js"></script>
    <![endif]-->

</head>