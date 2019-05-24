<?php

use Illuminate\Support\Facades\Route;

Route::namespace('Voicecode\NavigationManager\Http\Controllers')->group(function () {
    Route::resource('navigation-manager', 'NavigationManagerController');

    Route::prefix('navigation-manager')->group(function () {
        Route::post('{id}/items/add', 'NavigationManagerController@addItem')->where(['id' => '[0-9]+']);
        Route::put('{id}/items/positions', 'NavigationManagerController@updatePositions')->where(['id' => '[0-9]+']);
        Route::put('{id}/items/{itemId}', 'NavigationManagerController@updateItem')->where(['id' => '[0-9]+'])->where(['itemId' => '[0-9]+']);
        Route::delete('{id}/items/{itemId}', 'NavigationManagerController@deleteItem')->where(['id' => '[0-9]+'])->where(['itemId' => '[0-9]+']);
    });
});
