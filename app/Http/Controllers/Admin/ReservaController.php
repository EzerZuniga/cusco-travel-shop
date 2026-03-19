<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreReservaRequest;
use App\Http\Requests\UpdateReservaRequest;
use App\Models\Reserva;
use App\Models\Tour;
use App\Models\Usuario;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class ReservaController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Listar todas las reservas
     */
    public function index(Request $request)
    {
        try {
            $query = Reserva::with(['usuario', 'tour']);

            // Filtros
            if ($request->filled('estado')) {
                $query->where('estado', $request->estado);
            }

            if ($request->filled('usuario_id')) {
                $query->where('usuario_id', $request->usuario_id);
            }

            if ($request->filled('tour_id')) {
                $query->where('tour_id', $request->tour_id);
            }

            // Búsqueda por fecha
            if ($request->filled('fecha_desde')) {
                $query->whereDate('fecha', '>=', $request->fecha_desde);
            }

            if ($request->filled('fecha_hasta')) {
                $query->whereDate('fecha', '<=', $request->fecha_hasta);
            }

            $reservas = $query->orderBy('created_at', 'desc')
                ->paginate(15);

            // Para los filtros
            $tours = Tour::where('activo', true)->get();
            $usuarios = Usuario::orderBy('nombre')->get();

            return view('admin.reservas.index', compact('reservas', 'tours', 'usuarios'));
        } catch (\Throwable $e) {
            Log::error('Error listando reservas: ' . $e->getMessage());
            return redirect()->route('admin.dashboard')
                ->with('error', 'Error al cargar las reservas');
        }
    }

    /**
     * Mostrar detalle de una reserva
     */
    public function show(Reserva $reserva)
    {
        $reserva->load(['usuario', 'tour']);
        return view('admin.reservas.show', compact('reserva'));
    }

    /**
     * Mostrar formulario de creación
     */
    public function create()
    {
        $tours = Tour::where('activo', true)->get();
        $usuarios = Usuario::orderBy('nombre')->get();
        return view('admin.reservas.create', compact('tours', 'usuarios'));
    }

    /**
     * Guardar nueva reserva
     */
    public function store(StoreReservaRequest $request)
    {
        try {
            $validated = $request->validated();

            $tour = Tour::findOrFail($validated['tour_id']);
            $validated['total'] = $tour->precio * $validated['personas'];

            if (empty($validated['estado'])) {
                $validated['estado'] = Reserva::STATUS_PENDING;
            }

            Reserva::create($validated);

            return redirect()->route('admin.reservas.index')
                ->with('success', 'Reserva creada correctamente');
        } catch (\Throwable $e) {
            Log::error('Error creando reserva: ' . $e->getMessage());
            return back()->withInput()
                ->with('error', 'Error al crear la reserva');
        }
    }

    /**
     * Mostrar formulario de edición
     */
    public function edit(Reserva $reserva)
    {
        $reserva->load(['usuario', 'tour']);
        $tours = Tour::where('activo', true)->get();
        $usuarios = Usuario::orderBy('nombre')->get();
        return view('admin.reservas.edit', compact('reserva', 'tours', 'usuarios'));
    }

    /**
     * Actualizar reserva
     */
    public function update(UpdateReservaRequest $request, Reserva $reserva)
    {
        try {
            $validated = $request->validated();
            // Recalcular total si cambió el tour o número de personas
            if ($reserva->tour_id != $validated['tour_id'] || $reserva->personas != $validated['personas']) {
                $tour = Tour::findOrFail($validated['tour_id']);
                $validated['total'] = $tour->precio * $validated['personas'];
            }

            $reserva->update($validated);

            return redirect()->route('admin.reservas.index')
                ->with('success', 'Reserva actualizada correctamente');
        } catch (\Throwable $e) {
            Log::error('Error actualizando reserva: ' . $e->getMessage());
            return back()->withInput()
                ->with('error', 'Error al actualizar la reserva');
        }
    }

    /**
     * Eliminar reserva
     */
    public function destroy(Reserva $reserva)
    {
        try {
            $reserva->delete();

            return redirect()->route('admin.reservas.index')
                ->with('success', 'Reserva eliminada correctamente');
        } catch (\Throwable $e) {
            Log::error('Error eliminando reserva: ' . $e->getMessage());
            return redirect()->route('admin.reservas.index')
                ->with('error', 'Error al eliminar la reserva');
        }
    }
}
