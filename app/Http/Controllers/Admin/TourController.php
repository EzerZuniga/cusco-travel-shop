<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreTourRequest;
use App\Http\Requests\UpdateTourRequest;
use App\Models\Tour;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;

class TourController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Listar todos los tours (con paginación)
     */
    public function index()
    {
        try {
            $tours = Tour::orderBy('created_at', 'desc')
                ->paginate(15);

            return view('admin.tours.index', compact('tours'));
        } catch (\Throwable $e) {
            Log::error('Error listando tours: ' . $e->getMessage());
            return redirect()->route('admin.dashboard')
                ->with('error', 'Error al cargar los tours');
        }
    }

    /**
     * Mostrar formulario de creación
     */
    public function create()
    {
        return view('admin.tours.create');
    }

    /**
     * Guardar nuevo tour
     */
    public function store(StoreTourRequest $request)
    {
        try {
            $validated = $request->validated();

            // Generar slug si no existe
            if (empty($validated['slug'] ?? null)) {
                $validated['slug'] = Str::slug($validated['titulo']);
            }

            // Asegurar que activo tenga un valor por defecto
            if (!isset($validated['activo'])) {
                $validated['activo'] = true;
            }

            Tour::create($validated);

            return redirect()->route('admin.tours.index')
                ->with('success', 'Tour creado correctamente');
        } catch (\Throwable $e) {
            Log::error('Error creando tour: ' . $e->getMessage());
            return back()->withInput()
                ->with('error', 'Error al crear el tour');
        }
    }

    /**
     * Mostrar detalle de un tour
     */
    public function show(Tour $tour)
    {
        $tour->loadCount('reservas');
        return view('admin.tours.show', compact('tour'));
    }

    /**
     * Mostrar formulario de edición
     */
    public function edit(Tour $tour)
    {
        return view('admin.tours.edit', compact('tour'));
    }

    /**
     * Actualizar tour
     */
    public function update(UpdateTourRequest $request, Tour $tour)
    {
        try {
            $validated = $request->validated();

            // Si no hay slug, generarlo del título
            if (empty($validated['slug'])) {
                $validated['slug'] = Str::slug($validated['titulo']);
            }

            $tour->update($validated);

            return redirect()->route('admin.tours.index')
                ->with('success', 'Tour actualizado correctamente');
        } catch (\Throwable $e) {
            Log::error('Error actualizando tour: ' . $e->getMessage());
            return back()->withInput()
                ->with('error', 'Error al actualizar el tour');
        }
    }

    /**
     * Eliminar tour
     */
    public function destroy(Tour $tour)
    {
        try {
            // Verificar si tiene reservas
            if ($tour->reservas()->count() > 0) {
                return redirect()->route('admin.tours.index')
                    ->with('error', 'No se puede eliminar un tour con reservas asociadas');
            }

            $tour->delete();

            return redirect()->route('admin.tours.index')
                ->with('success', 'Tour eliminado correctamente');
        } catch (\Throwable $e) {
            Log::error('Error eliminando tour: ' . $e->getMessage());
            return redirect()->route('admin.tours.index')
                ->with('error', 'Error al eliminar el tour');
        }
    }
}
