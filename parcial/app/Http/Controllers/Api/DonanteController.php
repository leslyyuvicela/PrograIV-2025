<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Donante;

class DonanteController extends Controller
{
    public function index()
    {
        return Donante::all(); // Devuelve todos los donantes
    }
}

