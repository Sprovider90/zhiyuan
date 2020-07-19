<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ProjectsWaringSetting extends Model
{

    protected  $table = 'projects_waring_setting';
    protected $fillable = [
        'project_id','remind_time', 'percentage','notice_start_time','notice_end_time','notice_phone'
    ];



}
