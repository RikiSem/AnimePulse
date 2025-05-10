<?php

namespace App\Http\Controllers;

use App\Http\Classes\Reps\AnimeRep;
use App\Http\Classes\ResponseBodyBuilder;
use App\Models\Anime;
use Illuminate\Http\Request;

class LibraryController extends Controller
{
    protected AnimeRep $animeRep;
    public function __construct(AnimeRep $animeRep) {
        $this->animeRep = $animeRep;
    }


    public function all(Request $request) {
        $responseData = $this->animeRep->getAll($request->offset, $request->params);
        $result = $responseData->map(function(Anime $anime) {
            return ResponseBodyBuilder::anime($anime);
        });
        return response($result);
    }
}
