<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Customers extends Model
{
    //
    use SoftDeletes;

    protected $fillable = [
        'company_name','company_addr', 'type','logo','email','address','memo'
    ];

    /**
     * 关联联系人表
     * @return mixed
     */
    public function contacts(){
        return $this->hasMany(CustomersContacts::class, 'cid', 'id');
    }

    /**
     * 关联项目表
     * @return mixed
     */
    public function projects(){
        return $this->hasMany(Projects::class, 'customer_id', 'id');
    }

    /**
     * 关联文件表
     * @return mixed
     */
    public function logos(){
        return $this->hasOne(Files::class, 'id', 'logo');
    }
}
