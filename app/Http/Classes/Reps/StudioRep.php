<?php

namespace App\Http\Classes\Reps;

use App\Models\AnimeStudio;
use App\Models\Studio;
use Illuminate\Database\Eloquent\Collection;

class StudioRep
{
    public function add(string $name, string|null $logo = null, bool $isAnimationStudio = true): Studio
    {
        $studio = new Studio();
        $studio->name = $name;
        $studio->logo = $logo;
        $studio->is_animation_studio = $isAnimationStudio;
        $studio->save();
        return $studio;
    }
    public function getByName(string $name): Studio|null
    {
        return Studio::where('name', '=', $name)
            ->first();
    }

    public function getById(int $id): Studio|null
    {
        return Studio::find($id);
    }
    public function getAllStudioNames()
    {
        return Studio::select('name')
            ->distinct()
            ->orderBy('name', 'asc')
            ->pluck('name');
    }

    public function createLink(int $animeId, int $studioId): AnimeStudio
    {
        return AnimeStudio::firstOrCreate([
            'anime_id' => $animeId,
            'studio_id' => $studioId
        ]);
    }
}
