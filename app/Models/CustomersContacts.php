<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class CustomersContacts extends Model
{
    //
    use SoftDeletes;

    protected $fillable = [
        'cid','contact', 'contact_phone','job'
    ];

    /**
     * 关联客户表
     * @return mixed
     */
    public function customs(){
        return $this->belongsTo(Customers::class, 'cid', 'id');
    }


}
