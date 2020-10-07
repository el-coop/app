<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Debt extends Model {
    use HasFactory;

    public function entity() {
        return $this->belongsTo(Entity::class);
    }

    public function project() {
        return $this->belongsTo(Project::class);
    }
}
