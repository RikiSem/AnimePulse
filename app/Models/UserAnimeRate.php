<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasOne;

class UserAnimeRate extends Model
{
    protected $guarded = [];

    public function anime(): HasOne
    {
        return $this->hasOne(Anime::class, 'id', 'anime_id');
    }
}
