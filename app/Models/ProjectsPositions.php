<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ProjectsPositions extends Model
{
    //
    use SoftDeletes;

    protected $fillable = [
        'project_id','number', 'name','good_id','device_id','area_id'
    ];

    /**
     * 关联区域表
     * @return mixed
     */
    public function area(){
        return $this->hasOne(ProjectsAreas::class, 'id', 'area_id');
    }

    /**
     * 关联设备表
     * @return mixed
     */
    public function device(){
        return $this->hasOne(OrdersDevices::class, 'id', 'device_id');
    }

    /**
     * 关联点位坐标表
     * @return mixed
     */
    public function location(){
        return $this->hasOne(Location::class, 'id', 'position_id');
    }

    /**
     * 关联点位坐标表
     * @return mixed
     */
    public function project(){
        return $this->hasOne(Projects::class, 'id', 'project_id');
    }

}
