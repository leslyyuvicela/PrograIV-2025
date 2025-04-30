<?php

namespace App\Http\Controllers;

use App\Models\Matricula;
use Illuminate\Http\Request;

class MatriculaController extends Controller
{
    // Listar todas las matrículas
    public function index()
    {
        return Matricula::all();
    }

    // Guardar una nueva matrícula
    public function store(Request $request)
    {
        $request->validate([
            'idAlumno'    => 'required|integer|exists:alumnos,id',
            'anio'        => 'required|integer|min:2000',
            'ciclo'       => 'required|string|max:10',
        ]);

        Matricula::create($request->all());

        return response()->json(['msg' => 'ok']);
    }

    // Mostrar una matrícula específica (opcional)
    public function show($id)
    {
        return Matricula::findOrFail($id);
    }

    // Modificar una matrícula existente
    public function update(Request $request, $id)
    {
        $matricula = Matricula::findOrFail($id);

        $request->validate([
            'idAlumno'    => 'required|integer|exists:alumnos,id',
            'anio'        => 'required|integer|min:2000',
            'ciclo'       => 'required|string|max:10',
        ]);

        $matricula->update($request->all());

        return response()->json(['msg' => 'ok']);
    }

    // Eliminar una matrícula
    public function destroy($id)
    {
        Matricula::destroy($id);
        return response()->json(['msg' => 'ok']);
    }
}
