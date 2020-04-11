<?php

namespace App\Http\Controllers;

use App\Services\Database\DatabaseDumperContract;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Spatie\DbDumper\Databases\MySql;
use Storage;

class DatabaseBackupController extends Controller {
    public function backup(DatabaseDumperContract $dumper) {
        $filename = "backups/mysql-backup.sql";

        if (!Storage::exists($filename)) {
            $dumper->dump($filename);
        }

        return Storage::download($filename, "elcoop_db_backup - " . Carbon::now()->format("d-m-Y h:i") . ".sql");
    }
}
