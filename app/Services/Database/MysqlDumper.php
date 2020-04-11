<?php


namespace App\Services\Database;


use Spatie\DbDumper\Databases\MySql;

class MysqlDumper implements DatabaseDumperContract {
    public function dump($filename) {
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
}
