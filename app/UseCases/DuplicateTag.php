<?php

namespace App\UseCases;

use App\UseCases\Handler;
use Illuminate\Http\Request;
use App\Enums\TagType;

class DuplicateTag implements Handler
{
    /**
     * @var \App\Models\Space
     */
    protected $space;
    
    /**
     * @var \App\Models\Tag
     */
    protected $tags;
    
    /**
     * @var \App\Enums\TagType
     */
    protected $type;
    
    /**
     * @var \App\Enums\TagType
     */
    protected $tagType;

    /**
     * Handle the form request or api request.
     *
     * @param \Illuminate\Http\Request $request
     *
     * @return mixed
     */
    public function handle(Request $request)
    {
        $this->space   = $space;
        $this->tags    = $tags;
        $this->type    = $type;
        $this->tagType = $tagType;
        $this->tagAttach();
    }

    /**
     * Update tags
     */
    protected function tagAttach()
    {
        $members = [];
        foreach ($this->tags as $tag) {
            if ($tag) {
                $members[$tag->id] = ['tag_id' => $tag->id, 'tag_type' => $this->tagType];
            }
        }
        
        $this->space->{$this->type}()->wherePivot('tag_type', $this->tagType)->sync($members);
    }
}