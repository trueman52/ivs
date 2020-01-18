<?php

namespace App\Observers\Traits;

use App\Models\Model;
use Illuminate\Http\Request;

trait ExcludeAttributesFromInsertion
{
    /**
     * @var \Illuminate\Http\Request
     */
    protected $request;

    public function __construct(Request $request)
    {
        $this->request = $request;
    }

    /**
     * Remove any transient attributes that we do not want
     * when creating / updating a model.
     *
     * @param \App\Models\Model $model
     */
    protected function excludeAttributesFromSaving(Model &$model)
    {
        foreach ($this->excludeFromRequest as $attribute) {
            if (!isset($this->request->$attribute)) continue;

            unset($model->$attribute);
        }
    }
}