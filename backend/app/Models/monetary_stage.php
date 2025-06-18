<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class monetary_stage extends Model
{
    protected $fillable = [
        'money_goal',
        'stage_id',
    ];

    public function stage()
    {
        return $this->belongsTo(Stage::class);
    }
}
