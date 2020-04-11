<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use Illuminate\Http\Request;
use Spatie\DbDumper\Databases\MySql;
use Storage;

class DatabaseBackupController extends Controller {
    public function backup() {
        $filename = "backups/mysql-backup.sql";

        if (!Storage::exists($filename)) {
            MySql::create()
                ->setDumpBinaryPath(config('database.connections.mysql.dumper_path'))
                ->setDbName(config('database.connections.mysql.database'))
                ->setUserName(config('database.connections.mysql.username'))
                ->setPassword(config('database.connections.mysql.password'))
                ->doNotCreateTables()
                ->excludeTables('migrations')
                ->addExtraOption('--complete-insert')
                ->dumpToFile(storage_path("app/{$filename}"));
        }

        return Storage::download($filename, "elcoop_db_backup - " . Carbon::now()->format("d-m-Y h:i") . ".sql");
    }
}
