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
                CONCAT(a.project_id, '') AS projectId,
                CONCAT(a.id, '') AS monitorId,
                b.device_number AS deviceId,
                b.run_status AS status
            FROM
                `projects_positions` a
            LEFT JOIN devices b ON a.device_id = b.id
            WHERE
                b.device_number IS NOT NULL
            AND a. STATUS = 1 ".$where;

        $rs=DB::select($sql);

        if(!empty($rs)){
            foreach ($rs as $k=>$v) {
                if($v->status==1){
                    $v->updateTime="".date('Y-m-d H:i:s',time());
                    Redis::hset("air:devices:tags",$v->deviceId,json_encode($v));
                }
            }
        }

        $sql="SELECT
            b.device_number AS deviceId,
            b.run_status as status
        FROM
            `devices` b
        WHERE
            1=1 ".$where;

        $rs=DB::select($sql);

        if(!empty($rs)){
            foreach ($rs as $k=>$v) {
                $stat=$v->status==2?"0":"1";
                Redis::hset("iot:auth:client",$v->deviceId,$stat);
            }
        }
    }
}
