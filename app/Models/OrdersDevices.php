<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class OrdersDevices extends Model
{
    //
    use SoftDeletes;



    protected $fillable = [
        'good_id','order_id', 'number'
    ];

    /**
     * 关联订单表
     * @return mixed
     */
    public function orders(){
        return $this->belongsTo(Orders::class, 'order_id', 'id');
    }
}
