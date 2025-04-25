<?php


namespace App\Http\Classes\Reps;


use App\Models\ReviewReaction;

class ReactionRep
{
    public static function getUserReactionByReviewId(int $reviewId, int $userId)
    {
        return ReviewReaction::where('review_id', '=', $reviewId)
            ->where('user_id', '=', $userId)
            ->first();
    }
    public static function create(int $reviewId, int $userId, int $type)
    {
        $reaction = new ReviewReaction();
        $reaction->review_id = $reviewId;
        $reaction->user_id = $userId;
        $reaction->type = $type;
        $reaction->save();
    }
}
