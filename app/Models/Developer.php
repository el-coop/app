<?php

namespace App\Models;

use App\Models\Traits\Backupable;
use Illuminate\Database\Eloquent\Model;

class Developer extends Model {
    use Backupable;

    public function user() {
        return $this->morphOne(User::class, 'user');
    }
}
