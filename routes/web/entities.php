<?php

Route::prefix('entities')->middleware('auth')->group(function () {
    Route::get('/', 'EntityController@index');
//    Route::post('/', 'TransactionController@store');
//    Route::patch('/{transaction}', 'TransactionController@update');
//    Route::delete('/{transaction}', 'TransactionController@destroy');
});
