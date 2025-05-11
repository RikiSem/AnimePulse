<?php

namespace App\Http\Controllers;

use App\Http\Classes\Pages;
use App\Http\Classes\Reps\AnimeRep;
use App\Http\Classes\ResponseBodyBuilder;
use App\Models\Anime;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Inertia\Inertia;

class StudioController extends Controller
{
    public function get(Request $request, AnimeRep $animeRep)
    {
        $responseData = $animeRep->getAll($request->offset, $request->params);
        $result = $responseData->map(function(Anime $anime) {
            return ResponseBodyBuilder::anime($anime);
        });
        return response($result);
    }

    public function show(Request $request)
    {
        return Inertia::render('AnimeByStudioPage', [
            'pages' => Pages::$pages,
        ]);
    }
}
