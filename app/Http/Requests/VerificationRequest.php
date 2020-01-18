<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Carbon\Carbon;

class VerificationRequest extends FormRequest
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
            'code' => 'required|max:6|exists:verification_codes,code',
            'expiresAt' => 'required',
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
            'expiresAt.required' => 'The code has already been expired. Please generate a new code',
        ];
    }
}
