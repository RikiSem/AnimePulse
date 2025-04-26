<?php


namespace App\Http\Classes\Reps;


use App\Models\UserViews;
use Illuminate\Database\Eloquent\Model;

class UserViewRep
{
    public function addUserViewStatus(int $animeId, int $userId, string $status) {
        $userView = new UserViews();
        $userView->anime_id = $animeId;
        $userView->user_id = $userId;
        $userView->view_status = $status;
        $userView->save();
    }

    public function removeUserViewStatus(int $animeId, int $userId) {
        UserViews::where('anime_id', '=', $animeId)
            ->where('user_id', '=', $userId)
            ->first()
            ->delete();
    }
}
