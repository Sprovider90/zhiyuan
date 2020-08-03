<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Position extends Model
{
    //
    use SoftDeletes;

    protected  $table = 'projects_positions';

    protected $fillable = [
        'project_id','number', 'name','device_id','area_id','status'
    ];

    /**
     * 关联区域表
     * @return mixed
     */
    public function areas(){
        return $this->hasOne(ProjectsAreas::class, 'id', 'area_id');
    }

    /**
     * 关联坐标表
     * @return mixed
     */
    public function location(){
        return $this->hasOne(Location::class, 'position_id', 'id');
    }
}
