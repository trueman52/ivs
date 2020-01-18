<?php

namespace App\Rules;

use App\Models\Period;
use Illuminate\Contracts\Validation\Rule;

class ValidPeriodCodeRule implements Rule
{
    private $spaceId;
    private $id;

    /**
     * Create a new rule instance to check the date overlap or not.
     * 
     * @param integer $spaceId
     * @param integer $id
     *
     * @return void
     */
    public function __construct($spaceId, $id)
    {
        $this->spaceId   = $spaceId;
        $this->id        = $id;
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
        return Period::where('id', '<>', $this->id)
                     ->where('space_id', $this->spaceId)
                     ->where('code', $value)
                      ->doesntExist();
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message()
    {
        return 'The :attribute has already been taken.';
    }
}
