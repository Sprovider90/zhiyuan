<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
class CreateDevicesInfoPic extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'zhiyuan:createdevicesinfopic';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = '生成设备所属的项目和所属的检测点关系的历史快照';

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
        $path=config("javasource.kz.createdevicesinfo_address");
        $date=date("YmdHi",time());
        $dic=$path.substr($date,0,8);
        $filename=$date."00";
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
                CONCAT(a.project_id, '') AS projectId,
                CONCAT(a.id, '') AS monitorId,
                b.device_number AS deviceId,
                b.run_status AS status
            FROM
                `projects_positions` a
            LEFT JOIN devices b ON a.device_id = b.id
            WHERE
                b.device_number IS NOT NULL
            AND a. STATUS = 1";
             $rs=DB::select($sql);
        return $rs;
    }

}
