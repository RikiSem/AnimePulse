<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class ReviewReaction extends Model
{
    public const LIKE = 1;
    public const DISLIKE = 0;
    public function review(): HasMany
    {
        return $this->hasMany(Reviews::class, 'id', 'anime_id');
    }
}
