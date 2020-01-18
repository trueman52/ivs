<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Enums\CustomerStatus;
use App\Models\Role;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use Carbon\Carbon;
use Laravel\Socialite\Facades\Socialite;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Support\Facades\Auth;

class SocialLoginController extends Controller
{
    use AuthenticatesUsers;

    protected $redirectTo = '/';
    
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest');
    }
    
    /**
     * Redirect the user to the OAuth Provider.
     *
     * @return Response
     */
    public function redirectToProvider($provider, Request $request) {
        if ($request->get('redirect')) {
            Session::put('redirect', $request->get('redirect'));
        }
        return Socialite::driver($provider)->redirect();
    }

    /**
     * Obtain the user information from provider.  Check if the user already exists in our
     * database by looking up their provider_id in the database.
     * If the user exists, log them in. Otherwise, create a new user then log them in. After that 
     * redirect them to the authenticated users homepage.
     *
     * @return Response
     */
    public function handleProviderCallback($provider) {
        try {
            $user = Socialite::driver($provider)->user();

            $authUser = $this->authenticate($user, $provider);

            if ($authUser->active()) {
                Auth::login($authUser, true);
                if (Session::get('redirect')) {
                    $redirect = Session::get('redirect');
                    Session::forget('redirect');
                    return redirect()->intended($redirect);
                } else {
                    return redirect($this->redirectTo);
                }
            } else {
                Session::flash('error', 'Your account has been deactivated. Please contact with the system admin.');
                return redirect()->route('login');
            }
        } catch (\Exception $e) {
            Session::flash('error', 'Something went wrong in social login. Please try again.');
            return redirect()->route('login');
        }
    }

    /**
     * If a user has registered before using social auth, return the user
     * else, create a new user object.
     * @param  $user Socialite user object
     * @param $provider Social auth provider
     * @return  User
     */
    public function authenticate($user, $provider) {
        $authUser = User::where('email', $user->email)->first();

        if ($authUser) {
            return $authUser;
        }
        
        $name = explode(' ', $user->name);

        $user = User::create([
            'first_name' => $name[0],
            'last_name' => $name[1],
            'email' => $user->email,
            'email_verified_at' => Carbon::now(),
            'status' => CustomerStatus::ACTIVE()->getValue(),
        ]);
        
        $user->assignRole(Role::CUSTOMER);
        return $user;
    }
    
}
