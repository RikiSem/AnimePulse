<?php


namespace App\Http\Classes;

use App\Http\Classes\Reps\StudioRep;
use App\Models\Anime;
use App\Models\AnimeStudio;
use App\Models\Comment;
use App\Models\Favorites;
use App\Models\Manga;
use App\Models\ReviewReaction;
use App\Models\Reviews;
use App\Models\User;
use App\Models\UserAnimeList;
use App\Models\UserViews;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class ResponseBodyBuilder
{
    public static function manga(Manga $manga): array
    {
        return [

        ];
    }
    public static function review(Reviews $review): array
    {
        return [
            'id' => $review->id,
            'text' => $review->text,
            'html' => $review->html,
            'anime' => [
                'id' => $review->anime->id,
                'name' => $review->anime->name,
            ],
            'midterm_mark' => json_decode($review->midterm_mark, true),
            'final_mark' => $review->final_mark,
            'reactions' => $review->reaction,
            'author' => [
                'id' => $review->author->id,
                'name' => $review->author->name,
                'avatar' =>  Storage::url('imgs/avatars/' . ($review->author->avatar ?: User::DEFAULT_AVATAR)),
            ],
            'like_count' => $review->reaction->where('type', '=', ReviewReaction::LIKE)->count(),
            'dislike_count' => $review->reaction->where('type', '=', ReviewReaction::DISLIKE)->count(),
            'comments_count' => $review->comments->count(),
            'status' => [
                'readable' => Reviews::STATUSES[$review->status],
                'system' => $review->status
            ],
            'reject_reason' => $review->reject_reason,
            'created_at' => $review->created_at,
            'updated_at' => $review->updated_at,
        ];
    }

    public static function userAnimeList(UserAnimeList $animeList, int $userId = 0): array
    {
        $result = self::anime($animeList->anime, $userId);
        $result['statusForUser'] = Anime::ANIME_STATUS_FOR_USER[$animeList->view_status] ?? null;
        $result['current_user']['user_favorite'] = $animeList->favorite;
        return $result;
    }

    public static function userViews(UserViews $view): array
    {
        $result = self::anime($view->anime);
        $result['statusForUser'] = Anime::ANIME_STATUS_FOR_USER[$view->view_status] ?? null;
        return $result;
    }

    public static function comment(Comment $comment): array
    {
        $result = [
            'id' => $comment->id,
            'comment' => $comment->comment,
            'created_at' => $comment->created_at,
            'updated_at' => $comment->updated_at,
            'user' => self::user($comment->user),
            'replays_from' => $comment->replaysFrom->first(),
        ];
        if (!empty($comment->replays)) {
            $replays = [];
            foreach ($comment->replays as $replay) {
                $replays[] = self::comment($replay);
            }
            $result['replays'] = $replays;
        }
        switch ($comment->commentable_type) {
            case Anime::ENTITY_TYPE:
                $result['entity'] = [
                    'type' => 'anime',
                    'data' => $comment->commentable,
                ];
                break;
            case Reviews::ENTITY_TYPE:
                $result['entity'] = [
                    'type' => 'review',
                    'data' => $comment->commentable,
                ];
                break;
        }
        return $result;
    }

    public static function user(User $user): array
    {
        return [
            'id' => $user->id,
            'name' => $user->name,
            'email' => $user->email,
            'avatar' => Storage::url('imgs/avatars/' . ($user->avatar ?: User::DEFAULT_AVATAR)),
            'isAdmin' => $user->isAdmin,
            'created_at' => $user->created_at,
            'updated_at' => $user->updated_at,
        ];
    }

    public static function anime(Anime $anime, int $userId = 0): array
    {
        $result = [
            'id' => $anime->id,
            'external_id' => $anime->external_id,
            'name' => empty($anime->name) ? json_decode($anime->alter_names)[0] : $anime->name,
            'alter_names' => array_filter(array_map(function ($animeName) {
                $type = gettype($animeName);
                $result = '';
                if ($type === 'string') {
                    $result = $animeName;
                } else if ($type === 'array') {
                    if (isset($animeName[0]) && $animeName[0] !== null && !empty($animeName[0])) {
                        $result = $animeName[0];
                    }
                }
                return $result;
            },$anime->alter_names)),
            'description' => $anime->description,
            'tags' => !empty($anime->tags) ? $anime->tags : null,
            'rate' => round($anime->userRate->select('user_rate')->sum('user_rate') / ($anime->userRate->count() === 0 ? 1 : $anime->userRate->count()), 2),
            'count_series' => $anime->count_series,
            'status' => Anime::ANIME_STATUSES[$anime->status]['title'],
            'studio' => $anime->studioLink->map(function ($link) {
                return $link->studio->map(function ($studio) {
                    return [
                        'name' => $studio->name,
                        'id' => $studio->id
                    ];
                });
            }),
            'type' => in_array($anime->type, array_keys(Anime::ANIME_TYPES)) ? Anime::ANIME_TYPES[$anime->type] : Anime::ANIME_TYPES[Anime::ANIME_TYPES_UNKNOWN],
            'poster' => Storage::url('imgs/posters/' . $anime->poster),
            'season'=> sprintf( '%s %s',
                 Season::getSeason(explode('-' ,$anime->season)[0] ?? '0'),
                $anime->release_year
            ),
            'other_rates' => $anime->other_rates,
            'link_to-watch' => $anime->link_to,
            'release_date' => Carbon::parse(sprintf(
                '%s.%s.%s', 
                $anime->release_day ?? 0, 
                $anime->release_month ?? 0, 
                $anime->release_year ?? 0, 
            ))->format('d.m.Y'),
            'release_day' => $anime->release_day,
            'release_month' => $anime->release_month,
            'release_year' => $anime->release_year,
            'in_current_season' => $anime->in_current_season,
            'current_user' => [
                'user' => 0,
                'user_rate' => 0,
                'user_view' => null,
            ]
        ];
        if ($userId > 0) {
            $result['current_user']['user'] = $userId;
            $result['current_user']['user_rate'] = $anime->userRate->where('user_id', '=', $userId)->first()->user_rate ?? 0;
            $result['current_user']['user_view'] = $anime->userList->where('user_id', '=', $userId)->first()->view_status ?? null;

            $result['current_user']['user_favorite'] = null;
            if (!is_null($anime->userList->where('user_id', '=', $userId)->first())) {
                $result['current_user']['user_favorite'] = $anime->userList->where('user_id', '=', $userId)->first()->favorite;
            }
        }

        return $result;
    }
}
