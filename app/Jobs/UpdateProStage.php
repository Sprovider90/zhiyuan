<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\DB;
use App\Models\Projects;

class UpdateProStage implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;
    protected $credentials;
    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($credentials=[])
    {
        $this->credentials=$credentials;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $where="";
        if(isset($this->credentials)&&!empty($this->credentials["project_id"])){
                $where=" where a.id=".$this->credentials["project_id"];
        }
        $sql="SELECT
                a.id AS project_id,
                CASE
            WHEN stage_stage_id IS NULL THEN
                0
            ELSE
                stage_stage_id
            END AS stage_stage_id,
             CASE
            WHEN max_end_date IS NULL
            OR min_start_date IS NULL THEN
                3
            WHEN date_format(now(), '%Y-%m-%d') < min_start_date THEN
                0
            WHEN date_format(now(), '%Y-%m-%d') > max_end_date THEN
                2
            WHEN stage_stage_id IS NULL THEN
                1
            WHEN stage = 1 THEN
                4
            WHEN stage = 2 THEN
                5
            WHEN stage = 3 THEN
                6
            ELSE
                7
            END AS pro_status ##状态0未开始1暂停中2已结束3项目错误4施工中5交付中6维护中7项目大阶段错误
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
            ) c ON a.id = c.project_id ".$where;

        $rs=DB::select($sql);
        if(!empty($rs)){
            foreach ($rs as $k=>$v) {
                Projects::where('id', $v->project_id)->update(['status' => $v->pro_status, 'stage_id' => $v->stage_stage_id]);
            }
        }
        //生成快照供预警使用
    }
}
