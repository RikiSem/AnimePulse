<?php

namespace App\Http\Classes\Reps;

use App\Models\Tag;

class TagRep
{
    public function tagExist(string $tag): bool
    {
        return Tag::where('russian_name', '=', $tag)->exists();
    }
}
