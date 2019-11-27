<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Developer extends Model {
    public function user() {
        return $this->morphOne(User::class, 'user');
    }
}
