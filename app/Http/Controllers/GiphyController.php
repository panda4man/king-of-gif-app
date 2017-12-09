<?php

namespace App\Http\Controllers;

use App\Services\GiphyService;

class GiphyController extends Controller
{
    /**
     * @param GiphyService $service
     * @return \Illuminate\Http\JsonResponse
     */
    public function getRandom(GiphyService $service)
    {
        $giph = $service->random(request()->get('tag'), request()->get('rating'));

        if($giph) {
            return response()->json(['success' => true, 'data' => $giph]);
        }

        return response()->json(['success' => false], 400);
    }

    /**
     * @param GiphyService $service
     * @return \Illuminate\Http\JsonResponse
     */
    public function getSearch(GiphyService $service)
    {
        $giphs = $service->search(request()->get('q'), request()->get('rating'), request()->get('limit'), request()->get('offset'));

        if(!is_null($giphs)) {
            return response()->json(['success' => true, 'data' => $giphs]);
        }

        return response()->json(['success' => false], 400);
    }
}
