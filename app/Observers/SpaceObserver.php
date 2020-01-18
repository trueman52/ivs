<?php

namespace App\Observers;

use App\Enums\TagType;
use App\Models\Space;
use App\UseCases\DuplicateTag;

class SpaceObserver
{
    /**
     * Handle the user "saved" event.
     *
     * @param \App\Models\Space $space
     *
     * @return void
     */
    public function saved(Space $space)
    {
        $oldSpaces = Space::where([['name', $space->name], ['code', $space->code]])
                          ->ordered()
                          ->get();
        if ($oldSpaces->count() > 1) {
            $oldSpace             = $oldSpaces->first();
            $input                = [];
            $input['street']      = $oldSpace->street;
            $input['postal_code'] = $oldSpace->postal_code;
            $input['country']     = $oldSpace->country;
            $space->address()->updateOrCreate([
                'addressable_type' => 'App\Models\Space',
                'addressable_id'   => $space->id,
            ], $input);
            
            (new DuplicateTag())->handle($space, $oldSpace->tags()->get(), 'tags', TagType::TAG());
            
            (new DuplicateTag())->handle($space, $oldSpace->features()->get(), 'features', TagType::NOTABLE_FEATURE());
        }
    }

}
