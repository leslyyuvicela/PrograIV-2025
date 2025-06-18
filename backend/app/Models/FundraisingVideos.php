<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class FundraisingVideos extends Model
{
    //
    protected $fillable = [
        'campaign_id',
        'url',
    ];
    public function campaign()
    {
        return $this->belongsTo(Campaign::class);
    }
}
