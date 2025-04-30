<?php

namespace App\Http\Controllers;

use App\Models\Materia;
use Illuminate\Http\Request;

class MateriaController extends Controller
{
    // Listar todas las materias
    public function index()
    {
        return Materia::all();
    }

    // Guardar una nueva materia
    public function store(Request $request)
    {
        $request->validate([
            'codigo' => 'required|string|max:20|unique:materias,codigo',
            'nombre' => 'required|string|max:150',
            'uv'     => 'required|integer|min:1',
        ]);

        Materia::create($request->all());

        return response()->json(['msg' => 'ok']);
    }

    // Mostrar una materia específica (opcional)
    public function show($id)
    {
        return Materia::findOrFail($id);
    }

    // Modificar una materia existente
    public function update(Request $request, $id)
    {
        $materia = Materia::findOrFail($id);

        $request->validate([
            'codigo' => 'required|string|max:20|unique:materias,codigo,' . $id,
            'nombre' => 'required|string|max:150',
            'uv'     => 'required|integer|min:1',
        ]);

        $materia->update($request->all());

        return response()->json(['msg' => 'ok']);
    }

    // Eliminar una materia
    public function destroy($id)
    {
        Materia::destroy($id);
        return response()->json(['msg' => 'ok']);
    }
}
