<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Projects extends Model
{
    //
    use SoftDeletes;

    protected $fillable = [
        'number','name', 'customer_id','address','hcho','tvoc'
    ];

    /**
     * 创建时自动生成订单编号
     */
    protected static function boot()
    {
        parent::boot();
        static::creating(function ($model) {
            $model->number =  self::createNumber();
        });
    }

    /**
     * 生成生成订单编号
     * @return string
     */
    private static function createNumber(){
        $number = '';
        $model = self::where('id','>',0)->orderBy('id','desc')->first();
        if($model){
            $arr = explode('-',$model->number);
            $number = $arr[0] == date("Ymd") ? $arr[0].'-'.sprintf("%04d", $arr[1]+1):'';
        }
        if(!$number){
            $number = $arr[1] = date("Ymd").'-'.sprintf("%04d", 1);
        }
        return $number;
    }

    /**
     * 关联区域表
     * @return mixed
     */
    public function areas(){
        return $this->hasMany(ProjectsAreas::class, 'project_id', 'id');
    }

    /**
     * 关联阶段表
     * @return mixed
     */
    public function stages(){
        return $this->hasMany(ProjectsStages::class, 'project_id', 'id');
    }
}
