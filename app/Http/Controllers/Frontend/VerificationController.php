<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\VerificationCode;
use App\Enums\CustomerStatus;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Crypt;
use Carbon\Carbon;
use App\Notifications\VerifyUserEmail;
use App\Http\Requests\VerificationRequest;
use Illuminate\Foundation\Auth\VerifiesEmails;

class VerificationController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Email Verification Controller
    |--------------------------------------------------------------------------
    |
    | This controller is responsible for handling email verification for any
    | user that recently registered with the application. Emails may also
    | be re-sent if the user didn't receive the original email message.
    |
    */

    use VerifiesEmails;

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
     * Where to redirect users after verification.
     *
     * @var string
     */
    protected $redirectTo = '/home';
    
    /**
     * Show the application verification form.
     *
     * @param $token

     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function show($token)
    {
        $id = Crypt::decryptString($token);
        $verificationCode = VerificationCode::find($id);
        return view('auth.verify', compact('verificationCode', 'token'));
    }
    
    /**
     * Handle a registration request for the application.
     *
     * @param  \App\Http\Requests\VerificationRequest  $request
     * 
     * @return \Illuminate\Http\Response
     */
    public function verify(VerificationRequest $request)
    {
        $token = $request->get('token');
        $id = Crypt::decryptString($token);
        $verificationCode = VerificationCode::where([['id', $id], ['code', $request->get('code')]])
                                            ->firstOrFail();
        $verificationCode->user->update([
           'email_verified_at' => Carbon::now(),
           'status' => CustomerStatus::ACTIVE()->getValue(),
        ]);

        Auth::login($verificationCode->user, true);
        return response()->json('Email Verified');
    }
    
    /**
     * Handle a resend request for the application.
     *
     * @param  \Illuminate\Http\Request $request
     * @param                           $token
     * 
     * @return \Illuminate\Http\Response
     */
    public function resend(Request $request, $token)
    {
        $id = Crypt::decryptString($token);
        $verificationCode = VerificationCode::find($id);
        $code = mt_rand(100000, 999999);
        $verificationCode->update([
            'code' => $code,
            'expires_at' => Carbon::now()->addMinutes(30),
        ]);
        $token = Crypt::encryptString($verificationCode->id);
        $data = [
          'name' => $verificationCode->user->name,
          'email' => $verificationCode->user->email,
          'code' => $code,
          'token' => $token,
        ];
        $notification = new VerifyUserEmail($data);
        $verificationCode->user->notify($notification);
        return response()->json('Resend verification Link');
    }
    
}
