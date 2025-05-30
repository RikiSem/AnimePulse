<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphTo;

class Favorites extends Model
{
    public function entity(): MorphTo
    {
        return $this->morphTo();
    }
}
