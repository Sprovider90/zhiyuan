<?php

namespace App\Models;
use Illuminate\Database\Eloquent\Model;

class Breakdown extends Model
{

    protected $fillable = [
        'project_id','device_id', 'type','happen_time'
    ];
    public function project()
    {
        return $this->belongsTo(Projects::class);
    }
}
