<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Loginlog extends Model
{
   protected $table = 'users_login_log';
   public function users()
    {
        return $this->belongsTo(User::class);
    }	
}
