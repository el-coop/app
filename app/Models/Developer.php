<?php

namespace App\Models;

use App\Models\Traits\Backupable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Developer extends Model {
    use Backupable;
    use HasFactory;

    public function user() {
        return $this->morphOne(User::class, 'user');
    }
}
