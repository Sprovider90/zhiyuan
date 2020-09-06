<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ProThresholdsLog extends Model
{
    protected  $table = 'pro_thresholds_log';
    protected $fillable = [
        'project_id','thresholdinfo','fromwhere','thresholds_name','stage_id'
    ];
}
