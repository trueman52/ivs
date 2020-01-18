<?php

namespace App\Rules;

use Illuminate\Contracts\Validation\Rule;

class MaxNumbersRule implements Rule
{
    private $max;

    /**
     * Create a new rule instance to check max number.
     * 
     * @param integer $max
     *
     * @return void
     */
    public function __construct($max = 1)
    {
        $this->max = $max;
    }

    /**
     * Determine if the validation rule passes.
     *
     * @param  string  $attribute
     * @param  mixed  $value
     * 
     * @return bool
     */
    public function passes($attribute, $value)
    {
        return $value <= $this->max;
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message()
    {
        return 'The :attribute cannot be greater than '. $this->max;
    }
}
