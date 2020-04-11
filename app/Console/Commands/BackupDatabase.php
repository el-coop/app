<?php

namespace App\Console\Commands;

use App\Mail\DatabaseBackupEmail;
use App\Services\Database\DatabaseDumperContract;
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
     * @var DatabaseDumperContract
     */
    protected DatabaseDumperContract $dumper;

    /**
     * Create a new command instance.
     *
     * @param DatabaseDumperContract $dumper
     */
    public function __construct(DatabaseDumperContract $dumper) {
        parent::__construct();
        $this->dumper = $dumper;
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
            $this->dumper->dump($filename);
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
