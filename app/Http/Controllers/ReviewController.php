<?php

namespace App\Http\Controllers;

use App\Http\Classes\Pages;
use App\Http\Classes\Reps\ReviewRep;
use App\Http\Classes\ResponseBodyBuilder;
use App\Http\Classes\Texts;
use App\Models\ReviewReaction;
use App\Models\Reviews;
use Illuminate\Http\Request;
use Inertia\Inertia;

class ReviewController extends Controller
{
    public function accept(Request $request) {
        $review = ReviewRep::getOne($request->review_id);
        $review->status = Reviews::MODERATION_SUCCESS;
        $review->update();
        NotificationController::sendNotification($review->author->id, 'Ваша рецензия прошла модерацию');
        return response('done');
    }

    public function reject(Request $request) {
        $review = ReviewRep::getOne($request->review_id);
        $review->reject_reason = $request->reason;
        $review->status = Reviews::MODERATION_FAIL;
        $review->update();
        NotificationController::sendNotification($review->author->id, 'Ваша рецензия не прошла модерацию');
        return response('done');
    }

    public function show(Request $request) {
        $review = ResponseBodyBuilder::review(ReviewRep::getOne($request->id));
        return Inertia::render('ReviewPage',
            [
                'id' => $request->id,
                'pages' => Pages::$pages,
                'review' => $review,
                'rule' => Texts::getRules(),
            ]);
    }

    public function update(Request $request) {
        $review = ReviewRep::getOne($request->id);
        $review->html = $request->html;
        $reviewText = '';
        foreach ($request->text as $text) {
            $reviewText .= $text['insert'];
        }
        $review->text = $reviewText;
        $review->midterm_mark = $request->marks;
        $finalMark = 0;
        foreach ($request->marks as $mark) {
            $finalMark += (int)$mark['mark'];
        }
        $finalMark = ceil($finalMark / count($request->marks));
        $review->final_mark = $finalMark;
        $review->update();
        NotificationController::sendNotification(
            $request->userId,
            Texts::EVENT_TEXTS['review_updated']
        );
        return response('done');
    }

    public function delete(Request $request) {
        ReviewRep::deleteById($request->id);
        return response('done');
    }

    public function getForAnime(Request $request) {
        $responseData = ReviewRep::getForAnime($request->id, $request->offset);
        $result = [];
        /**
         * @var int $key
         * @var Reviews $review
         */
        foreach ($responseData as $key => $review) {
            $result[] = ResponseBodyBuilder::review($review);
        }
        return response($result);
    }

    public function getUserReviews(Request $request) {
        $responseData = ReviewRep::getForUser($request->userId, $request->offset);
        $result = [];
        /**
         * @var int $key
         * @var Reviews $review
         */
        foreach ($responseData as $key => $review) {
            $result[] = ResponseBodyBuilder::review($review);
        }
        return response($result);
    }

    public function create(Request $request) {
        $midtermMarks = json_encode($request->marks);
        $finalMark = 0;
        foreach ($request->marks as $mark) {
            $finalMark += (int)$mark['mark'];
        }
        $finalMark = ceil($finalMark / count($request->marks));
        ReviewRep::createNew($request, $midtermMarks, $finalMark);
        NotificationController::sendNotification(
            $request->userId,
            Texts::EVENT_TEXTS['review_created']
        );
        return response('done');
    }

    public function getOnModeration() {
        $responseData = ReviewRep::getOnModeration();
        $result = [];
        foreach ($responseData as $key => $review) {
            $result[] = ResponseBodyBuilder::review($review);
        }
        return response($result);
    }
}
