<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;

class UpdateCustomerAccountRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'old_password' => 'required|old_password:' . Auth::user()->password,
            'password' => 'required|confirmed|min:8|max:255',
        ];
    }
    
    /**
     * Get the validation rules message that apply to the request.
     *
     * @return array
     */
    public function messages() 
    {
        return [
            'old_password.required' => 'Old password is required',
            'old_password.old_password' => 'Old password is wrong',
            'password.required' => 'New Password is required',
            'password.confirmed' => 'New Passwords does not match',
            'password.min' => 'New Password must be at least 8 char long',
            'password.max' => 'New Password can be maximum 255 char long',
        ];
    }
}
