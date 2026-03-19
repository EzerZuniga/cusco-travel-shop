<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Models\Tour;
use App\Models\Reserva;
use App\Models\Usuario;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Schema;

class HomeController extends Controller
{
    /**
     * Mostrar la página principal.
     *
     * Carga:
     * - Tours destacados para el carrusel
     * - Tours recientes
     * - Estadísticas básicas (usuarios, tours, reservas)
     * - Ingresos de los últimos 30 días
     *
     * Los resultados se cachean durante un corto periodo para aliviar consultas frecuentes.
     */
    public function index(Request $request)
    {
        try {
            // Cache keys
            $cacheTtl = 60; // segundos
            $featuredKey = 'home:featured_tours';
            $recentKey = 'home:recent_tours';
            $statsKey = 'home:stats';

            // Tours destacados (ej. campo 'destacado' en la tabla tours)
            $featured = Cache::remember($featuredKey, $cacheTtl, function () {
                $query = Tour::where('activo', true);
                
                // Si existe la columna 'destacado' la usamos
                if (Schema::hasColumn('tours', 'destacado')) {
                    $query->where('destacado', true);
                }
                
                return $query->orderBy('updated_at', 'desc')
                    ->take(6)
                    ->get();
            });

            // Tours recientes
            $recent = Cache::remember($recentKey, $cacheTtl, function () {
                return Tour::where('activo', true)
                    ->orderBy('created_at', 'desc')
                    ->take(8)
                    ->get();
            });

            // Estadísticas: contadores y suma de ingresos últimos 30 días
            $stats = Cache::remember($statsKey, $cacheTtl, function () {
                $usuariosCount = Usuario::count();
                $toursCount = Tour::count();
                $reservasCount = Reserva::count();

                $incomeLast30 = Reserva::where('created_at', '>=', now()->subDays(30))
                    ->sum('total');

                return [
                    'usuarios' => $usuariosCount,
                    'tours' => $toursCount,
                    'reservas' => $reservasCount,
                    'incomeLast30' => $incomeLast30,
                ];
            });

            return view('pages.index', [
                'featured' => $featured,
                'recent' => $recent,
                'stats' => $stats,
            ]);
        } catch (\Throwable $e) {
            // Si algo falla, logueamos el error y devolvemos la vista base sin datos críticos
            Log::error('HomeController@index error: ' . $e->getMessage(), ['exception' => $e]);
            return view('pages.index', [
                'featured' => collect([]),
                'recent' => collect([]),
                'stats' => [
                    'usuarios' => 0,
                    'tours' => 0,
                    'reservas' => 0,
                    'incomeLast30' => 0,
                ],
            ]);
        }
    }
}
