<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ProjectsStatusLog extends Model
{

    protected  $table = 'projects_status_log';

    protected $fillable = [
        'project_id','stage_id', 'status','fromwhere'
    ];

}
