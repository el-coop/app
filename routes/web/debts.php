<?php

Route::prefix('debts')->middleware('auth')->group(function () {
    Route::get('/', 'DebtController@index');
    Route::post('/', 'DebtController@store');
    Route::patch('/{debt}', 'DebtController@update');
    Route::delete('/{debt}', 'DebtController@destroy');
});
