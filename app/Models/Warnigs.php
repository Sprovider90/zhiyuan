<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Warnigs extends Model
{
    use SoftDeletes;
    protected  $table = 'warnigs';

    protected $fillable = [
        'project_id','point_id','waring_time','threshold_keys','originaldata'
    ];
}
