<?php

namespace Tests\Feature;

use App\Services\Database\DatabaseDumperContract;
use Carbon\Carbon;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Facades\Storage;
use Spatie\DbDumper\Databases\MySql;
use Tests\TestCase;
use Tests\Traits\CreatesUsers;

class DatabaseBackupTest extends TestCase {

    use CreatesUsers;

    public function test_guest_cant_download_database_backup() {

        $this->get(action('DatabaseBackupController@backup'))->assertRedirect('/');
    }

    public function test_downloads_already_generated_database_file() {

        $this->mock(DatabaseDumperContract::class, function ($mock) {
            $mock->shouldNotReceive('dump');
        });

        $developer = $this->getDeveloper();
        $time = Carbon::now()->format("d-m-Y h:i");

        Storage::fake();
        Storage::put("backups/mysql-backup.sql", 'content');

        $this->actingAs($developer)->get(action('DatabaseBackupController@backup'))->assertHeader('content-disposition', "attachment; filename=\"elcoop_db_backup - {$time}.sql\"");
    }

    public function test_downloads_generates_and_downloads_database_file() {
        Storage::fake();

        $this->mock(DatabaseDumperContract::class, function ($mock) {
            $mock->shouldReceive('dump')->once()->andReturnUsing(function () {
                Storage::put("backups/mysql-backup.sql", 'content');
            });
        });

        $developer = $this->getDeveloper();
        $time = Carbon::now()->format("d-m-Y h:i");


        $this->actingAs($developer)->get(action('DatabaseBackupController@backup'))->assertHeader('content-disposition', "attachment; filename=\"elcoop_db_backup - {$time}.sql\"");
    }

    public function () {

    }
}
