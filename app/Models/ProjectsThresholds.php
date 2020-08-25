<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ProjectsThresholds extends Model
{
    use SoftDeletes;
    protected  $table = 'projects_thresholds';
    protected $fillable = [
        'project_id','stage_id', 'thresholdinfo'
    ];



}
