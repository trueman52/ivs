<?php

namespace App\UseCases\Customer;

use App\Models\User;
use App\Models\Profile;
use App\Models\Role;
use App\Models\VerificationCode;
use App\UseCases\Handler;
use Illuminate\Http\Request;
use Illuminate\Support\Arr;
use Carbon\Carbon;
use App\Notifications\VerifyUserEmail;
use Illuminate\Support\Facades\Crypt;

class RegisterUserProfile implements Handler
{
    /**
     * @var \App\Models\User
     */
    protected $user;
    
    /**
     * @var \Illuminate\Http\Request
     */
    protected $request;

    /**
     * Handle the form request or api request.
     *
     * @param \Illuminate\Http\Request $request
     *
     * @return mixed
     */
    public function handle(Request $request)
    {
        $this->user    = $request->user();
        $this->request = $request;

        $this->createUser();
        $this->createProfile();
        $this->createVerification();
    }

    /**
     * Create customer's profile.
     */
    protected function createProfile()
    {
        $search                   = ['user_id' => $this->user->id];
        $contactNumber['code']    = $this->request->code;
        $contactNumber['number']  = $this->request->number;
        $contact['contactNumber'] = $contactNumber;

        $this->profile = Profile::updateOrCreate(
            $search,
            array_merge($search, $contact)
        );
    }

    /**
     * Create user model.
     */
    protected function createUser()
    {
        $search = ['email' => $this->request->email];
        $password['password'] = bcrypt($this->request->password);
        
        $this->user = User::updateOrCreate(
            $search,
            array_merge($search, $password, Arr::only($this->request->all(), [
                'firstName',
                'lastName',
            ]))
        );
        
        $this->user->assignRole(Role::CUSTOMER);
    }
    
    /**
     * Create verification model.
     */
    protected function createVerification()
    {
        $search                 = ['user_id' => $this->user->id];
        $code                   = mt_rand(100000, 999999);
        $data                   = [];
        $data['code']           = $code;
        $data['expiresAt']      = Carbon::now()->addMinutes(30);
        $this->verificationCode = VerificationCode::updateOrCreate(
            $search,
            array_merge($search, $data)
        );
        $token = Crypt::encryptString($this->verificationCode->id);
        $data = [
          'name' => $this->user->name,
          'email' => $this->user->email,
          'code' => $code,
          'token' => $token,
        ];
        $notification = new VerifyUserEmail($data);
        
        $this->user->notify($notification);
        
    }
}