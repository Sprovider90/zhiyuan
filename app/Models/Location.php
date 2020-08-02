<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Location extends Model
{
    //
    use SoftDeletes;


    protected $fillable = [
        'left','top', 'area_id'
    ];

    /**
     * 关联区域表
     * @return mixed
     */
    public function areas(){
        return $this->hasMany(ProjectsAreas::class, 'area_id', 'id');
    }
}
