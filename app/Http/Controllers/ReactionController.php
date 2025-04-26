<?php

namespace App\Http\Controllers;

use App\Http\Classes\Reps\ReactionRep;
use Illuminate\Http\Request;

class ReactionController extends Controller
{
    protected ReactionRep $reactionRep;

    public function __construct(ReactionRep $reactionRep) {
        $this->reactionRep = $reactionRep;
    }
    public function create(Request $request) {

        $userReactionOnReview = $this->reactionRep->getUserReactionByReviewId($request->id, $request->user_id);
        if (is_null($userReactionOnReview)) {
            $this->reactionRep->create($request->id, $request->user_id, $request->type);
        } else {
            $userReactionOnReview->delete();
            if ($userReactionOnReview->type !== $request->type) {
                $this->reactionRep->create($request->id, $request->user_id, $request->type);
            }
        }
        return response('done');
    }
}
