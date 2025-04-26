<?php

namespace App\Http\Controllers;

use App\Http\Classes\Pages;
use Illuminate\Http\Request;
use Inertia\Inertia;

class AdminController extends Controller
{
 
    protected ReviewController $reviewController;

    public function __construct(ReviewController $reviewController) {
        $this->reviewController = $reviewController;
    }
    public function show(Request $request){
        $data = [];
        if (isset($request->page)) {
            switch ($request->page) {
                case 'view':
                    $data = 'view';
                    break;
            }
        } else {
            $data = $this->reviewController->getOnModeration();
        }
        return Inertia::render('AdminPage', [
            'pages' => Pages::$pages,
            'data' => $data,
        ]);
    }
}
