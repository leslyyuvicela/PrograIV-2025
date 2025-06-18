<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class goods_stage extends Model
{
    protected $fillable = [
        'goods_goal',
        'needed_goods',
        'address',
        'stage_id',
        
    ];

 public function stage()
    {
        return $this->belongsTo(Stage::class);
    }
}
