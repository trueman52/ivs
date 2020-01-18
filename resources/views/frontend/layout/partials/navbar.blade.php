<nav class="navbar navbar-expand-lg @if(in_array(Route::currentRouteName(), ['account', 'bank', 'business', 'bankForm', 'profile', 'favourites', 'bookings'])) no-shadow @endif"> 
    <div class="container-fluid">
        <div class="logo">
            <a href="{{ route('home') }}">
                <img class="logo__white" src="{{ URL::to('/') }}/images/frontend/logo-white.svg" alt="Logo">
                <img class="logo__pink" src="{{ URL::to('/') }}/images/frontend/logo-pink.svg" alt="Logo">
            </a>
        </div>
        <button class="navbar-toggler collapsed" type="button" data-toggle="collapse" data-target="#main-nav" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="main-nav">
            <ul class="navbar-nav mr-auto">
                <li @if(in_array(Route::currentRouteName(), ['spaces', 'spaces.show']))class="active" @endif><a href="{{ route('spaces') }}">Spaces</a></li>
                <li><a href="#">Work with us</a></li>
            </ul>

            <ul class="navbar-nav ml-auto">
                <li><a href="#"><img src="{{ URL::to('/') }}/images/frontend/help-4059C1.svg" alt=""/></a></li>
                <li class="dropdown currency_select">
                    <a href="#" class="dropdown-toggle" data-toggle="dropdown">SGD</a>
                    <ul class="dropdown-menu">
                        <li><a href="#">Singaporean Dollar - SGD</a></li>
                        <li><a href="#">Malaysian Ringgit - MYR</a></li>
                    </ul>
                </li>
                @if(Auth::check())
                <li class="dropdown my_Account">
                    <a href="#" class="dropdown-toggle" data-toggle="dropdown">{{ Auth::user()->first_name . ' ' . Auth::user()->last_name }}<span class="caret"></span></a>
                    <ul class="dropdown-menu">
                        <li><a href="{{ route('profile') }}">Profile</a></li>
                        <li><a href="{{ route('account') }}">Account</a></li>
                        <li><a href="#">Coupons</a></li>
                        <li><a href="{{ route('favourites') }}">Favourites</a></li>
                        <li><a href="{{ route('bookings.index') }}">Bookings</a></li>
                        <li>
                            <a href="{{ route('logout') }}"
                               onclick="event.preventDefault();
                                              document.getElementById('logout-form-profile').submit();">
                                 Log Out
                            </a>

                            <form id="logout-form-profile" action="{{ route('logout') }}" method="POST" style="display: none;">
                                @csrf
                            </form>
                        </li>
                    </ul>
                </li>
                @else 
                <li><a href="{{ route('register', ['redirect'=> Request::path() ]) }}">Sign up</a></li>
                <li><a href="{{ route('login', ['redirect'=> Request::path() ]) }}">Log in</a></li>
                @endif
            </ul>
        </div>
    </div>
</nav>

<section class="mobile-menu">
    <div class="mobile-menu__top">
        <div class="logo">
            <a href="index.html">
                <img src="{{ URL::to('/') }}/images/frontend/logo-white.svg" alt="Logo">
            </a>
        </div>
        <div class="menu_close">
            <a href="javascript:void(0)"></a>
        </div>
    </div>

    <div class="mobile-menu__menu">
        <ul class="navbar-nav mr-auto">
            <li @if(in_array(Route::currentRouteName(), ['spaces', 'spaces.show']))class="active" @endif><a href="{{ route('spaces') }}">Spaces</a></li>
            <li><a href="#">Work with us</a></li>
            <li><a href="#">FAQ</a></li>
            <li class="dropdown currency_select">
                <a href="#" class="dropdown-toggle">SGD</a>
                <ul class="dropdown-menu">
                    <li><a href="#">Singaporean Dollar - SGD</a></li>
                    <li><a href="#">Malaysian Ringgit - MYR</a></li>
                </ul>
            </li>
        </ul>
        @if(Auth::check())
        <ul class="navbar-nav mr-auto">
            <li><a href="{{ route('profile') }}">Profile</a></li>
            <li><a href="{{ route('account') }}">Account</a></li>
            <li><a href="#">Coupons</a></li>
            <li><a href="{{ route('favourites') }}">Favourites</a></li>
            <li><a href="#">Bookings</a></li>
            <li>
                <a href="{{ route('logout') }}"
                   onclick="event.preventDefault();
                                  document.getElementById('logout-form-profile-mobile').submit();">
                     Log Out
                </a>

                <form id="logout-form-profile-mobile" action="{{ route('logout') }}" method="POST" style="display: none;">
                    @csrf
                </form>
            </li>
        </ul>
        @else 
        <ul class="navbar-nav mr-auto">
            <li><a href="{{ route('register', ['redirect'=> Request::path() ]) }}">Sign up</a></li>
            <li><a href="{{ route('login', ['redirect'=> Request::path() ]) }}">Log in</a></li>
        </ul>
        @endif
    </div>
</section>