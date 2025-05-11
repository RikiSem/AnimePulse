<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

class AnimeStudio extends Model
{
    protected $fillable = [
        'anime_id',
        'studio_id'
    ];
    public function anime(): HasMany
    {
        return $this->hasMany(Anime::class, 'id', 'anime_id');
    }

    public function studio(): HasMany
    {
        return $this->hasMany(Studio::class, 'id', 'studio_id');
    }
}
