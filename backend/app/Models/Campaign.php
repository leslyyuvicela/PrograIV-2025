<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Campaign extends Model
{
    protected $fillable = [
    'user_id',
    'title',
    'description',
    'category_id',
    'status'
    ];

    public function stages() {
        return $this->hasMany(Stage::class);
    }
    
    public function images() {
        return $this->hasMany(FundraisingImages::class);
    }
    public function videos(){
        return $this->hasMany(FundraisingVideos::class);
    }
    
    public function documents() {
        return $this->hasMany(Documents::class);
    }

    public function category() {
        return $this->belongsTo(Category::class);
    }
    
    public function user() {
        return $this->belongsTo(User::class);
    }



}

