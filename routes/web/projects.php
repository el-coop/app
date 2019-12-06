<?php

Route::prefix('projects/{project}')->middleware('auth')->group(function () {
    Route::get('errors', 'ProjectErrorController@index');
    Route::delete('errors/{projectError}', 'ProjectErrorController@destroy');
});
