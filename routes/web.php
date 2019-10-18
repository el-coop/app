<?php

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


Route::post('login','Auth\LoginController@login');
Route::get('logout','Auth\LoginController@logout');

Route::middleware(['spa'])->group(function (){
    foreach (\File::allFiles(__DIR__ . "/web") as $routeFile) {
        include $routeFile;
    }
});

Route::get('/{any}', function () {
    return view('spa');
})->where('any', '.*');
