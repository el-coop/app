<?php

Route::prefix('entities')->middleware('auth')->group(function () {
    Route::get('/', 'EntityController@index');
    Route::post('/', 'EntityController@store');
    Route::patch('/{entity}', 'EntityController@update');
    Route::delete('/{entity}', 'EntityController@destroy');


    Route::prefix('{entity}/projects')->group(function () {
        Route::post('/', 'EntityProjectController@store');
        Route::patch('/{project}', 'EntityProjectController@update');
        Route::delete('/{project}', 'EntityProjectController@destroy');
    });
});
