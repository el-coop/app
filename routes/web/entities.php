<?php

Route::prefix('entities')->middleware('auth')->group(function () {
    Route::get('/', 'EntityController@index');
    Route::post('/', 'EntityController@store');
    Route::patch('/{entity}', 'EntityController@update');
});
