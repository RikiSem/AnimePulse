<?php

namespace App\Traits;

use App\Http\Classes\Reps\StudioRep;
use App\Models\Anime;
use Exception;

trait RefactorStudiosInAnimeData
{
    public static function refactorStudio(Anime $anime, array $studios, StudioRep $studioRep, $log)
    {
        array_map(function ($studioOld) use (&$anime, &$studioRep, &$log) {
            $studioOld = reset($studioOld);
            if (!is_numeric($studioOld)) {
                $studio = $studioRep->getByName($studioOld);
                if (!empty($studio)) {
                    $studioRep->createLink($anime->id, $studio->id);
                } else {
                    $newStudio = $studioRep->add($studioOld);
                    $studioRep->createLink($anime->id, $newStudio->id);
                    $log->info($newStudio->id);
                }
            }
        }, $studios);
    }
}
