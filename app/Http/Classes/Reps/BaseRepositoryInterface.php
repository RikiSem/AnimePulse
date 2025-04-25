<?php

namespace App\Http\Classes\Reps;

use Illuminate\Database\Eloquent\Collection;

interface BaseRepositoryInterface
{
    public static function getOne(int $id);
    public static function getAll(): Collection;
}
