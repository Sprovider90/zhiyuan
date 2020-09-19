<?php

namespace App\Console\Commands;

use App\Models\ProThresholdsLog;
use App\Services\CommonUtils;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
class CreateProThresholdsLog extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'zhiyuan:createprothresholdslog';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = '生成项目阈值关系的历史记录';

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
        $db_data=$this->getKzData();
        if(!empty($db_data)){
            foreach ($db_data as $k =>$v){
                $where=[];
                $where['project_id']=$v->project_id;
                $where['stage_id']=$v->stage_id;
                $where['thresholdinfo']=$v->thresholdinfo;
                if (!ProThresholdsLog::where($where)->exists()) {
                    $insertData=CommonUtils::objToArr($v);
                    $insertData["created_at"]=date('Y-m-d H:i:s',time());
                    ProThresholdsLog::insert($insertData);
                }
            }

        }

        $this->info("ok");
    }
    protected function getKzData(){

        $sql="SELECT
                a.id AS project_id,
                a.stage_id,
                c.`name` AS thresholds_name,
                CASE
            WHEN d.thresholdinfo IS NULL THEN
                \"thresholds\"
            ELSE
                \"projects_thresholds\"
            END AS fromwhere,
             CASE
            WHEN d.thresholdinfo IS NULL THEN
                c.thresholdinfo
            ELSE
                d.thresholdinfo
            END AS thresholdinfo
            FROM
                `projects` a
            LEFT JOIN projects_stages b ON a.stage_id = b.id
            LEFT JOIN thresholds c ON b.threshold_id = c.id
            LEFT JOIN projects_thresholds d ON a.stage_id = d.stage_id
            WHERE
                a. STATUS IN (4, 5, 6)
            AND a.stage_id IS NOT NULL
            AND b.deleted_at IS NULL
            AND c.thresholdinfo IS NOT NULL";
             $rs=DB::select($sql);
        return $rs;
    }

}
