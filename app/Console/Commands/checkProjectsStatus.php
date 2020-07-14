<?php

namespace App\Console\Commands;

use App\Models\Projects;
use App\Models\ProjectsStages;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;

class checkProjectsStatus extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'command:checkProjectsStatus';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = '检测项目状态并更新项目状态';

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

        //查询第一条的开始时间 和最后一条的结束时间
        $data = Projects::where('status','<',6)->get();
        foreach ($data as $k => $v){
            $status     = 1;//1未开始 2施工中 3交付中 4维护中 5暂停 6已结束
            $stage_id   = 0;
            $stages     = $stages1 = ProjectsStages::where('project_id',$v->id)->orderBy('id','asc');
            $count      = $stages->count();
            $stages     = $stages->get();
            $start      =  strtotime($stages[0]->start_date);
            $end        =  strtotime($stages[$count-1]->end_date);
            $time       =  strtotime(date('Y-m-d'));
            if( $start < $time && $time < $end ){
                $date  = date('Y-m-d');
                $stage =  $stages1->where('start_date','<',$date)->where('end_date','>',$date)->first();
                if($stage){
                    //根据stage显示状态
                    //1施工阶段 2交付阶段 3维护阶段
                    switch ($stage->stage){
                        case 1:
                            $status = 2;
                            $stage_id = $stage->id;
                            break;
                        case 2:
                            $status = 3;
                            $stage_id = $stage->id;
                            break;
                        case 3:
                            $status = 4;
                            $stage_id = $stage->id;
                            break;
                    }
                }else{
                    //暂停
                    $status = 5;
                    $stage_id = 0;
                }
            }else{
                if($end < $time ){
                    //已结束状态
                    $status = 6;
                    $stage_id = 0;
                }
            }
            if($status > 1){
                $flg = Projects::where('id',$v->id)->update(['status' => $status,'stage_id' => $stage_id]);
                if(!$flg){
                    Log::info('项目状态更新失败！');
                }
            }
        }
    }
}
