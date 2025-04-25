<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\MorphTo;

class Comment extends Model
{
    public const COMMENTS_ENTITIES_TYPES = [
        'review' => Reviews::ENTITY_TYPE,
        'anime' => Anime::ENTITY_TYPE,
    ];
    public const LIMIT_ON_PAGE = 30;

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }

    public function replays(): HasMany
    {
        return $this->hasMany(Comment::class,'reply_to', 'id');
    }

    public function replaysFrom(): HasMany
    {
        return $this->HasMany(Comment::class,'id', 'reply_to');
    }

    public function commentable(): MorphTo
    {
        return $this->morphTo();
    }
}
