<?php

namespace App\Models;

use App\Http\Classes\Pages;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\Relations\MorphMany;

class Reviews extends Model
{
    public const ON_MODERATION_STATUS = 'on_moderation';
    public const MODERATION_FAIL = 'moderation_fail';
    public const MODERATION_SUCCESS = 'moderation_success';
    public const DEFAULT_STATUS = self::ON_MODERATION_STATUS;
    public const LIMIT_ON_PAGE = 10;
    public const ENTITY_TYPE =  __CLASS__;

    public const STATUSES = [
        self::ON_MODERATION_STATUS => 'На модерации',
        self::MODERATION_FAIL => 'Модерация не пройдена',
        self::MODERATION_SUCCESS => 'Модерация пройдена'
    ];

    public function anime(): HasOne
    {
        return $this->hasOne(Anime::class, 'id', 'anime_id');
    }

    public function author(): HasOne
    {
        return $this->hasOne(User::class, 'id', 'user_id');
    }

    public function comments(): MorphMany
    {
        return $this->morphMany(Comment::class, 'commentable');
    }

    public function reaction(): HasMany
    {
        return $this->hasMany(ReviewReaction::class, 'review_id', 'id');
    }

}
