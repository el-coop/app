<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Entity extends Model {
    public function transactions() {
        return $this->hasMany(Transaction::class);
    }

    public function projects() {
        return $this->hasMany(Project::class);
    }
}
