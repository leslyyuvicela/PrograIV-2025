<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Donacion;
use Illuminate\Http\Request;

class DonacionController extends Controller
{
    /**
     * Obtener todas las donaciones con sus relaciones (Donante y Administrador)
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        // Obtener las donaciones con las relaciones de Donante y Administrador
        $donaciones = Donacion::with('donante', 'administrador')->get();

        return response()->json($donaciones);
    }

    /**
     * Crear una nueva donación
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        // Validar los datos del formulario
        $request->validate([
            'tipo' => 'required|string|max:255',
            'descripcion' => 'required|string|max:255',
            'valor_estimado' => 'nullable|numeric',
            'fecha_recepcion' => 'required|date',
            'donante_id' => 'required|exists:donantes,id',
            'administrador_id' => 'required|exists:administradores,id',
        ]);

        // Crear la nueva donación
        $donacion = Donacion::create([
            'tipo' => $request->tipo,
            'descripcion' => $request->descripcion,
            'valor_estimado' => $request->valor_estimado,
            'fecha_recepcion' => $request->fecha_recepcion,
            'donante_id' => $request->donante_id,
            'administrador_id' => $request->administrador_id,
        ]);

        return response()->json($donacion, 201);  // Retorna la donación recién creada
    }

    /**
     * Actualizar una donación existente
     *
     * @param \Illuminate\Http\Request $request
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        // Validar los datos del formulario
        $request->validate([
            'tipo' => 'required|string|max:255',
            'descripcion' => 'required|string|max:255',
            'valor_estimado' => 'nullable|numeric',
            'fecha_recepcion' => 'required|date',
            'donante_id' => 'required|exists:donantes,id',
            'administrador_id' => 'required|exists:administradores,id',
        ]);

        // Buscar la donación por su ID
        $donacion = Donacion::findOrFail($id);

        // Actualizar los valores de la donación
        $donacion->update([
            'tipo' => $request->tipo,
            'descripcion' => $request->descripcion,
            'valor_estimado' => $request->valor_estimado,
            'fecha_recepcion' => $request->fecha_recepcion,
            'donante_id' => $request->donante_id,
            'administrador_id' => $request->administrador_id,
        ]);

        return response()->json($donacion);  // Retorna la donación actualizada
    }

    /**
     * Eliminar una donación existente
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        // Buscar la donación por su ID
        $donacion = Donacion::findOrFail($id);

        // Eliminar la donación
        $donacion->delete();

        return response()->json(null, 204);  // Retorna un código 204 (sin contenido) indicando que la donación fue eliminada
    }
}
