<?php

use Illuminate\Support\Facades\Route;

Route::namespace('Voicecode\NavigationManager\Http\Controllers')->group(function() {
    
    Route::resource('navigation-manager', 'NavigationController');

    Route::prefix('navigation-manager')->group(function() {
        Route::post('{id}/items/add', 'NavigationController@addItem')->where(['id' => '[0-9]+']);
        Route::put('{id}/items/positions', 'NavigationController@updatePositions')->where(['id' => '[0-9]+']);
        Route::put('{id}/items/{itemId}', 'NavigationController@updateItem')->where(['id' => '[0-9]+'])->where(['itemId' => '[0-9]+']);
        Route::delete('{id}/items/{itemId}', 'NavigationController@deleteItem')->where(['id' => '[0-9]+'])->where(['itemId' => '[0-9]+']);
    });
});