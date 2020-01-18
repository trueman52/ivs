<?php

namespace App\Rules;

use App\Models\Period;
use Illuminate\Contracts\Validation\Rule;

class ValidPeriodDateRule implements Rule
{
    private $spaceId;
    private $to_date;
    private $id;

    /**
     * Create a new rule instance to check the date overlap or not.
     * 
     * @param integer $to_date
     * @param integer $id
     *
     * @return void
     */
    public function __construct($spaceId, $to_date, $id)
    {
        $this->spaceId = $spaceId;
        $this->to_date = $to_date;
        $this->id      = $id;
    }

    /**
     * Determine if the validation rule passes.
     *
     * @param  string  $attribute
     * @param  mixed  $value
     * @return bool
     */
    public function passes($attribute, $value)
    {
        return Period::where('id', '<>', $this->id)
                     ->where('space_id', $this->spaceId)
                     ->where(function ($query) use($value) {
                            $query->where(function ($query) use($value) {
                                  $query->where('from_date', '<=', $value)
                                        ->where('to_date', '>=', $value);
                            })
                                  ->orWhere(function ($query) use($value) {
                                  $query->where('from_date', '<=', $this->to_date)
                                        ->where('to_date', '>=', $this->to_date);
                            })
                                  ->orWhere(function ($query) use($value) {
                                  $query->where('from_date', '>=', $value)
                                        ->where('to_date', '<=', $this->to_date);
                            });
                      })
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
