<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class AddOnRequest extends FormRequest
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
        $rules = array_merge_recursive(self::maxValidatorRule($this));
        return $rules;
    }
    
    /**
     * Get the validation rules to check the max value is greater than the min value.
     *
     * @param  \Illuminate\Foundation\Http\FormRequest $request
     * @param                                          $rule
     * 
     * @return array
     */
    public static function maxValidatorRule($request, $rule = null) {
        $rules = [
            'max' => 'min:' . $request->min,
        ];
                            
        if(!empty($rule)) {
            if(isset($rules[$rule])) {
                return $rules[$rule];
            }
            return '';
        }
        return $rules;
    }
    
}
