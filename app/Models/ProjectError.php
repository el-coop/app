<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ProjectError extends Model {

    protected $casts = [
        'user' => 'object',
        'exception' => 'object',
        'extra_data' => 'object',
    ];

    public function project() {
        return $this->belongsTo(Project::class);
    }
}
