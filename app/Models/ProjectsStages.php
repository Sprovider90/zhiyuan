<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ProjectsStages extends Model
{
    //
    use SoftDeletes;

    protected $fillable = [
        'project_id','stage_name', 'start_date','end_date','threshold_id','stage','default',
    ];

    /**
     * 关联阈值表
     * @return mixed
     */
    public function thresholds(){
        return $this->hasOne(Thresholds::class, 'id', 'threshold_id');
    }
}
