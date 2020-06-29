<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use App\Models\Loginlog as loginlogmodel;
use Illuminate\Support\Facades\DB;
class LoginLog implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;
    protected $credentials;   
    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($credentials)
    {
        $this->credentials=$credentials;
    }
    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle(loginlogmodel $Loginlog)
    {
        $users = DB::table('users')->where('name', $this->credentials["name"])
                    ->orWhere('phone', $this->credentials["name"])
                    ->limit(1)->get();
        
        if(isset($users[0])&&$users[0]->id){
           $this->createLoginLog($Loginlog,$users[0]->id);
        }
        
    }
    private function createLoginLog($Loginlog,$users_id){

        $oncenotice_hash=["1"=>"账号或者密码错误","2"=>"登录成功"];
        $data=[];
        $data["users_id"]=$users_id;
        $data["ip"]=$this->credentials["ip"];
        $data["oncenotice"]=$oncenotice_hash[$this->credentials["type"]];
        $data["notice"]=0;
        $Loginlog->create($data);
    }

    private function updateLoginLog(){
        
    }
    
}
