<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Preinstall extends Model
{
    //
    use SoftDeletes;

    protected $fillable = [
        'hcho',
        'tvoc',
        'project_id',
        'customer_id',
        'date'
    ];

    /**
     * 关联项目表
     * @return mixed
     */
    public function projects(){
        return $this->hasMany(Projects::class, 'id', 'project_id');
    }
}
