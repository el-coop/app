<?php


namespace App\Models\Traits;

use App\Events\DatabaseUpdated;

trait Backupable {
    protected static function bootBackupable() {
        static::saved(function () {
            event(new DatabaseUpdated);
        });
    }
}
