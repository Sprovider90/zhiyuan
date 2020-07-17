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




    /**
     * 创建时自动生成订单编号
     */
    protected static function boot()
    {
        parent::boot();
        static::creating(function ($model) {
            $model->order_number =  self::createOrderNumber();
        });
    }

    /**
     * 生成生成订单编号
     * @return string
     */
    private static function createOrderNumber(){
        $orderNumber = '';
        $order = self::where('id','>',0)->orderBy('id','desc')->first();
        if($order){
            $arr = explode('-',$order->order_number);
            $orderNumber = $arr[0] == date("Ymd") ? $arr[0].'-'.sprintf("%04d", $arr[1]+1):'';
        }
        if(!$orderNumber){
            $orderNumber = $arr[1] = date("Ymd").'-'.sprintf("%04d", 1);
        }
        return $orderNumber;
    }


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
