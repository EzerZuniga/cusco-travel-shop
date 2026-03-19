<?php
namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Usuario;
use App\Models\Tour;
use App\Models\Reserva;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class DashboardController extends Controller
{
    /**
     * Registrar middleware para proteger el acceso al panel.
     * Usamos `auth` por defecto; la autorización más fina (roles/permiso)
     * puede aplicarse mediante políticas o middleware adicional.
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index()
    {
        try {
            // Contadores principales
            $usuariosCount = Usuario::count();
            $toursCount = Tour::count();
            $reservasCount = Reserva::count();

            // Reservas recientes (cargar usuario y tour para mostrar en el dashboard)
            $latestReservas = Reserva::with(['usuario', 'tour'])
                ->orderByDesc('fecha')
                ->limit(10)
                ->get();

            // Ingresos aproximados últimos 30 días
            $incomeLast30 = Reserva::where('fecha', '>=', now()->subDays(30))
                ->sum('total');

            // Preparar datos para la vista
            $data = [
                'usuariosCount' => $usuariosCount,
                'toursCount' => $toursCount,
                'reservasCount' => $reservasCount,
                'latestReservas' => $latestReservas,
                'incomeLast30' => $incomeLast30,
            ];

            return view('admin.dashboard', $data);
        } catch (\Throwable $e) {
            // Registrar el error a logs y redirigir con mensaje amigable
            Log::error('Error cargando dashboard: ' . $e->getMessage(), [
                'exception' => $e,
                'user_id' => Auth::id(),
            ]);

            // En caso de petición AJAX devolver JSON con error
            if (request()->wantsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'No se pudo cargar el panel de control. Intente nuevamente.'
                ], 500);
            }

            return redirect()->back()->with('error', 'No se pudo cargar el panel de control.');
        }
    }
}
