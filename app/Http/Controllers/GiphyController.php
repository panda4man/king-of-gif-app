<?php

namespace App\Http\Controllers;

use App\Services\GiphyService;

class GiphyController extends Controller
{
    public function getRandom(GiphyService $service)
    {
        $giph = $service->random(request()->get('tag'), request()->get('rating'));

        if($giph) {
            return response()->json(['success' => true, 'data' => $giph]);
        }

        return response()->json(['success' => false], 400);
    }
}
