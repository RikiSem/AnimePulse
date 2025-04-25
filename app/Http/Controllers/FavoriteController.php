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
        if (!empty($currentFavorite) && $currentFavorite->favorite) {
            $this->remove($request);
            NotificationController::sendNotification(
                $request->user_id,
                Texts::EVENT_TEXTS['remove_from_favorite']
            );
        } else {
            UserAnimeListRep::setFavorite($request->user_id, $request->entity_id);
            NotificationController::sendNotification(
                $request->user_id,
                Texts::EVENT_TEXTS['add_to_favorite']
            );
        }
        return response('done');
    }

    public function remove(Request $request) {
        UserAnimeListRep::removeFromFavorite($request->user_id, $request->entity_id);
        return response('done');
    }
}
