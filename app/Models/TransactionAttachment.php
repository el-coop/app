<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TransactionAttachment extends Model {

    protected static function boot() {
        parent::boot();
        static::deleted(function ($attachment) {
            \Storage::delete("transactionAttachments/{$attachment->path}");
        });
    }

    public function transaction() {
        return $this->belongsTo(Transaction::class);
    }
}
