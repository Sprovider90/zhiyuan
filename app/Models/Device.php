<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Device extends Model
{
    //
    use SoftDeletes;

    protected  $table = 'devices';

    protected $fillable = [
        'good_id','device_number', 'come_date','model','manufacturer','storehouse_id','customer_id','check_data','status'
    ];

    /**
     * 关联客户表
     * @return mixed
     */
    public function customer(){
        return $this->hasMany(Customers::class, 'id', 'customer_id');
    }

    /**
     * 关联仓库表
     * @return mixed
     */
    public function storehouse(){
        return $this->hasMany(Customers::class, 'id', 'storehouse_id');
    }
}
