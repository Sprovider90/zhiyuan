<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class FinanceLog extends Model
{
    use SoftDeletes;
    protected  $table = 'finance_logs';

    protected $fillable = [
        'order_id','money', 'type','pay_type','file','date'
    ];

    /**
    * 创建时自动添加参数
    */
    protected static function boot()
    {
        parent::boot();
        static::creating(function ($model) {
            $model->user_id =  auth('api')-> user()->id;
        });
    }

    /**
     * 关联用户表
     * @return mixed
     */
    public function user(){
        return $this->hasOne(User::class, 'id', 'user_id');
    }

}
