<?php

Route::prefix('accounting')->group(function () {
    Route::get('/', 'Programmers\CalculationSheetController@index');
    Route::post('/', 'Programmers\CalculationSheetController@create');
});
