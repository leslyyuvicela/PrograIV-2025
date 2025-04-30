<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Administrador extends Model
{
    use HasFactory;

    protected $fillable = ['nombre', 'correo'];

    public function donaciones()
    {
        return $this->hasMany(Donacion::class);
    }
}
