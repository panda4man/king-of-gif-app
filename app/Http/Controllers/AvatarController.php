<?php

namespace App\Http\Controllers;

use Storage;

class AvatarController extends Controller
{
    public function __invoke()
    {
        $avatars = Storage::disk('public')->files('img/mushrooms');
        $avatars = collect($avatars)->transform(function ($a) {
            return Storage::disk('public')->url($a);
        });

        return response()->json(['success' => true, 'data' => $avatars]);
    }
}
