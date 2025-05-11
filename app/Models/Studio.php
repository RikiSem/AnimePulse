<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Studio extends Model
{
    protected $fillable = [
        'name',
        'logo',
        'is_animation_studio',
    ];

    public function animes(): HasMany
    {
        return $this->hasMany(AnimeStudio::class, 'studio_id', 'id');
    }
}
