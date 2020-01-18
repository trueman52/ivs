<?php

namespace App\Http\ViewComposers;

use Illuminate\View\View;
use Illuminate\Support\Facades\Auth;
use App\Helpers\Money;

class SpaceDetailsComposer extends Media
{
    /**
     * Bind data to the view.
     *
     * @param View $view
     *
     * @return void
     */
    public function compose(View $view)
    {
        $this->space = $view->space;

        $this->space->loadMissing('media', 'tags', 'units', 'address');
        $this->view       = $view;
        $favourite        = 0;
        if(Auth::check()) {
            $favourite = Auth::user()->favourites->where('space_id', $this->space->id)->first();
        }
        
        $view->with(array_merge([
            'space'            => $this->space,
            'sliderImages'     => $this->setSliders($this->space, 'banners'),
            'highlights'       => $this->space->highlights,
            'features'         => $this->space->features,
            'units'            => $this->getUnits($this->space),
            'mapUrl'           => config('maps.url') . urlencode($this->space->street . ' ' . $this->space->postal_code . ' ' . $this->space->country),
            'mapAddress'       => $this->space->street . ' ' . $this->space->postal_code . ' ' . $this->space->country,
            'favourite'        => $favourite,
        ]));
    }
    
    /**
     * Get content unit data and return it in a format that can be
     * used by our front-end component.
     *
     * @return array
     */
    public function getUnits($space)
    {
        return $space->units()->publishedOrSoldOut()->get()->transform(function ($unit){
            return [
                'photo'       => asset($unit->getFirstMediaUrl('photo')),
                'price'       => $unit->periods()->first() ? Money::toDollars($unit->periods()->first()->pivot->unit_price) : 'N/A',
                'unit'        =>  $unit,
                'published'   =>  $unit->isPublished(),
            ];
        });
    }
}