<?php


namespace App\Http\Classes\Reps;


use App\Models\ReviewReaction;
use App\Models\Reviews;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;

class ReviewRep
{

    public static function getReviewCount(): int
    {
        return Reviews::all()->count();
    }

    public static function deleteById(int $id) {
        Reviews::find($id)->delete();
    }

    public static function getForAnime(int $id, int $offset = 0)
    {
        return Reviews::where('anime_id', '=', $id)
            ->where('reviews.status', '=', Reviews::MODERATION_SUCCESS)
            ->limit(Reviews::LIMIT_ON_PAGE)
            ->offset($offset * Reviews::LIMIT_ON_PAGE)
            ->get();
    }

    public static function getForLastDays(int $days)
    {
        return Reviews::where('created_at', '>', Carbon::now()->subDays($days))
            ->where('reviews.status', '=', Reviews::MODERATION_SUCCESS)
            ->get();
    }

    public static function getMostPopular()
    {
        return Reviews::select('reviews.*')
            ->leftJoin('review_reactions', 'review_reactions.review_id', '=', 'reviews.id')
            ->where('type', '=', ReviewReaction::LIKE)
            ->where('reviews.status', '=', Reviews::MODERATION_SUCCESS)
            ->groupBy('reviews.id')
            ->get();
    }

    public static function getForUser(int $userId, int $offset = 0)
    {
        return Reviews::where('user_id', '=', $userId)
            ->limit(Reviews::LIMIT_ON_PAGE)
            ->offset($offset * Reviews::LIMIT_ON_PAGE)
            ->get();
    }

    public static function getOne(int $id): Reviews
    {
        return Reviews::find($id);
    }

    public static function getOnModeration() {
        return Reviews::where('status', '=', Reviews::ON_MODERATION_STATUS)
            ->get();
    }

    public static function createNew(Request $data, string $midtermMarks, int $finalMark)
    {
        $review = new Reviews();
        $review->anime_id = $data->animeId;
        $review->user_id = $data->userId;
        $review->html = $data->html;
        $reviewText = '';
        foreach ($data->text as $text) {
            $reviewText .= $text['insert'];
        }
        $review->text = $reviewText;
        $review->midterm_mark = $midtermMarks;
        $review->final_mark = $finalMark;
        $review->save();
    }
}
