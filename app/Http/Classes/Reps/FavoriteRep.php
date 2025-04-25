<?php


namespace App\Http\Classes\Reps;


use App\Models\Anime;
use App\Models\Favorites;

class FavoriteRep
{
    public const ENTITY_TYPES = [
        'anime' => Anime::ENTITY_TYPE
    ];
    public static function getOne(string $entityType, int $entityId, int $userId): Favorites | null
    {
        return Favorites::where('entity_type', '=', self::ENTITY_TYPES[$entityType])
            ->where('entity_id', '=', $entityId)
            ->where('user_id', '=', $userId)
            ->first();
    }

    public static function add(string $entityType, int $entityId, int $userId) {
        $favorite = new Favorites();
        $favorite->entity_type = self::ENTITY_TYPES[$entityType];
        $favorite->entity_id = $entityId;
        $favorite->user_id = $userId;
        $favorite->save();
    }

    public static function remove(int $entityId, int $userId) {
        Favorites::where('entity_id', '=', $entityId)
            ->where('user_id', '=', $userId)
            ->first()
            ->delete();
    }

    public static function getUserFavourites(int $userId) {
        return Favorites::where('user_id', '=',$userId)
            ->get();
    }
}
