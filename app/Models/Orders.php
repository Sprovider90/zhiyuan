<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Orders extends Model
{
    //
    use SoftDeletes;

    protected $fillable = [
        'order_number','gid', 'cid','num','money','order_status','send_goods_status','express_name','express_number'
    ];

}
