<?php

namespace App\Http\ViewComposers;

class Media
{
    /**
     * @var array - The renderable content data from sections.
     */
    protected $content;
    
    /**
     * Set sliders so that other methods can retrieve
     *
     * @param array $content
     * @param string $collectionName
     *
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public function setSliders($content, string $collectionName)
    {
        return $content->media->where('collection_name', $collectionName)->transform(function ($media){
            return [
                'fullUrl' => asset($media->getFullUrl()),
            ];
        });
    }
}