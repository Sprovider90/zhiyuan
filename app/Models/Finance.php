<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Finance extends Model
{
    //
    use SoftDeletes;
    protected  $table = 'orders';

    protected $fillable = [
        'order_number','gid', 'cid','num','money','order_status','send_goods_status','express_name','express_number','type','invoice'
    ];



    /**
     * 关联发货表
     * @return mixed
     */
    public function devices(){
        return $this->hasMany(OrdersDevices::class, 'order_id', 'id');
    }

    /**
     * 关联客户表
     * @return mixed
     */
    public function customs(){
        return $this->belongsTo(Customers::class, 'cid', 'id');
    }

    /**
     * 关联收退款记录表
     * @return mixed
     */
    public function logs(){
        return $this->hasMany( FinanceLog::class, 'order_id', 'id');
    }
}
