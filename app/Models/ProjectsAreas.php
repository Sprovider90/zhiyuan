<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ProjectsAreas extends Model
{
    //
    use SoftDeletes;

    protected $fillable = [
        'project_id','area_name', 'file'
    ];

    /**
     * 关联文件表
     * @return mixed
     */
    public function file(){
        return $this->hasOne(Files::class, 'id', 'file');
    }

}
