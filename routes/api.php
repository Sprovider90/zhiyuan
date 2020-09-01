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
            //监测点原始数据-导出
            Route::get('/positiondatas/export', 'PositiondatasController@export')
                ->name('PositiondatasController.export');


        Route::middleware('throttle:' . config('api.rate_limits.access'))
            ->group(function () {
                // 游客可以访问的接口

                Route::middleware('auth:api')->group(function() {
                    // 登录后可以访问的接口
                    // 用户新增
                    Route::resource('users', 'UsersController')->only([
                        'store', 'update', 'index'
                    ]);
                    // 当前登录用户信息
                    Route::get('user/check', 'UsersController@check')
                        ->name('user.check');
                    // 当前登录用户信息
                    Route::get('user', 'UsersController@me')
                        ->name('user.show');

                    //角色列表
                    Route::get('/role', 'RoleController@index')
                    ->name('role.index');
                    //权限列表
                    Route::get('/permissions', 'PermissionsController@index')
                    ->name('permissions.index');
                     // 当前登录用户权限
                    Route::get('userpermissions', 'PermissionsController@userIndex')
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

                    //订单数及金额统计
                    Route::get('projects/count', 'ProjectsController@count')
                        ->name('projects.count');
                    //项目点位检测管理
                    Route::get('projects/{project}/positions','ProjectsController@positions')
                        ->name('projects.positions');
                    Route::get('projects/{project}/area','ProjectsController@area')
                        ->name('projects.area');
                    //项目列表 项目新增  项目编辑 项目更新 仓库删除
                    Route::resource('projects', 'ProjectsController')->only([
                        'index','store', 'edit','update','show'
                    ]);
                    //点位启用停用 1启用 2停用
                    Route::put('position/{position}/status', 'PositionsController@status')
                        ->name('position.status');
                    //点位新增 点位编辑 点位更新 点位删除
                    Route::resource('position', 'PositionsController')->only([
                        'store', 'update'
                    ]);
                    //获取该区域坐标
                    Route::get('projects/{project}/area/{area}/location', 'LocationController@location')
                        ->name('projects.area.location');
                    //区域坐标
                    Route::resource('location', 'LocationController')->only([
                        'update','destroy'
                    ]);


                    //仓库停用 1启用 2停用
                    Route::put('storehouses/{storehouse}/status', 'StorehousesController@status')
                        ->name('storehouses.status');
                    //仓库列表 仓库新增  仓库编辑 仓库更新 仓库删除
                    Route::resource('storehouses', 'StorehousesController')->only([
                        'index','store', 'edit','update','destroy'
                    ]);

                    //客户详情项目列表
                    Route::get('customers/{customer}/projects', 'CustomersController@projects')
                        ->name('customers.projects');
                    //客户列表 客户新增  客户编辑 客户更新 客户详情
                    Route::resource('customers', 'CustomersController')->only([
                        'index','store', 'edit','update','show'
                    ]);

                    //订单数及金额统计
                    Route::get('orders/count', 'OrdersController@count')
                        ->name('orders.count');
                    Route::get('orders/{order}/financeLog', 'OrdersController@financeLog')
                        ->name('orders.financeLog');
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


                    //财务统计
                    Route::get('finance/count','FinanceController@count')
                        ->name('finance.count');
                    //财务列表
                    Route::get('finance','FinanceController@index')
                        ->name('finance.index');
                    //财务查看
                    Route::get('finance/{finance}','FinanceController@show')
                        ->name('finance.show');
                    //财务收退款
                    Route::put('finance/{finance}','FinanceController@update')
                        ->name('financeLog.update');
                    Route::get('finance/{finance}/financeLog', 'FinanceController@financeLog')
                        ->name('finance.financeLog');

                    //设备导入
                    Route::post('device/import','DeviceController@import')
                        ->name('device/import');
                    //设备统计
                    Route::get('device/count','DeviceController@count')
                        ->name('device/count');
                    //收回
                    Route::put('device/{device}/cancel','DeviceController@cancel')
                        ->name('device/cancel');
                    //设备管理
                    Route::resource('device','DeviceController')->only([
                        'index' , 'store' , 'update' , 'show'
                    ]);

                    //订单列表 订单新增  订单编辑 订单更新 订单详情
                    Route::resource('finance', 'FinanceController')->only([
                        'index','update','show'
                    ]);

                    //数据报表
                    Route::resource('data','DataController')->only([
                        'index'
                    ]);


                    //预评估导入
                    Route::post('preinstall/{project}/import','PreinstallController@import')
                        ->name('preinstall/import');
                    //预评估管理
                    Route::resource('preinstall','PreinstallController')->only([
                        'index' , 'show'
                    ]);


                    //公共接口
                    Route::group(['prefix' => 'public'],function () {
                        //文件上传
                        Route::resource('file', 'PublicController')->only([
                            'store'
                        ]);
                        //客户列表
                        Route::get('customers','PublicController@customers')
                            ->name('public.customers');
                        //检测标准列表
                        Route::get('thresholds','PublicController@thresholds')
                            ->name('public.thresholds');
                        //项目对应区域列表
                        Route::get('areas','PublicController@areas')
                            ->name('public.areas');
                        //项目对应设备列表
                        Route::get('devices','PublicController@devices')
                            ->name('public.devices');
                        //获取所有正常未绑定客户的设备列表
                        Route::get('getAllNoCustomerDevicesList','PublicController@getAllNoCustomerDevicesList')
                            ->name('public.getAllNoCustomerDevicesList');
                    });
                    //监测点原始数据
                    Route::get('/positiondatas', 'PositiondatasController@index')
                        ->name('PositiondatasController.index');


                    //故障排查
                    Route::get('breakdown','BreakdownController@index')
                        ->name('breakdown.index');

                    //消息列表
                    Route::get('message','MessageController@index')
                        ->name('message.index');
                    //消息删除
                    Route::delete('message/{message}','MessageController@destroy')
                        ->name('message.destroy');
                    //消息未读总条数
                    Route::get('message/noreadcount','MessageController@noread')
                        ->name('message.noreadcount');
                    // 数据字典
                    Route::resource('dictories', 'DictoriesController')->only([
                        'update', 'index'
                    ]);
                    // 标准阈值
                    Route::resource('thresholds', 'ThresholdsController')->only([
                        'store', 'update', 'index','show'
                    ]);

                    // 设置项目的阈值
                    Route::post('projects/{project}/thresholds', 'ProjectsThresholdsController@store')
                        ->name('projects.thresholds.store');
                    // 修改项目阶段阈值
                    Route::patch('projects/{project}/thresholds/{projectsthreshold}', 'ProjectsThresholdsController@update')
                        ->name('projects.thresholds.update');

                    // 设置项目的预警条件
                    Route::post('projects/{project}/waringsetting', 'ProjectsWaringSettingController@store')
                        ->name('projects.waringsetting.store');
                    // 修改项目的预警条件
                    Route::patch('projects/{project}/waringsetting/{waringsetting}', 'ProjectsWaringSettingController@update')
                        ->name('projects.waringsetting.update');

                    //预警警报列表
                    Route::get('warnigs','WarnigsController@index')
                        ->name('warnigs.index');
                    //预警警报详情
                    Route::get('warnigs/{warnig}','WarnigsController@show')
                        ->name('warnigs.show');

                    // 预警警报用户消息列表
                    Route::resource('warnigssms', 'WarnigsSmsController')->only([
                        'index'
                    ]);
                    // 发送预警警报的消息
                    Route::post('warnigs/{warnigs}/warnigssms', 'WarnigsSmsController@store')
                        ->name('warnigs.warnigssms.store');
                    // 修改预警警报消息
                    Route::patch('warnigs/{warnigs}/warnigssms/{warnigssms}', 'WarnigsSmsController@update')
                        ->name('warnigs.warnigssms.update');



                });
            });
        });
    });
