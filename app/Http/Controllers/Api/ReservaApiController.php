<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Reserva;
use App\Models\Tour;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Validator;

class ReservaApiController extends Controller
{
    /**
     * Listar todas las reservas
     */
    public function index(Request $request): JsonResponse
    {
        $query = Reserva::with(['usuario', 'tour']);

        if ($request->has('usuario_id')) {
            $query->where('usuario_id', $request->usuario_id);
        }

        $reservas = $query->orderBy('created_at', 'desc')->get();

        return response()->json([
            'success' => true,
            'data' => $reservas
        ]);
    }

    /**
     * Mostrar una reserva específica
     */
    public function show(int $id): JsonResponse
    {
        $reserva = Reserva::with(['usuario', 'tour'])->find($id);

        if (!$reserva) {
            return response()->json([
                'success' => false,
                'message' => 'Reserva no encontrada'
            ], 404);
        }

        return response()->json([
            'success' => true,
            'data' => $reserva
        ]);
    }

    /**
     * Crear una nueva reserva
     */
    public function store(Request $request): JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'usuario_id' => 'required|exists:usuarios,id',
            'tour_id' => 'required|exists:tours,id',
            'fecha' => 'required|date|after_or_equal:today',
            'personas' => 'required|integer|min:1|max:50',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Error de validación',
                'errors' => $validator->errors()
            ], 422);
        }

        $tour = Tour::find($request->tour_id);
        if (!$tour || !$tour->activo) {
            return response()->json([
                'success' => false,
                'message' => 'Tour no disponible'
            ], 404);
        }

        $total = $tour->precio * $request->personas;

        $reserva = Reserva::create([
            'usuario_id' => $request->usuario_id,
            'tour_id' => $request->tour_id,
            'fecha' => $request->fecha,
            'personas' => $request->personas,
            'total' => $total,
            'estado' => 'pendiente',
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Reserva creada exitosamente',
            'data' => $reserva->load(['usuario', 'tour'])
        ], 201);
    }
}
