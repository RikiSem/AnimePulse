<?php

namespace App\Http\Controllers;

use App\Events\NotificationSent;
use App\Http\Classes\Reps\FavoriteRep;
use App\Http\Classes\Reps\UserAnimeListRep;
use App\Http\Classes\Reps\UserViewRep;
use App\Http\Classes\Texts;
use App\Models\Anime;
use Illuminate\Http\Request;

class FavoriteController extends Controller
{
    public function add(Request $request) {
        $currentFavorite = UserAnimeListRep::getOne($request->user_id, $request->entity_id);
        if (!empty($currentFavorite)) {
            if ($currentFavorite->favorite) {
                $currentFavorite->favorite = false;
                NotificationController::sendNotification(
                    $request->user_id,
                    Texts::EVENT_TEXTS['remove_from_favorite']
                );
            } else {
                $currentFavorite->favorite = true;
                NotificationController::sendNotification(
                    $request->user_id,
                    Texts::EVENT_TEXTS['add_to_favorite']
                );
            }
            $currentFavorite->update();
        }
        return response('done');
    }

    public function remove(Request $request) {
        UserAnimeListRep::removeFromFavorite($request->user_id, $request->entity_id);
        return response('done');
    }
}
