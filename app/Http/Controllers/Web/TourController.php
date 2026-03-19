<?php
// TourController

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Models\Tour;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;

class TourController extends Controller
{
    /**
     * Listado público de tours.
     *
     * Soporta filtros por búsqueda (`q`), rango de precio (`min_price`, `max_price`),
     * estado (`activo`), ordenamiento y paginación (`per_page`).
     * Los resultados se cachean brevemente para mejorar la respuesta en páginas muy visitadas.
     */
    public function index(Request $request)
    {
        try {
            $pageParams = $request->query();
            $cacheKey = 'tours:index:' . md5(http_build_query($pageParams));

            $perPage = (int) $request->input('per_page', 12);

            $result = Cache::remember($cacheKey, 30, function () use ($request, $perPage) {
                $query = Tour::query();

                // Filtrar por estado activo (por defecto solo activos)
                if ($request->has('activo')) {
                    $activo = filter_var($request->input('activo'), FILTER_VALIDATE_BOOLEAN, FILTER_NULL_ON_FAILURE);
                    if (!is_null($activo)) {
                        $query->where('activo', $activo);
                    }
                } else {
                    $query->where('activo', true);
                }

                // Búsqueda por título o descripción
                if ($q = $request->input('q')) {
                    $query->where(function ($qWhere) use ($q) {
                        $qWhere->where('titulo', 'like', "%{$q}%")
                               ->orWhere('descripcion', 'like', "%{$q}%");
                    });
                }

                // Rango de precio
                if ($min = $request->input('min_price')) {
                    $query->where('precio', '>=', (float) $min);
                }
                if ($max = $request->input('max_price')) {
                    $query->where('precio', '<=', (float) $max);
                }

                // Orden seguro
                $allowed = ['created_at', 'precio', 'titulo'];
                $sortBy = $request->input('sort_by', 'created_at');
                $order = strtolower($request->input('order', 'desc')) === 'asc' ? 'asc' : 'desc';
                if (!in_array($sortBy, $allowed)) $sortBy = 'created_at';

                $query->orderBy($sortBy, $order);

                // Paginación
                return $query->paginate(max(1, $perPage))->appends(request()->query());
            });

            return view('pages.tours', ['tours' => $result]);
        } catch (\Throwable $e) {
            Log::error('TourController@index error: ' . $e->getMessage(), ['exception' => $e]);
            // En caso de fallo, devolvemos la vista sin resultados para no romper la UX
            return view('pages.tours', ['tours' => collect([])]);
        }
    }

    /**
     * Mostrar detalle de un tour (por `id` o `slug`).
     * Devuelve la vista `pages.tour` con la variable `tour`.
     */
    public function show(Request $request, $id)
    {
        try {
            // Buscar por slug o id
            $tour = is_numeric($id)
                ? Tour::withCount('reservas')->find((int) $id)
                : Tour::withCount('reservas')->where('slug', $id)->first();

            if (!$tour || !$tour->activo) {
                // Si no existe o no está activo, redirigimos al listado
                if ($request->wantsJson()) {
                    return response()->json(['success' => false, 'message' => 'Tour no encontrado'], 404);
                }
                return redirect()->route('tours')->with('error', 'Tour no encontrado');
            }

            return view('pages.tour', ['tour' => $tour]);
        } catch (\Throwable $e) {
            Log::error('TourController@show error: ' . $e->getMessage(), ['id' => $id]);
            if ($request->wantsJson()) {
                return response()->json(['success' => false, 'message' => 'Error interno'], 500);
            }
            return redirect()->route('tours')->with('error', 'Ocurrió un error al cargar el tour');
        }
    }
}
