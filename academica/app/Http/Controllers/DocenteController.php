<?php

namespace App\Http\Controllers;

use App\Models\Docente;
use Illuminate\Http\Request;

class DocenteController extends Controller
{
    // Obtener todos los docentes
    public function index()
    {
        return Docente::all();
    }

    // Registrar un nuevo docente
    public function store(Request $request)
    {
        $request->validate([
            'codigo'    => 'required|unique:docentes,codigo|max:50',
            'nombre'    => 'required|string|max:150',
            'direccion' => 'required|string|max:255',
            'telefono'  => 'nullable|string|max:20',
            'email'     => 'nullable|email|max:100',
        ]);

        Docente::create($request->all());

        return response()->json(['msg' => 'ok']);
    }

    // Mostrar un docente por ID (opcional)
    public function show($id)
    {
        return Docente::findOrFail($id);
    }

    // Modificar un docente existente
    public function update(Request $request, $id)
    {
        $docente = Docente::findOrFail($id);

        $request->validate([
            'codigo'    => 'required|max:50|unique:docentes,codigo,' . $id,
            'nombre'    => 'required|string|max:150',
            'direccion' => 'required|string|max:255',
            'telefono'  => 'nullable|string|max:20',
            'email'     => 'nullable|email|max:100',
        ]);

        $docente->update($request->all());

        return response()->json(['msg' => 'ok']);
    }

    // Eliminar un docente
    public function destroy($id)
    {
        Docente::destroy($id);
        return response()->json(['msg' => 'ok']);
    }
}
