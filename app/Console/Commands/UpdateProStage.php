<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Jobs\UpdateProStage as UpdateProStageJob;
class UpdateProStage extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'zhiyuan:updateprostage';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = '修改项目阶段';

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
        dispatch(new UpdateProStageJob(["fromwhere"=>"Commands"]));
        $this->info("ok");
    }

}
