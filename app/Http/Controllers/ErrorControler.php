<?php

namespace App\Http\Controllers;

use App\Http\Classes\Pages;
use App\Http\Classes\Storage;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Blade;
use Inertia\Inertia;

class ErrorControler extends Controller
{

    public const PAGE_404 = '404_page.png';

    public function show404Page(Request $request) {
        return Inertia::render('404Page',[
            'img' => Storage::SYSTEM_IMG_PATH . self::PAGE_404,
            'pages' => Pages::$pages,
        ])
            ->toResponse($request)
            ->setStatusCode(404);
    }
}
