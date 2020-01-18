<?php

namespace App\Rules;

use App\Models\Space;
use App\Enums\SpaceStatus;
use Illuminate\Contracts\Validation\Rule;

class ValidSpacePublishRule implements Rule
{
    private $id;

    /**
     * Create a new rule instance to check if the space is able to publish or not.
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
        // Check Space Status is published or not
        if($value == SpaceStatus::PUBLISHED()) {
            $space = Space::find($this->id);
            return $space->periods()
                         ->exists() 
                    && 
                    $space->units()
                          ->published()
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
        return 'One or more associated records need proper information to publish this space.';
    }
}
