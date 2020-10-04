<?php

Route::prefix('projects')->middleware('auth')->group(function () {
    Route::get('/', 'ProjectController@index');

    Route::prefix('{project}/errors')->group(function () {
        Route::get('/', 'ProjectErrorController@index');
        Route::delete('{projectError}', 'ProjectErrorController@destroy');
    });
});
