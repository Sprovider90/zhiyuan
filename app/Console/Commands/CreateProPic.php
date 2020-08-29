<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
class CreateProPic extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'zhiyuan:createpropic';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = '生成项目相关快照数据';

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
        $path=config("javasource.kz.createpropic_address");
        $date=date("YmdHi",time());
        $dic=$path.substr($date,0,8);
        $filename=substr($date,0,11)."000";
        if (!is_dir($dic)) {
            $mkdir_rs=@mkdir($dic, 0755, true);
            if(empty($mkdir_rs)){
                $this->error("create path err ".$dic);
            }
        }

        file_put_contents($dic."/".$filename.".txt",json_encode($this->getKzData(),true));

        $this->info("ok");
    }
    protected function getKzData(){

        $sql="SELECT
                a.id as project_id,
                a.stage_id,
                c.`name`,
                CASE WHEN d.thresholdinfo IS NULL THEN
                \"thresholds\"
                ELSE
                \"projects_thresholds\" END AS fromwhere,
                CASE WHEN d.thresholdinfo IS NULL THEN
                c.thresholdinfo
                ELSE
                d.thresholdinfo END AS thresholdinfo
            FROM
                `projects` a
            LEFT JOIN projects_stages b ON a.stage_id = b.id
            LEFT JOIN thresholds c ON b.threshold_id = c.id
            LEFT JOIN projects_thresholds d ON a.stage_id = d.stage_id
            WHERE
                a. STATUS IN (4, 5, 6)
            AND a.stage_id IS NOT NULL";
             $rs=DB::select($sql);

        return $rs;
    }

}
