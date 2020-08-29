<?php

namespace App\Console;

use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;

class Kernel extends ConsoleKernel
{
    /**
     * The Artisan commands provided by your application.
     *
     * @var array
     */
    protected $commands = [

        //检测项目状态并修改
        \App\Console\Commands\CreateProPic::class,
        \App\Console\Commands\UpdateProStage::class,
        \App\Console\Commands\UpdateDevicesInfo::class

    ];

    /**
     * Define the application's command schedule.
     *
     * @param  \Illuminate\Console\Scheduling\Schedule  $schedule
     * @return void
     */
    protected function schedule(Schedule $schedule)
    {
        // $schedule->command('inspire')
        //          ->hourly();

        //检测项目状态并修改  每天晚上一点执行
        $schedule->command('zhiyuan:updateprostage')
            ->dailyAt('1:00')
            ->timezone('Asia/Shanghai');
        //每十分钟生成系统中项目相关数据快照
        $schedule->command('zhiyuan:createpropic')->timezone('Asia/Shanghai')
            ->everyTenMinutes();
        //更新设备信息至redis  每天晚上一点执行
        $schedule->command('zhiyuan:updatedevicesinfo')
            ->dailyAt('1:00')
            ->timezone('Asia/Shanghai');

    }

    /**
     * Register the commands for the application.
     *
     * @return void
     */
    protected function commands()
    {
        $this->load(__DIR__.'/Commands');

        require base_path('routes/console.php');
    }
}
