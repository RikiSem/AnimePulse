<?php

namespace App\Http\Classes\Reps;

use App\Models\User;

class UserRep
{
    public function getOne(int $id): User
    {
        return User::find($id);
    }
}
