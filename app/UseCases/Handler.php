<?php

namespace App\UseCases;

use Illuminate\Http\Request;

interface Handler
{
    /**
     * Handle the form request or api request.
     *
     * @param \Illuminate\Http\Request $request
     *
     * @return mixed
     */
    public function handle(Request $request);
}