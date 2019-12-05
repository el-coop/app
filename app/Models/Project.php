<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Project extends Model {

    public function entity() {
        return $this->belongsTo(Entity::class);
    }

    public function errors() {
        return $this->hasMany(ProjectError::class);
    }
}
