<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class BlogController extends Controller
{
    /**
     * Lista de artículos del blog.
     * Lee `storage/app/data/blog.json` y aplica búsqueda, categoría y paginación.
     * Si la petición solicita JSON, devuelve data JSON (útil para el BlogManager frontend).
     */
    public function index(Request $request)
    {
        try {
            $path = storage_path('app/data/blog.json');
            if (!file_exists($path)) {
                return $this->respondError('No se encontró el archivo de contenido del blog', 500, $request);
            }

            $raw = file_get_contents($path);
            $items = json_decode($raw, true) ?: [];

            $collection = collect($items);

            // Filtro por categoría
            if ($request->filled('category')) {
                $category = $request->input('category');
                $collection = $collection->filter(function ($item) use ($category) {
                    return isset($item['category']) && strcasecmp($item['category'], $category) === 0;
                });
            }

            // Búsqueda por título o extracto
            if ($request->filled('q')) {
                $q = mb_strtolower($request->input('q'));
                $collection = $collection->filter(function ($item) use ($q) {
                    $titulo = mb_strtolower($item['title'] ?? '');
                    $excerpt = mb_strtolower($item['excerpt'] ?? '');
                    return mb_strpos($titulo, $q) !== false || mb_strpos($excerpt, $q) !== false;
                });
            }

            // Orden descendente por fecha por defecto
            $collection = $collection->sortByDesc(function ($item) {
                return $item['date'] ?? null;
            })->values();

            // Paginación simple (si se solicita JSON, soportamos per_page; para HTML devolvemos todo y JS filtra)
            $perPage = (int) $request->input('per_page', 10);
            if ($request->wantsJson()) {
                $page = max(1, (int) $request->input('page', 1));
                $offset = ($page - 1) * $perPage;
                $data = $collection->slice($offset, $perPage)->values();

                return response()->json([
                    'success' => true,
                    'data' => $data,
                    'meta' => [
                        'total' => $collection->count(),
                        'per_page' => $perPage,
                        'current_page' => $page,
                    ]
                ]);
            }

            // Para la vista blade, pasamos todos los posts (el frontend ya tiene JS para filtrar/paginar)
            $posts = $collection->all();
            return view('pages.blog', compact('posts'));
        } catch (\Throwable $e) {
            Log::error('Error en BlogController@index: ' . $e->getMessage());
            return $this->respondError('Ocurrió un error cargando el blog', 500, $request);
        }
    }

    /**
     * Mostrar artículo por slug (o id si se pasa numérico).
     * Si se solicita JSON devuelve el objeto; para HTML redirige a la lista (el sitio usa páginas estáticas).
     */
    public function show(Request $request, $slug)
    {
        try {
            $path = storage_path('app/data/blog.json');
            if (!file_exists($path)) {
                return $this->respondError('No se encontró el archivo de contenido del blog', 500, $request);
            }

            $items = json_decode(file_get_contents($path), true) ?: [];
            $collection = collect($items);

            $post = null;
            if (is_numeric($slug)) {
                $post = $collection->firstWhere('id', (int) $slug);
            } else {
                $post = $collection->firstWhere('slug', $slug);
            }

            if (!$post) {
                if ($request->wantsJson()) {
                    return response()->json(['success' => false, 'message' => 'Artículo no encontrado'], 404);
                }
                return redirect()->route('blog')->with('error', 'Artículo no encontrado');
            }

            if ($request->wantsJson()) {
                return response()->json(['success' => true, 'data' => $post]);
            }

            // Si no hay vista específica, redirigimos al blog (la plantilla puede abrir modal o JS para mostrar contenido)
            return view('pages.blog-post', compact('post'));
        } catch (\Throwable $e) {
            Log::error('Error en BlogController@show: ' . $e->getMessage(), ['slug' => $slug]);
            return $this->respondError('Ocurrió un error mostrando el artículo', 500, $request);
        }
    }

    /**
     * Respuesta de error consistente (JSON o redirect según petición)
     */
    protected function respondError(string $message, int $status, Request $request)
    {
        if ($request->wantsJson()) {
            return response()->json(['success' => false, 'message' => $message], $status);
        }
        return redirect()->route('blog')->with('error', $message);
    }
}
