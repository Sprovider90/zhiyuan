<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Thresholds extends Model
{
    //
    use SoftDeletes;
    protected  $table = 'thresholds';

    protected $fillable = [
        'name','status','thresholdinfo','descript'
    ];


}
