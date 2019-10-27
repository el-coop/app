<?php

Route::prefix('transactions')->middleware('auth')->group(function () {
    Route::get('/', 'TransactionController@index');
    Route::post('/', 'TransactionController@store');
//    Route::patch('/{transaction}', 'TransactionsController@update');
});
