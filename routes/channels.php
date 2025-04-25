<?php

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Broadcast;

Broadcast::channel('App.Model.User.{id}.events', function ($user, $id) {;
    return Auth::check();
});

Broadcast::channel('test', function () {
    return true;
});
