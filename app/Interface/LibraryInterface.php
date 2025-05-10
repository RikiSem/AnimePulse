<?php

namespace App\Interface;

use Illuminate\Http\Request;

interface LibraryInterface
{
    public function show(Request $request);
    public function all(Request $request);
}
