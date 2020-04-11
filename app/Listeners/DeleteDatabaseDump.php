<?php

namespace App\Listeners;

use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Storage;

class DeleteDatabaseDump {
    /**
     * Create the event listener.
     *
     * @return void
     */
    public function __construct() {
        //
    }

    public function handle() {
        Storage::delete("backups/mysql-backup.sql");
    }
}
