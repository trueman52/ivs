<?php

namespace App\UseCases\Customer;

use App\Enums\TagType;
use App\Models\BusinessDetail;
use App\UseCases\Handler;
use Illuminate\Http\Request;

class UpdateBusinessProfile implements Handler
{
    /**
     * @var \App\Models\User
     */
    protected $user;

    /**
     * @var \App\Models\BusinessDetail
     */
    protected $businessDetail;

    /**
     * @var \Illuminate\Http\Request
     */
    protected $request;

    /**
     * Handle the form request or api request.
     *
     * @param \Illuminate\Http\Request $request
     *
     * @return mixed
     */
    public function handle(Request $request)
    {
        $this->user    = $request->user();
        $this->request = $request;

        $this->updateBusinessDetail();
        $this->syncBusinessDetailCharacteristics();
    }

    /**
     * Update business detail characteristics.
     */
    protected function syncBusinessDetailCharacteristics()
    {
        $type = (string)TagType::BUSINESS_CHARACTERISTICS();
        $processed = [];

        foreach ($this->request->tags as $tagId) {
            $processed[] = ['tag_type' => $type, 'tag_id' => $tagId];
        }

        $this->businessDetail
            ->businessCharacteristics()
            ->wherePivot('tag_type', $type)
            ->sync($processed);
    }

    /**
     * Update business details.
     */
    protected function updateBusinessDetail()
    {
        $search = ['user_id' => $this->user->id];
        $socialUrls['website']   = $this->request->website;
        $socialUrls['facebook']  = $this->request->facebook;
        $socialUrls['instagram'] = $this->request->instagram;
        $urls['urls']            = $socialUrls;

        $this->businessDetail = BusinessDetail::updateOrCreate(
            $search,
            array_merge(
                $this->request->only('age', 'revenue', 'teamSize', 'averageTicketSize'),
                $search,
                $urls
            )
        );
    }
}