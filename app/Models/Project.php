<?php

namespace App\Models;

use App\Models\Traits\Backupable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Project extends Model {
    use Backupable;
    use HasFactory;

    public function entity() {
        return $this->belongsTo(Entity::class);
    }

    public function errors() {
        return $this->hasMany(ProjectError::class);
    }
}
