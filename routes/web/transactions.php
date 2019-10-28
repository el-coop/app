<?php

Route::prefix('transactions')->middleware('auth')->group(function () {
    Route::get('/', 'TransactionController@index');
    Route::get('/total', 'TransactionController@total');
    Route::post('/', 'TransactionController@store');
    Route::patch('/{transaction}', 'TransactionController@update');
});
