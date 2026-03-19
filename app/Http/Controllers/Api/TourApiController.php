<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Tour;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Log;

class TourApiController extends Controller
{
    /**
     * Listar todos los tours activos
     */
    public function index(Request $request): JsonResponse
    {
        try {
            $query = Tour::query();

            // Filtrar por estado activo (por defecto true)
            if ($request->has('activo')) {
                $activo = filter_var($request->activo, FILTER_VALIDATE_BOOLEAN, FILTER_NULL_ON_FAILURE);
                if (!is_null($activo)) {
                    $query->where('activo', $activo);
                }
            } else {
                $query->where('activo', true);
            }

            // Búsqueda simple por título o descripción
            if ($search = $request->input('q')) {
                $query->where(function ($q) use ($search) {
                    $q->where('titulo', 'like', "%{$search}%")
                      ->orWhere('descripcion', 'like', "%{$search}%");
                });
            }

            // Ordenamiento seguro
            $allowedSort = ['created_at', 'precio', 'titulo'];
            $sortBy = $request->input('sort_by', 'created_at');
            $order = strtolower($request->input('order', 'desc')) === 'asc' ? 'asc' : 'desc';
            if (!in_array($sortBy, $allowedSort)) {
                $sortBy = 'created_at';
            }

            $query->orderBy($sortBy, $order);

            // Paginación (per_page puede ser 'all')
            if ($request->filled('per_page') && $request->per_page === 'all') {
                $tours = $query->get();
                return response()->json(['success' => true, 'data' => $tours]);
            }

            $perPage = (int) $request->input('per_page', 15);
            $tours = $query->paginate(max(1, $perPage))->appends($request->query());

            return response()->json(['success' => true, 'data' => $tours]);
        } catch (\Throwable $e) {
            Log::error('Error obteniendo tours: ' . $e->getMessage(), ['request' => $request->all()]);
            return response()->json(['success' => false, 'message' => 'Error interno'], 500);
        }
    }

    /**
     * Mostrar un tour específico
     */
    public function show(int $id): JsonResponse
    {
        try {
            $tour = Tour::where('activo', true)->find($id);

            if (!$tour) {
                return response()->json(['success' => false, 'message' => 'Tour no encontrado'], 404);
            }

            return response()->json(['success' => true, 'data' => $tour]);
        } catch (\Throwable $e) {
            Log::error('Error mostrando tour: ' . $e->getMessage(), ['id' => $id]);
            return response()->json(['success' => false, 'message' => 'Error interno'], 500);
        }
    }
}
