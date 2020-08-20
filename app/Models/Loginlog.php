<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Loginlog extends Model
{
   protected $table = 'users_login_log';
   protected $fillable = [
        'users_id','ip', 'oncenotice','notice',"users_name","users_truename"
    ];
   public function users()
    {
        return $this->belongsTo(User::class)->with("customer");
    }
}
