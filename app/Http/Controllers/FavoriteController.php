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
    protected UserAnimeListRep $userAnimeListRep;
    public function __construct(UserAnimeListRep $userAnimeListRep) {
        $this->userAnimeListRep = $userAnimeListRep;
    }
    public function add(Request $request) {
        $currentFavorite = $this->userAnimeListRep->getOne($request->user_id, $request->entity_id);
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
        $this->userAnimeListRep->removeFromFavorite($request->user_id, $request->entity_id);
        return response('done');
    }
}
