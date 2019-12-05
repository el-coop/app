<?php

Route::prefix('projects/{project}')->middleware('auth')->group(function () {
    Route::get('errors', 'ProjectErrorController@index');
});
