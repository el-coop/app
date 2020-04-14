<?php

namespace App\Models;

use App\Models\Traits\Backupable;
use Illuminate\Database\Eloquent\Model;

class Transaction extends Model {
    use Backupable;

    public function entity() {
        $this->belongsTo(Entity::class);
    }

    public function attachments() {
        return $this->hasMany(TransactionAttachment::class);
    }
}
