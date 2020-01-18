<?php

namespace App\Rules;

use App\Models\Unit;
use App\Enums\UnitStatus;
use Illuminate\Contracts\Validation\Rule;

class ValidUnitPublishRule implements Rule
{
    private $id;

    /**
     * Create a new rule instance to check if the unit is able to publish or not.
     * 
     * @param integer $id
     *
     * @return void
     */
    public function __construct($id = 0)
    {
        $this->id = $id;
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
        // Check Unit Status is published or not
        if($value == UnitStatus::PUBLISHED()) {
            $unit = Unit::find($this->id);
            return $unit->periods()
                        ->where('unit_price', '>', 0)
                        ->where('remaining_quantity', '>', 0)
                        ->exists();
        }
        return true;
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message()
    {
        return 'One or more associated records need proper information to publish this unit.';
    }
}
