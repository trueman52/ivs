<?php

namespace App\Observers;

use App\Enums\TagType;
use App\Models\Unit;
use App\UseCases\DuplicateTag;
use App\UseCases\DuplicateRelation;

class UnitObserver
{
    /**
     * Handle the user "saved" event.
     *
     * @param \App\Models\Unit $unit
     *
     * @return void
     */
    public function saved(Unit $unit)
    {
        $oldUnits = Unit::where([['space_id', $unit->space_id], ['name', $unit->name], ['code', $unit->code]])
                        ->ordered()
                        ->get();
        if ($oldUnits->count() > 1) {
            $oldUnit = $oldUnits->first();
            
            (new DuplicateTag())->handle($unit, $oldUnit->things()->get(), 'things', TagType::THINGS_TO_NOTE());
            
            (new DuplicateTag())->handle($unit, $oldUnit->tags()->get(), 'tags', TagType::TAG());
            
            (new DuplicateRelation())->handle($unit, $oldUnit->discounts()->get(), 'discounts', 'discount_id');
            
            (new DuplicateRelation())->handle($unit, $oldUnit->addongroups()->get(), 'addOnGroups', 'add_on_group_id');
        }
    }
    
}
