<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class services_stage extends Model
{
    protected $fillable = [
        'services_goal',
        'needed_services',
        'address',
        'stage_id',
    ];

 public function stage()
    {
        return $this->belongsTo(Stage::class);
    }
}
