<?php

use App\Models\Transaction;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::post('login', 'Auth\LoginController@login');
Route::post('logout', 'Auth\LoginController@logout');

Route::middleware('auth')->group(function () {
    Route::get('transactions/attachment/{attachment}', 'TransactionController@showAttachment');
    Route::get('database/backup', "DatabaseBackupController@backup");
});

Route::middleware(['spa'])->group(function () {
    foreach (\File::allFiles(__DIR__ . "/web") as $routeFile) {
        include $routeFile;
    }
});

Route::get('{any}', 'HomeController@any')->where('any', '.*');
