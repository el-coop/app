<?php

namespace App\Models;

use App\Models\Traits\Backupable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Transaction extends Model {
    use Backupable;
    use HasFactory;

    public function entity() {
        $this->belongsTo(Entity::class);
    }

    public function attachments() {
        return $this->hasMany(TransactionAttachment::class);
    }
}
