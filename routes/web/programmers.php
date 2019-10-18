<?php

Route::prefix('accounting')->middleware('auth')->group(function () {
    Route::get('/', 'Programmers\CalculationSheetController@index');
    Route::post('/', 'Programmers\CalculationSheetController@create');
    Route::patch('/{sheet}', 'Programmers\CalculationSheetController@update');
});
