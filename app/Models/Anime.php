<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\Relations\MorphMany;
use Illuminate\Database\Eloquent\Relations\MorphOne;

class Anime extends Model
{
    public const SYSTEM_ANIME_STATUS_ONGOING = 'ongoing';
    public const SYSTEM_ANIME_STATUS_DONE = 'released';
    public const SYSTEM_ANIME_STATUS_ANNOUNCEMENT = 'anons';
    public const SYSTEM_ANIME_STATUS_CANCELED = 'canceled';
    public const SYSTEM_ANIME_STATUS_STOPPED = 'stopped';

    public const SYSTEM_ANIME_STATUS_FOR_USER_WILL_WATCH = 'will_watch';
    public const SYSTEM_ANIME_STATUS_FOR_USER_WATCH = 'watch';
    public const SYSTEM_ANIME_STATUS_FOR_USER_WATCHED = 'watched';
    public const SYSTEM_ANIME_STATUS_FOR_USER_DROPPED = 'dropped';
    public const SYSTEM_ANIME_STATUS_FOR_USER_FAVORITE = 'favorite';

    public const ANIME_TYPES_SERIAL = 'TV';
    public const ANIME_TYPES_OVA = 'OVA';
    public const ANIME_TYPES_ONA = 'ONA';
    public const ANIME_TYPES_FULL = 'MOVIE';
    public const ANIME_TYPES_PV = 'pv';
    public const ANIME_TYPES_CM = 'cm';
    public const ANIME_TYPES_SPECIAL = 'SPECIAL';
    public const ANIME_TYPES_TV_SHORT = 'TV_SHORT';
    public const ANIME_TYPES_MUSIC = 'MUSIC';
    public const ANIME_TYPES_UNKNOWN = 'UNKNOWN';

    public const UNKNOWN_INFO = 'Не известно';

    public const ANIME_TYPES_FILTER = [
        [
            'title' => self::ANIME_TYPES[self::ANIME_TYPES_FULL],
            'value' => self::ANIME_TYPES_FULL
        ],
        [
            'title' => self::ANIME_TYPES[self::ANIME_TYPES_SERIAL],
            'value' => self::ANIME_TYPES_SERIAL
        ],
        [
            'title' => self::ANIME_TYPES[self::ANIME_TYPES_OVA],
            'value' => self::ANIME_TYPES_OVA
        ],
        [
            'title' => self::ANIME_TYPES[self::ANIME_TYPES_ONA],
            'value' => self::ANIME_TYPES_ONA
        ],
        [
            'title' => self::ANIME_TYPES[self::ANIME_TYPES_SPECIAL],
            'value' => self::ANIME_TYPES_SPECIAL
        ],
        [
            'title' => self::ANIME_TYPES[self::ANIME_TYPES_TV_SHORT],
            'value' => self::ANIME_TYPES_TV_SHORT
        ],
        [
            'title' => self::ANIME_TYPES[self::ANIME_TYPES_MUSIC],
            'value' => self::ANIME_TYPES_MUSIC
        ],
    ];

    public const ANIME_STATUSES = [
        self::SYSTEM_ANIME_STATUS_ONGOING => [
            'title' => 'Онгоинг',
            'value' => self::SYSTEM_ANIME_STATUS_ONGOING
        ],
        self::SYSTEM_ANIME_STATUS_DONE => [
            'title' => 'Вышел',
            'value' => self::SYSTEM_ANIME_STATUS_DONE
        ],
        self::SYSTEM_ANIME_STATUS_ANNOUNCEMENT => [
            'title' => 'Анонсировано',
            'value' => self::SYSTEM_ANIME_STATUS_ANNOUNCEMENT
        ],
    ];

    public const ANIME_STATUS_FOR_USER = [
        self::SYSTEM_ANIME_STATUS_FOR_USER_WILL_WATCH => [
            'title' => 'Буду смотреть',
            'value' => self::SYSTEM_ANIME_STATUS_FOR_USER_WILL_WATCH
        ],
        self::SYSTEM_ANIME_STATUS_FOR_USER_WATCH => [
            'title' => 'Смотрю',
            'value' => self::SYSTEM_ANIME_STATUS_FOR_USER_WATCH
        ],
        self::SYSTEM_ANIME_STATUS_FOR_USER_WATCHED => [
            'title' => 'Просмотрено',
            'value' => self::SYSTEM_ANIME_STATUS_FOR_USER_WATCHED
        ],
        self::SYSTEM_ANIME_STATUS_FOR_USER_DROPPED => [
            'title' => 'Забросил',
            'value' => self::SYSTEM_ANIME_STATUS_FOR_USER_DROPPED
        ],
    ];

    public const ANIME_TYPES = [
        self::ANIME_TYPES_FULL => 'Полнометражный',
        self::ANIME_TYPES_SERIAL => 'Сериал',
        self::ANIME_TYPES_OVA => 'OVA',
        self::ANIME_TYPES_ONA => 'ONA',
        self::ANIME_TYPES_PV => 'PV',
        self::ANIME_TYPES_CM => 'CM',
        self::ANIME_TYPES_SPECIAL => 'Спешал',
        self::ANIME_TYPES_TV_SHORT => 'TV шорт',
        self::ANIME_TYPES_MUSIC => 'Музыкальное',
        self::ANIME_TYPES_UNKNOWN => 'Неизвестно',
    ];

    public const ANIME_FIELDS = [
        'id',
        'name',
        'alter_names',
        'description',
        'tags',
        'season',
        'release_date',
        'type',
        'status',
        'count_series',
        'poster',
        'rate',
        'link_to_watch',
        'created_at',
        'updated_at'
    ];

    protected $fillable = self::ANIME_FIELDS;

    protected $casts = [
        'tags' => 'array',
        'alter_names' => 'array',
    ];

    public const ENTITY_TYPE = __CLASS__;

    public function comments(): MorphMany
    {
        return $this->morphMany(Comment::class, 'commentable');
    }

    public function userList(): HasMany
    {
        return $this->hasMany(UserAnimeList::class, 'anime_id', 'id');
    }

    public function userViews(): HasOne
    {
        return $this->hasOne(UserViews::class, 'anime_id', 'id');
    }

    public function userRate(): HasMany
    {
        return $this->hasMany(UserAnimeRate::class, 'anime_id', 'id');
    }

    public function favorite(): MorphOne
    {
        return $this->morphOne(Favorites::class, 'entity');
    }

    public function views(): MorphMany
    {
        return $this->morphMany(View::class, 'viewable');
    }

    public function studioLink(): HasMany
    {
        return $this->hasMany(AnimeStudio::class, 'anime_id', 'id');
    }
}
