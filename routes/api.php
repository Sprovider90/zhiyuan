<?php
use Illuminate\Http\Request;

Route::prefix('v1')
    ->namespace('Api')
    ->name('api.v1.')
    ->group(function () {

        Route::middleware('throttle:' . config('api.rate_limits.sign'))
            ->group(function () {
            
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
                    // 用户新增
                    Route::resource('users', 'UsersController')->only([
                        'store', 'update', 'index'
                    ]);
                    //角色列表
                    Route::get('/role', 'RoleController@index')
                    ->name('role.index');
                    //权限列表
                    Route::get('/permissions', 'PermissionsController@index')
                    ->name('permissions.index');
                     // 当前登录用户权限
                    Route::get('user', 'PermissionsController@userIndex')
                        ->name('user.permissions');
                    //角色的权限列表
                    Route::get('/role/{role}/permissions', 'PermissionsController@roleIndex')
                    ->name('role.permissions');

                    Route::resource('role', 'RoleController')->only([
                        'store', 'update'
                    ]);

                    //登录日志列表
                    Route::get('/usersloginlog', 'UsersloginlogController@index')
                    ->name('UsersloginlogController.index');

                    //项目列表 项目新增  项目编辑 项目更新 仓库删除
                    Route::resource('projects', 'ProjectsController')->only([
                        'index','store', 'edit','update','show'
                    ]);

                    //仓库列表 仓库新增  仓库编辑 仓库更新 仓库删除
                    Route::resource('storehouses', 'StorehousesController')->only([
                        'index','store', 'edit','update','destroy'
                    ]);

                    //客户列表 客户新增  客户编辑 客户更新 客户详情
                    Route::resource('customers', 'CustomersController')->only([
                        'index','store', 'edit','update','show'
                    ]);

                    //订单数及金额统计
                    Route::get('orders/count', 'OrdersController@count')
                        ->name('orders.count');
                    //订单列表 订单新增  订单编辑 订单更新 订单详情
                    Route::resource('orders', 'OrdersController')->only([
                        'index','store', 'edit','update','show'
                    ]);
                    //订单发货
                    Route::post('orders/{order}/send', 'OrdersController@send')
                        ->name('orders.send');
                    //订单取消
                    Route::put('orders/{order}/cancel', 'OrdersController@cancel')
                        ->name('orders.cancel');




                    //公共接口
                    Route::group(['prefix' => 'public'],function () {
                        //文件上传
                        Route::resource('file', 'PublicController')->only([
                            'store'
                        ]);
                        //客户列表
                        Route::get('customers','PublicController@customers')
                            ->name('public.customers');
                    });








                });
            });
        });
    });