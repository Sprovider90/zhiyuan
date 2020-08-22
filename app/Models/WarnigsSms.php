<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
class WarnigsSms extends Model
{
    use SoftDeletes;
    protected  $table = 'warnigs_sms';

    protected $fillable = [
        'warnig_id','send_id','customer_id','content','pics'
    ];

}
