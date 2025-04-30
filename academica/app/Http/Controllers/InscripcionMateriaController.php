<?php

namespace App\Http\Controllers;

use App\Models\InscripcionMateria;
use Illuminate\Http\Request;

class InscripcionMateriaController extends Controller
{
    // Listar todas las inscripciones
    public function index()
    {
        return InscripcionMateria::all();
    }

    // Guardar nueva inscripción
    public function store(Request $request)
    {
        $request->validate([
            'idAlumno'  => 'required|integer|exists:alumnos,id',
            'idMateria' => 'required|integer|exists:materias,id',
        ]);

        InscripcionMateria::create($request->all());

        return response()->json(['msg' => 'ok']);
    }

    // Mostrar una inscripción por ID (opcional)
    public function show($id)
    {
        return InscripcionMateria::findOrFail($id);
    }

    // Modificar una inscripción existente
    public function update(Request $request, $id)
    {
        $inscripcion = InscripcionMateria::findOrFail($id);

        $request->validate([
            'idAlumno'  => 'required|integer|exists:alumnos,id',
            'idMateria' => 'required|integer|exists:materias,id',
        ]);

        $inscripcion->update($request->all());

        return response()->json(['msg' => 'ok']);
    }

    // Eliminar una inscripción
    public function destroy($id)
    {
        InscripcionMateria::destroy($id);
        return response()->json(['msg' => 'ok']);
    }
}
