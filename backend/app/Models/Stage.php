<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Stage extends Model
{
    protected $fillable = [
        'title',
        'description',
        'deadline',
        'type',
        'campaign_id',
    ];
    public function campaign() {
        return $this->belongsTo(Campaign::class);
    }

    public function monetary_stage() {
        return $this->hasOne(monetary_stage::class, 'stage_id');
    }

    public function goods_stage() {
        return $this->hasOne(goods_stage::class, 'stage_id');
    }

    public function services_stage() {
        return $this->hasOne(services_stage::class, 'stage_id');
    }
}
