<?php

namespace App\Models;

use App\Models\Traits\Backupable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProjectError extends Model {
    use Backupable;
    use HasFactory;

    protected $casts = [
        'user' => 'object',
        'exception' => 'object',
        'extra_data' => 'object',
    ];

    public function project() {
        return $this->belongsTo(Project::class);
    }
}
