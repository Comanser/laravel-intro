<?php

Route::get('/', [
    'uses' => 'NiceActionController@getHome',
    'as' => 'home'
]);

Route::group(['prefix' => 'do'], function() {
    
    Route::get('/{action}/{name?}', [
        'uses' => 'NiceActionController@getNiceAction',
        'as' => 'niceaction'
    ]);

    Route::post('/add_action', [
        'uses' => 'NiceActionController@postInsertNiceAction',
        'as' => 'add_action'
    ]);
    
});
