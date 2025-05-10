<?php

namespace App\Http\Classes\Reps;

use App\Models\Studio;

class StudioRep
{
    public function add(string $name, string|null $logo, bool $isAnimationStudio): Studio
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
}
