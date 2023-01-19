<?php

Route::prefix('entities/{entity}/notes')->middleware('auth')->group(function() {

    Route::get('/', 'NoteController@index');
    Route::post('/', 'NoteController@store');
    Route::patch('/{note}', 'NoteController@update');
    Route::delete('/{note}', 'NoteController@destroy');

});
