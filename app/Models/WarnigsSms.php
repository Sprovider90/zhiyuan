<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Models\User;
class WarnigsSms extends Model
{
    use SoftDeletes;
    protected  $table = 'warnigs_sms';

    protected $fillable = [
        'warnig_id','send_id','customer_id','content','pics'
    ];
    public function user(){
        return $this->hasOne(User::class, 'id', 'send_id');
    }

    public function warnings(){
        return $this->hasOne(Warnigs::class, 'id', 'warnig_id');
    }

}
