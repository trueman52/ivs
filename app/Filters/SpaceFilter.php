<?php

namespace App\Filters;

use Carbon\Carbon;

class SpaceFilter extends Filter
{
    /**
     * {@inheritdoc}
     */
    protected $filterables = ['upcoming', 'past', 'country'];

    /**
     * Only include past spaces.
     */
    public function past()
    {
        $this->builder->where('booking_closing_at', '<=', Carbon::now());
    }

    /**
     * Only include upcoming spaces.
     */
    public function upcoming()
    {
        $this->builder->where('booking_closing_at', '>=', Carbon::now());
    }
    
    /**
     * Only include specific country spaces.
     */
    public function country()
    {
        $this->builder->whereHas('address', function($query) {
            $query->where('country', request()->country);
        });
    }
}