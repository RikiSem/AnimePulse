<?php

namespace App\Http\Controllers;

use App\Events\NotificationSent;
use Illuminate\Http\Request;

class NotificationController extends Controller
{
    public static function sendNotification(int $userId, string $text) {
        broadcast(new NotificationSent([
            'id' => $userId,
            'text' => $text,
        ]));
    }
}
