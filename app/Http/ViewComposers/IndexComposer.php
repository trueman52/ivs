<?php

namespace App\Http\ViewComposers;

use Illuminate\View\View;
use App\Enums\EventPositionType;
use App\Models\FeaturedSpace;

class IndexComposer
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
        $this->view = $view;
        $view->with(array_merge([
            'topSection'       => $this->getSectionFromContent(EventPositionType::TOP()),
            'leftSection'       => $this->getSectionFromContent(EventPositionType::LEFT()),
            'rightSection'       => $this->getSectionFromContent(EventPositionType::RIGHT()),
        ]));
    }

    /**
     * Get content data and return it in a format that can be
     * used by our front-end component.
     *
     * @param string $position
     * 
     * @return array|null
     */
    protected function getSectionFromContent($position)
    {
        $content = FeaturedSpace::where('data->position', $position)->first();
        $contentSection = NULL;
        if($content) {
            $contentSection['photo'] = asset($content->getFirstMediaUrl('photo'));
            $contentSection['name'] = $content->space->name;
            $contentSection['description'] = $content->description;
            $contentSection['spaceDescription'] = $content->space->description;
            $contentSection['spaceUrl'] = route('spaces.show', $content->space_id);
        }
        return $contentSection;
    }
}