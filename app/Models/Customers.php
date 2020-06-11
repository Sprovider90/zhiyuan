<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Customers extends Model
{
    //
    use SoftDeletes;

    protected $fillable = [
        'company_name','company_addr', 'contact','contact_number','type','email','address','memo'
    ];
}
