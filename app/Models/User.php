<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;

use Tymon\JWTAuth\Contracts\JWTSubject;

use Spatie\Permission\Traits\HasRoles;

class User extends Authenticatable implements JWTSubject
{
    use HasRoles;
    public function getJWTIdentifier()
    {
        return $this->getKey();
    }

    public function getJWTCustomClaims()
    {
        return [];
    }
    protected $fillable = [
        'name','phone', 'truename','password','type','customer_id','roles','status'
    ];

    protected $hidden = [
        'password', 'remember_token'
    ];

    public function isAuthorOf($model)
    {
        return $this->id == $model->user_id;
    }


    public function setPasswordAttribute($value)
    {
        // 如果值的长度等于 60，即认为是已经做过加密的情况
        if (strlen($value) != 60) {

            // 不等于 60，做密码加密处理
            $value = bcrypt($value);
        }

        $this->attributes['password'] = $value;

    }
    public function customer()
    {
        return $this->belongsTo(Customers::class);
    }

}
