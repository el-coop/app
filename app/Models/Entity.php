<?php

namespace App\Models;

use App\Models\Traits\Backupable;
use Illuminate\Database\Eloquent\Model;

class Entity extends Model {
    use Backupable;

    public function transactions() {
        return $this->hasMany(Transaction::class);
    }

    public function projects() {
        return $this->hasMany(Project::class);
    }

    public function debts() {
        return $this->hasMany(Debt::class);
    }
}
