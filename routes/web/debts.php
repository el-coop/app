<?php

Route::prefix('debts')->middleware('auth')->group(function () {
    Route::get('/', 'DebtController@index');
});
