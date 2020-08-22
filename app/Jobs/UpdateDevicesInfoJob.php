<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Redis;

class UpdateDevicesInfoJob implements ShouldQueue
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
        if(isset($this->credentials)&&!empty($this->credentials["device_id"])){
            $where=" and b.id=".$this->credentials["device_id"];
        }
        $sql="SELECT
            a.project_id AS projectId,
            a.id AS monitorId,
            b.device_number AS deviceId,
            a.STATUS
        FROM
            `projects_positions` a
            LEFT JOIN devices b ON a.device_id = b.id
        WHERE
            b.device_number IS NOT NULL ".$where;

        $rs=DB::select($sql);

        if(!empty($rs)){
            foreach ($rs as $k=>$v) {
                Redis::hset("zhiyuanv2:air:devices:tags",$v->deviceId,json_encode($v));
                $stat=$v->status==2?0:1;
                Redis::hset("zhiyuanv2:iot:auth:client",$v->deviceId,$stat);
            }
        }
    }
}
