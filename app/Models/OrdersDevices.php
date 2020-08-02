<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class OrdersDevices extends Model
{
    //
    use SoftDeletes;



    protected $fillable = [
        'good_id','order_id', 'number','customer_id'
    ];



    /**
     * 创建时自动添加用户id
     */
    protected static function boot()
    {
        parent::boot();
        static::creating(function ($model) {
            $model->customer_id =  $model->orders->cid;
        });
    }

    /**
     * 关联订单表
     * @return mixed
     */
    public function orders(){
        return $this->belongsTo(Orders::class, 'order_id', 'id');
    }

}
