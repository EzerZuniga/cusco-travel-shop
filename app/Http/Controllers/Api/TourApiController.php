<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Tour;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;

class TourApiController extends Controller
{
    /**
     * Listar todos los tours activos
     */
    public function index(): JsonResponse
    {
        $tours = Tour::where('activo', true)
            ->orderBy('created_at', 'desc')
            ->get();

        return response()->json([
            'success' => true,
            'data' => $tours
        ]);
    }

    /**
     * Mostrar un tour específico
     */
    public function show(int $id): JsonResponse
    {
        $tour = Tour::where('activo', true)->find($id);

        if (!$tour) {
            return response()->json([
                'success' => false,
                'message' => 'Tour no encontrado'
            ], 404);
        }

        return response()->json([
            'success' => true,
            'data' => $tour
        ]);
    }
}
