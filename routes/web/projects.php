<?php

Route::prefix('projects/{project}')->middleware('auth')->group(function () {
    Route::prefix('errors')->group(function () {
        Route::get('/', 'ProjectErrorController@index');
        Route::delete('{projectError}', 'ProjectErrorController@destroy');
    });
});
