<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

use App\Jobs\UpdateDevicesInfoJob;

class UpdateDevicesInfo extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'zhiyuan:updatedevicesinfo';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = '更新设备信息';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        dispatch(new UpdateDevicesInfoJob(["fromwhere"=>"Commands"]));
        $this->info("ok");
    }
}
