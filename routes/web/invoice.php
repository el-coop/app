<?php

Route::prefix('invoice')->middleware('auth')->group(function () {
    Route::post('/generate', 'InvoiceController@generate');
    Route::post('/smartechEmail', 'InvoiceController@smartechEmail');
});
