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

}
