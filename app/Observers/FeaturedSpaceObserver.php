<?php

namespace App\Observers;

use App\Models\FeaturedSpace;

class FeaturedSpaceObserver
{
    /**
     * Handle the user "creating" event.
     *
     * @param \App\Models\FeaturedSpace $featuredSpace
     *
     * @return void
     */
    public function creating(FeaturedSpace $featuredSpace)
    {
        //Update space_id
        $spaces    = json_decode(request()->spaces, true);
        $featuredSpace->space_id = $spaces[0];
    }
    
    /**
     * Handle the user "saving" event.
     *
     * @param \App\Models\FeaturedSpace $featuredSpace
     *
     * @return void
     */
    public function saving(FeaturedSpace $featuredSpace)
    {
        //Update space_id
        $spaces    = json_decode(request()->spaces, true);
        $featuredSpace->space_id = $spaces[0];
    }

}
