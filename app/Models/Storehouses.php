<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Storehouses extends Model
{
    //
    use SoftDeletes;

    protected $fillable = [
        'name','address', 'desc','status','num'
    ];


}
