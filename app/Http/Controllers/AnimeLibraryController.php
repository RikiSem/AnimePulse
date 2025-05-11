<?php

namespace App\Http\Controllers;

use App\Http\Classes\Pages;
use App\Http\Classes\Reps\AnimeRep;
use App\Http\Classes\Reps\StudioRep;
use App\Http\Classes\ResponseBodyBuilder;
use App\Http\Classes\Texts;
use App\Interface\LibraryInterface;
use App\Models\Anime;
use App\Traits\PrepareFilterList;
use App\Traits\PrepareShowPageBody;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Inertia\Inertia;

class AnimeLibraryController implements LibraryInterface
{
    use PrepareFilterList;
    use PrepareShowPageBody;
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
        return Inertia::render('LibraryAnime',
                self::prepareForFilter(
                $this->animeRep,
                $this->studioRep,
            ));
    }
}
