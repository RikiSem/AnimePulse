<?php

namespace App\Http\Controllers;

use App\Http\Classes\Pages;
use App\Http\Classes\Reps\AnimeRep;
use App\Http\Classes\ResponseBodyBuilder;
use App\Models\Anime;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Inertia\Inertia;

class LibraryController extends Controller
{
    public function show(Request $request) {
        return Inertia::render('Library', [
            'pages' => Pages::$pages,
            'animeStatuses' => Anime::ANIME_STATUSES,
            'statusForUser' => Anime::ANIME_STATUS_FOR_USER,
            'userId' => Auth::getUser() != null ? Auth::getUser()->getAuthIdentifier() : 0,
            'params' => $request->params ?? [],
        ]);
    }

    public function all(Request $request) {
        $responseData = AnimeRep::getAll($request->offset, $request->params);
        $result = [];
        foreach ($responseData as $key => $anime) {
            $result[] = ResponseBodyBuilder::anime($anime);
        }

        return response($result);
    }
}
