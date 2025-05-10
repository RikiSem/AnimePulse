<?php

namespace App\Http\Controllers;

use App\Http\Classes\Pages;
use App\Http\Classes\ResponseBodyBuilder;
use App\Interface\LibraryInterface;
use App\Models\Anime;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Inertia\Inertia;

class MangaLibraryController implements LibraryInterface
{
    public function all(Request $request)
    {
        $responseData = $this->animeRep->getAll($request->offset, $request->params);
        $result = $responseData->map(function(Anime $anime) {
            return ResponseBodyBuilder::anime($anime);
        });
        return response($result);
    }

    public function show(Request $request) {
        return Inertia::render('LibraryManga', [
            'pages' => Pages::$pages,
            'animeStatuses' => Anime::ANIME_STATUSES,
            'statusForUser' => Anime::ANIME_STATUS_FOR_USER,
            'userId' => Auth::getUser() != null ? Auth::getUser()->getAuthIdentifier() : 0,
            'params' => $request->params ?? [],
        ]);
    }
}
