<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Donacion extends Model
{
    use HasFactory;

    protected $fillable = ['tipo', 'descripcion', 'valor_estimado', 'fecha_recepcion', 'donante_id', 'administrador_id'];

    public function donante()
    {
        return $this->belongsTo(Donante::class);
    }

    public function administrador()
    {
        return $this->belongsTo(Administrador::class);
    }
}
