<?php

use Illuminate\Http\Request;

Route::prefix('v1')->namespace('Api')->name('api.v1.')->group(function () {
    
    // 用户注册
    Route::post('users', 'UsersController@store')
        ->name('users.store');
        // 登录
    Route::post('authorizations', 'AuthorizationsController@store')
        ->name('api.authorizations.store');
         // 刷新token
    Route::put('authorizations/current', 'AuthorizationsController@update')
        ->name('authorizations.update');
    // 删除token
    Route::delete('authorizations/current', 'AuthorizationsController@destroy')
        ->name('authorizations.destroy');
});