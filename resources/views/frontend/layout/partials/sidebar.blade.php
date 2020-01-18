<section class="spaces_details_header inner-menu" id="inner_header">
    <div class="container">
        <div class="row">
            <div class="col-md-12">
                <div class="inner-menu__content">
                    <ul>
                        <li @if(in_array(Route::currentRouteName(), ['profile', 'business', 'bank']))class="active" @endif><a href="{{ route('profile') }}">Profile</a></li>
                        <li @if(in_array(Route::currentRouteName(), ['account']))class="active" @endif><a href="{{ route('account') }}">Account</a></li>
                        <li><a href="#">Coupons</a></li>
                        <li @if(in_array(Route::currentRouteName(), ['favourites']))class="active" @endif><a href="{{ route('favourites') }}">Favourites</a></li>
                        <li @if(in_array(Route::currentRouteName(), ['bookings.index']))class="active" @endif><a href="{{ route('bookings.index') }}">Bookings</a></li>
                        <li>
                            <a href="{{ route('logout') }}"
                               onclick="event.preventDefault();
                                              document.getElementById('logout-form').submit();">
                                 Log Out
                            </a>
                            <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                                @csrf
                            </form>
                        </li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</section>