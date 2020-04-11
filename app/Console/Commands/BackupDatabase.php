<?php

namespace App\Console\Commands;

use App\Mail\DatabaseBackupEmail;
use Carbon\Carbon;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Mail;
use Spatie\DbDumper\Databases\MySql;
use Storage;

class BackupDatabase extends Command {
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'db:backup {email}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Backs up the database and mails it to given email';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct() {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     * @throws \Spatie\DbDumper\Exceptions\CannotSetParameter
     */
    public function handle() {
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

        $date = Carbon::now()->format("d-m-Y h:i");

        Mail::raw("Backup for {$date} is attached.", function ($message) use ($filename, $date) {
            $message->to($this->argument('email'))
                ->subject("El-Coop Database Backup for {$date}")
                ->attach(storage_path("app/{$filename}"), [
                    'as' => "elcoop_db_{$date}.sql"
                ]);
        });

    }
}
