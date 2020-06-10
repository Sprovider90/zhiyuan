<?php
use Illuminate\Http\Request;

Route::prefix('v1')
    ->namespace('Api')
    ->name('api.v1.')
    ->group(function () {

        Route::middleware('throttle:' . config('api.rate_limits.sign'))
            ->group(function () {
            
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


        
        Route::middleware('throttle:' . config('api.rate_limits.access'))
            ->group(function () {
                // 游客可以访问的接口
                
                Route::middleware('auth:api')->group(function() {
                    // 登录后可以访问的接口


                    // 仓库列表
                    Route::get('storehouses', 'StorehousesController@index')
                        ->name('storehouses.index');
                    // 仓库新增
                    Route::post('storehouses', 'StorehousesController@store')
                        ->name('storehouses.store');
                    // 仓库编辑
                    Route::put('storehouses/{storehouse}', 'StorehousesController@update')
                        ->name('storehouses.update');
                    Route::get('storehouses/{storehouse}/edit', 'StorehousesController@edit')
                        ->name('storehouses.edit');
                    // 仓库删除
                    Route::delete('storehouses/{storehouse}', 'StorehousesController@destroy')
                        ->name('storehouses.destroy');




                });
            });
        });
    });