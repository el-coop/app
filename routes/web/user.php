<?php

Route::prefix('user')->middleware('auth')->group(function () {
    Route::get('/', 'UserController@show');
    Route::patch('/scheduledActions', 'UserController@updateScheduledAction');
});
