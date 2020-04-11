<?php


namespace App\Services\Database;


interface DatabaseDumperContract {
    public function dump($filename);
}
