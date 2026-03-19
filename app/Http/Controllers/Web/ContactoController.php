<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Http\Requests\ContactRequest;
use App\Services\EmailService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Http\JsonResponse;

class ContactoController extends Controller
{
    /**
     * Servicio de email inyectado.
     */
    protected EmailService $emailService;

    public function __construct(EmailService $emailService)
    {
        // Inyectar el servicio y aplicar un limitador sencillo por middleware
        $this->emailService = $emailService;
        $this->middleware('throttle:6,1')->only('send');
    }

    /**
     * Mostrar formulario de contacto.
     */
    public function index()
    {
        return view('contacto');
    }

    /**
     * Procesar el envío del formulario de contacto.
     * - Valida los campos
     * - Protege con honeypot y rate limiter por IP
     * - Usa EmailService para enviar el mensaje
     * - Devuelve JSON si la petición es AJAX, o redirect con flash messages
     */
    public function send(ContactRequest $request)
    {
        // Validación ya realizada por el Request
        $validated = $request->validated();
        
        // Comprobar honeypot: si está rellenado, registrar y terminar silenciosamente
        // El campo 'hp' no está en las reglas de validación, así que lo obtenemos directamente
        if (!empty($request->input('hp'))) {
            Log::warning('Contacto: detectado honeypot (spam)', ['ip' => $request->ip(), 'input' => $request->except('mensaje')]);
            if ($request->wantsJson() || $request->ajax()) {
                return response()->json(['success' => true]);
            }
            return redirect()->route('contacto.index')->with('success', 'Mensaje enviado correctamente');
        }

        // Limitador por IP (clave personalizada)
        $key = 'contact|' . $request->ip();
        $maxAttempts = 6;
        if (RateLimiter::tooManyAttempts($key, $maxAttempts)) {
            $retry = RateLimiter::availableIn($key);
            $message = 'Demasiados intentos. Intenta de nuevo en ' . $retry . ' segundos.';
            if ($request->wantsJson() || $request->ajax()) {
                return response()->json(['success' => false, 'message' => $message], 429);
            }
            return redirect()->back()->with('error', $message);
        }

        // Marcar intento (caduca en 60 segundos)
        RateLimiter::hit($key, 60);

        // Preparar datos para el servicio de email
        $payload = [
            'nombre' => $validated['nombre'],
            'email' => $validated['email'],
            'telefono' => $validated['telefono'] ?? null,
            'mensaje' => $validated['mensaje'],
        ];

        try {
            $sent = $this->emailService->sendContact($payload);

            if ($sent) {
                // Si se envía correctamente, limpiar contador de intentos
                RateLimiter::clear($key);
                Log::info('Contacto: mensaje enviado', ['email' => $payload['email'], 'ip' => $request->ip()]);

                if ($request->wantsJson() || $request->ajax()) {
                    return response()->json(['success' => true, 'message' => 'Mensaje enviado correctamente'], 201);
                }

                return redirect()->route('contacto.index')->with('success', 'Mensaje enviado correctamente');
            }

            // Si el servicio devolvió false, registramos y respondemos error
            Log::error('Contacto: EmailService devolvió false', ['email' => $payload['email']]);
            if ($request->wantsJson() || $request->ajax()) {
                return response()->json(['success' => false, 'message' => 'Error al enviar el mensaje'], 500);
            }
            return redirect()->back()->withInput()->with('error', 'Error al enviar el mensaje. Por favor, intente nuevamente.');
        } catch (\Throwable $e) {
            Log::error('Contacto: excepción al enviar mensaje - ' . $e->getMessage(), ['exception' => $e]);
            if ($request->wantsJson() || $request->ajax()) {
                return response()->json(['success' => false, 'message' => 'Ocurrió un error interno'], 500);
            }
            return redirect()->back()->withInput()->with('error', 'Ocurrió un error interno. Intente más tarde.');
        }
    }
}
