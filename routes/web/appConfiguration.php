<?php

Route::prefix('app-configuration')->middleware('auth')->group(function() {

    Route::get('/', 'AppConfigurationController@index');
    Route::get('/{key}', 'AppConfigurationController@get');

    Route::post('/', 'AppConfigurationController@store');
    Route::patch('/{appConfiguration}', 'AppConfigurationController@update');

    Route::delete('/{appConfiguration}', 'AppConfigurationController@destroy');

});
