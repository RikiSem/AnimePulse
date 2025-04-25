<?php

namespace App\Http\Controllers;

use App\Http\Classes\Pages;
use Illuminate\Http\Request;
use Inertia\Inertia;

class AdminController extends Controller
{
    public function show(Request $request){
        $data = [];
        if (isset($request->page)) {
            switch ($request->page) {
                case 'view':
                    $data = 'view';
                    break;
            }
        } else {
            $data = (new ReviewController())->getOnModeration();
        }
        return Inertia::render('AdminPage', [
            'pages' => Pages::$pages,
            'data' => $data,
        ]);
    }
}
