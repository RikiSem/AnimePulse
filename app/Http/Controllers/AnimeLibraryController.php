<?php

namespace App\Http\Controllers;

use App\Http\Classes\Pages;
use App\Http\Classes\Reps\AnimeRep;
use App\Http\Classes\Reps\StudioRep;
use App\Http\Classes\ResponseBodyBuilder;
use App\Interface\EntityRepositoryInterface;
use App\Interface\LibraryInterface;
use App\Models\Anime;
use App\Traits\PrepareFilterList;
use App\Traits\PrepareReleaseYearFilterList;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Inertia\Inertia;

class AnimeLibraryController implements LibraryInterface
{
    use PrepareFilterList;
    protected AnimeRep $animeRep;
    protected StudioRep $studioRep;
    public function __construct(AnimeRep $animeRep, StudioRep $studioRep) {
        $this->animeRep = $animeRep;
        $this->studioRep = $studioRep;
    }
    public function all(Request $request)
    {
        $responseData = $this->animeRep->getAll($request->offset, $request->params);
        $result = $responseData->map(function(Anime $anime) {
            return ResponseBodyBuilder::anime($anime);
        });
        return response($result);
    }

    public function show(Request $request) {
        return Inertia::render('LibraryAnime', [
            'pages' => Pages::$pages,
            'animeStatuses' => Anime::ANIME_STATUSES,
            'statusForUser' => Anime::ANIME_STATUS_FOR_USER,
            'animeTypes' => Anime::ANIME_TYPES_FILTER,
            'userId' => Auth::getUser() != null ? Auth::getUser()->getAuthIdentifier() : 0,
            'params' => $request->params ?? [],
            'animeReleaseYears' => self::prepareList($this->animeRep->getAllUnicReleaseYears()->toArray(), 'Выберите год выхода'),
            'studios' => self::prepareList($this->studioRep->getAllStudioNames()->toArray(), 'Выберите студию'),
        ]);
    }
}
