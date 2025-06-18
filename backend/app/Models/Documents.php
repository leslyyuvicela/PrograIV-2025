<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Documents extends Model
{
    protected $fillable = [
        'file_path',
        'campaign_id'
    ];
    public function campaign() {
        return $this->belongsTo(Campaign::class);
    }
}
