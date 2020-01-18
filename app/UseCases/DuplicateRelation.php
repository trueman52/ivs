<?php

namespace App\UseCases;

use App\UseCases\Handler;
use Illuminate\Http\Request;

class DuplicateRelation implements Handler
{
    /**
     * @var \App\Models\Unit
     */
    protected $unit;
    
    /**
     * @var \App\Models
     */
    protected $relations;
    
    protected $type;
    
    protected $type_id;

    /**
     * Handle the form request or api request.
     *
     * @param \Illuminate\Http\Request $request
     *
     * @return mixed
     */
    public function handle(Request $request)
    {
        $this->unit      = $unit;
        $this->relations = $relations;
        $this->type      = $type;
        $this->type_id   = $type_id;
        $this->relationAttach();
    }

    /**
     * Update relations data
     */
    protected function relationAttach()
    {
        $members = [];
        foreach ($this->relations as $relation) {
            if ($relation) {
                $members[$relation->id] = ['{$this->type_id}' => $relation->id];
            }
        }
        
        $this->unit->{$this->type}()->sync($members);
    }
}