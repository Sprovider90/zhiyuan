<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
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
        //获取项目-当前状态-阶段
        $sql="SELECT
                a.id as project_id,
                stage_stage_id,
                case 
                when max_end_date IS null or min_start_date is null then 3
                when date_format(now(), '%Y-%m-%d')< min_start_date  then 0
                when date_format(now(), '%Y-%m-%d')> max_end_date then 2
                when stage_stage_id IS NULL then 1
                when stage=1 then 4
                when stage=2 then 5
                when stage=3 then 6
                else 7  end as status ##状态0未开始1暂停中2已结束3项目错误4施工中5交付中6维护中7项目大阶段错误
            FROM
                projects a
            LEFT JOIN (
                SELECT
                    id AS stage_stage_id,
                    project_id,
                    stage_name,
                    stage
                FROM
                    `projects_stages`
                WHERE
                    start_date <= date_format(now(), '%Y-%m-%d')
                AND end_date >= date_format(now(), '%Y-%m-%d')
            ) b ON a.id = b.project_id
            LEFT JOIN (
                SELECT
                    project_id,
                    min(start_date) AS min_start_date,
                    max(end_date) AS max_end_date
                FROM
                    projects_stages
                GROUP BY
                    project_id
            ) c 
            on a.id = c.project_id;
            ";

        $rs=DB::select($sql);

        //修改项目状态和阶段
        //生成快照供预警使用
        $this->info("ok");
    }

}
