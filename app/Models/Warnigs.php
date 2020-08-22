<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
class Warnigs extends Model
{
    use SoftDeletes;
    protected  $table = 'warnigs';

    protected $fillable = [
        'project_id','point_id','waring_time','threshold_keys','originaldata'
    ];
    public function project(){
        return $this->hasOne(Projects::class, 'id', 'project_id');
    }

    public function projectsPositions(){
        return $this->hasOne(ProjectsPositions::class, 'id', 'point_id');
    }

}
