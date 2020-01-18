<?php

namespace App\Http\Controllers\Frontend\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreUserFavourite;
use App\Models\Favourite;
use Exception;
use Illuminate\Support\Facades\Auth;

class UserFavouriteController extends Controller
{
    /**
     * Remove the specified resource from storage.
     *
     * @param \App\Models\Favourite $favourite
     *
     * @return \Illuminate\Http\Response
     * @throws \Exception
     */
    public function destroy(Favourite $favourite)
    {
        $favourite->delete();
        return response()->json('removed from favourites');
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return response()->json([
            'favourites' => Favourite::where('user_id', Auth::id())->whereHas('space')->get(),
        ]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param \App\Http\Requests\StoreUserFavourite $request
     *
     * @return \Illuminate\Http\Response
     */
    public function store(StoreUserFavourite $request)
    {
        try {
            $request->user()->favourites()->create([
                'space_id' => $request->spaceId,
            ]);
        }
        catch (Exception $e) {
            return response()->json($e->getMessage(), 500);
        }

        return response()->json('saved to favourites');
    }
}
