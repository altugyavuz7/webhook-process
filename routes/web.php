<?php

Route::group(['middleware' => 'auth'], function () {
    Route::resource('webhook-process', 'WebhookProcessController')->only(['index', 'destroy']);
    Route::resource('webhook-process-errors', 'WebhookProcessErrorController')->only(['index', 'destroy']);
    Route::post('webhook-error-retry/{id}', 'WebhookProcessErrorController@retryProcess')->name('webhook-error-retry');
});