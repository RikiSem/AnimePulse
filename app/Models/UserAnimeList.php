<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasOne;

class UserAnimeList extends Model
{
    protected $fillable = [
        'user_id',
        'anime_id',
        'view_status',
        'favorite'
    ];
    public function anime(): HasOne
    {
        return $this->hasOne(Anime::class, 'id', 'anime_id');
    }
}
