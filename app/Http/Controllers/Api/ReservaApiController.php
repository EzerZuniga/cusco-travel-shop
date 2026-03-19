<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Reserva;
use App\Models\Tour;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class ReservaApiController extends Controller
{
    /**
     * Listar todas las reservas
     */
    public function index(Request $request): JsonResponse
    {
        try {
            $query = Reserva::with(['usuario', 'tour']);

            // Si el usuario está autenticado, solo mostrar sus reservas (a menos que sea admin)
            if (Auth::check()) {
                $user = Auth::user();
                // Si no es admin, solo mostrar sus propias reservas
                if (!$user->can('admin')) {
                    $query->where('usuario_id', $user->id);
                } elseif ($request->has('usuario_id')) {
                    // Si es admin y se especifica usuario_id, filtrar por ese usuario
                    $query->where('usuario_id', $request->usuario_id);
                }
            } elseif ($request->has('usuario_id')) {
                // Si no está autenticado pero se especifica usuario_id (solo para desarrollo/testing)
                $query->where('usuario_id', $request->usuario_id);
            } else {
                // Sin autenticación y sin usuario_id, no devolver nada
                return response()->json([
                    'success' => false,
                    'message' => 'Debes estar autenticado para ver reservas'
                ], 401);
            }

            // Filtros adicionales
            if ($request->has('estado')) {
                $query->where('estado', $request->estado);
            }

            if ($request->has('tour_id')) {
                $query->where('tour_id', $request->tour_id);
            }

            $reservas = $query->orderBy('created_at', 'desc')->paginate($request->input('per_page', 15));

            return response()->json([
                'success' => true,
                'data' => $reservas
            ]);
        } catch (\Throwable $e) {
            Log::error('Error obteniendo reservas: ' . $e->getMessage(), [
                'trace' => $e->getTraceAsString()
            ]);
            return response()->json([
                'success' => false,
                'message' => 'Error al obtener las reservas'
            ], 500);
        }
    }

    /**
     * Mostrar una reserva específica
     */
    public function show(int $id): JsonResponse
    {
        try {
            $reserva = Reserva::with(['usuario', 'tour'])->find($id);

            if (!$reserva) {
                return response()->json([
                    'success' => false,
                    'message' => 'Reserva no encontrada'
                ], 404);
            }

            // Verificar que el usuario autenticado sea el dueño de la reserva o sea admin
            if (Auth::check()) {
                $user = Auth::user();
                if ($reserva->usuario_id !== $user->id && !$user->can('admin')) {
                    return response()->json([
                        'success' => false,
                        'message' => 'No tienes permiso para ver esta reserva'
                    ], 403);
                }
            } else {
                return response()->json([
                    'success' => false,
                    'message' => 'Debes estar autenticado para ver reservas'
                ], 401);
            }

            return response()->json([
                'success' => true,
                'data' => $reserva
            ]);
        } catch (\Throwable $e) {
            Log::error('Error mostrando reserva: ' . $e->getMessage(), [
                'id' => $id,
                'trace' => $e->getTraceAsString()
            ]);
            return response()->json([
                'success' => false,
                'message' => 'Error al obtener la reserva'
            ], 500);
        }
    }

    /**
     * Crear una nueva reserva
     */
    public function store(Request $request): JsonResponse
    {
        // Reglas dinámicas: si hay usuario autenticado, permitimos omitir usuario_id
        $rules = [
            'tour_id' => 'required|exists:tours,id',
            'fecha' => 'required|date|after_or_equal:today',
            'personas' => 'required|integer|min:1|max:50',
        ];

        if (!Auth::check()) {
            $rules['usuario_id'] = 'required|exists:usuarios,id';
        } else {
            // Si el usuario está autenticado, preferimos el id del guard
            $request->merge(['usuario_id' => Auth::id()]);
        }

        $validator = Validator::make($request->all(), $rules);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Error de validación',
                'errors' => $validator->errors()
            ], 422);
        }

        try {
            $tour = Tour::find($request->tour_id);
            if (!$tour) {
                return response()->json([
                    'success' => false,
                    'message' => 'Tour no encontrado'
                ], 404);
            }

            // Verificar que el tour esté activo
            if (!$tour->activo) {
                return response()->json([
                    'success' => false,
                    'message' => 'Tour no disponible'
                ], 400);
            }

            // Validar disponibilidad de plazas para la fecha solicitada
            $fechaReserva = Carbon::parse($request->fecha);
            if (!$tour->isAvailable($fechaReserva)) {
                return response()->json([
                    'success' => false,
                    'message' => 'No hay disponibilidad para la fecha seleccionada'
                ], 400);
            }

            // Verificar plazas disponibles si el tour tiene capacidad definida
            $plazasDisponibles = $tour->availableSeats($fechaReserva);
            if (!is_null($plazasDisponibles) && $plazasDisponibles < $request->personas) {
                return response()->json([
                    'success' => false,
                    'message' => "Solo hay {$plazasDisponibles} plazas disponibles para esta fecha"
                ], 400);
            }

            // Calcular total (asumimos que $tour->precio existe)
            $total = ($tour->precio ?? 0) * $request->personas;

            DB::beginTransaction();

            $reserva = Reserva::create([
                'usuario_id' => $request->usuario_id,
                'tour_id' => $request->tour_id,
                'fecha' => $request->fecha,
                'personas' => $request->personas,
                'total' => $total,
                'estado' => 'pendiente',
            ]);

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Reserva creada exitosamente',
                'data' => $reserva->load(['usuario', 'tour'])
            ], 201);
        } catch (\Throwable $e) {
            DB::rollBack();
            Log::error('Error creando reserva: ' . $e->getMessage(), ['input' => $request->all()]);

            return response()->json([
                'success' => false,
                'message' => 'Ocurrió un error creando la reserva'
            ], 500);
        }
    }

    /**
     * Eliminar una reserva
     */
    public function destroy(int $id): JsonResponse
    {
        try {
            $reserva = Reserva::find($id);

            if (!$reserva) {
                return response()->json([
                    'success' => false,
                    'message' => 'Reserva no encontrada'
                ], 404);
            }

            // Verificar que el usuario autenticado sea el dueño de la reserva o sea admin
            if (Auth::check() && Auth::id() !== $reserva->usuario_id) {
                // Si no es el dueño, verificar si es admin
                if (!Auth::user()->can('admin')) {
                    return response()->json([
                        'success' => false,
                        'message' => 'No tienes permiso para eliminar esta reserva'
                    ], 403);
                }
            }

            // Verificar si la reserva puede ser cancelada
            if (!$reserva->isCancelable()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Esta reserva no puede ser cancelada'
                ], 400);
            }

            DB::beginTransaction();
            $reserva->delete();
            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Reserva eliminada exitosamente'
            ], 200);
        } catch (\Throwable $e) {
            DB::rollBack();
            Log::error('Error eliminando reserva: ' . $e->getMessage(), ['id' => $id]);
            return response()->json([
                'success' => false,
                'message' => 'Error al eliminar la reserva'
            ], 500);
        }
    }

    /**
     * Listar todas las reservas (solo para administradores)
     */
    public function adminIndex(Request $request): JsonResponse
    {
        try {
            $query = Reserva::with(['usuario', 'tour']);

            // Filtros para administradores
            if ($request->has('usuario_id')) {
                $query->where('usuario_id', $request->usuario_id);
            }

            if ($request->has('tour_id')) {
                $query->where('tour_id', $request->tour_id);
            }

            if ($request->has('estado')) {
                $query->where('estado', $request->estado);
            }

            if ($request->has('fecha_desde')) {
                $query->whereDate('fecha', '>=', $request->fecha_desde);
            }

            if ($request->has('fecha_hasta')) {
                $query->whereDate('fecha', '<=', $request->fecha_hasta);
            }

            $reservas = $query->orderBy('created_at', 'desc')
                ->paginate($request->input('per_page', 20));

            return response()->json([
                'success' => true,
                'data' => $reservas
            ]);
        } catch (\Throwable $e) {
            Log::error('Error obteniendo reservas (admin): ' . $e->getMessage(), [
                'trace' => $e->getTraceAsString()
            ]);
            return response()->json([
                'success' => false,
                'message' => 'Error al obtener las reservas'
            ], 500);
        }
    }
}
