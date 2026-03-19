<?php
namespace App\Exceptions;

use Illuminate\Auth\AuthenticationException;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Illuminate\Validation\ValidationException;
use Symfony\Component\HttpKernel\Exception\HttpExceptionInterface;
use Throwable;

/**
 * Manejo centralizado de excepciones de la aplicación.
 *
 * Esta clase extiende el handler por defecto de Laravel pero añade
 * protecciones contra fallos en el logger y respuestas JSON amigables
 * para peticiones API/AJAX. Los comentarios están en español para
 * facilitar el mantenimiento.
 */
class Handler extends ExceptionHandler
{
    /**
     * Excepciones que no deben reportarse (ruido conocido / esperado).
     *
     * Puedes añadir aquí excepciones que no quieres que se registren
     * en el log (por ejemplo 404, validaciones, autenticación fallida).
     *
     * @var array<int, class-string<\Throwable>>
     */
    protected $dontReport = [
        AuthenticationException::class,
        ValidationException::class,
        ModelNotFoundException::class,
    ];

    /**
     * Niveles de log personalizados por tipo de excepción.
     * (Opcional: Laravel 8+ soporta el array $levels)
     *
     * @var array<string, \Psr\Log\LogLevel::*>
     */
    protected $levels = [
        // Ejemplo: \App\Exceptions\MiExcepcion::class => \Psr\Log\LogLevel::WARNING,
    ];

    /**
     * Registrar callbacks de manejo y reporte de excepciones.
     *
     * Aquí se pueden registrar closures que decidan cómo reportar
     * o renderizar excepciones concretas.
     */
    public function register(): void
    {
        // Reportable: permite ejecutar lógica adicional antes de reportar
        $this->reportable(function (Throwable $e) {
            // Por defecto no hacemos nada extra aquí.
        });

        // Renderable: ejemplo para excepciones específicas si se desea
        $this->renderable(function (Throwable $e, $request) {
            // Devolver JSON para peticiones AJAX/API
            if ($request->wantsJson() || $request->isJson() || $request->ajax()) {
                return $this->prepareJsonResponse($request, $e);
            }
            // Para otras peticiones, se usará el método render() por defecto
            return null;
        });
    }

    /**
     * Reporta la excepción usando el logger de Laravel.
     *
     * Este método envuelve la llamada padre en un try/catch para evitar
     * que un fallo en la configuración del logger bloquee la aplicación.
     *
     * @param  \Throwable  $e
     * @return void
     */
    public function report(Throwable $e): void
    {
        // Si la excepción está en dontReport, no hacemos nada
        if ($this->shouldntReport($e)) {
            return;
        }

        try {
            parent::report($e);
        } catch (Throwable $reportException) {
            // Fallback en caso de error al reportar (p. ej. logger mal configurado)
            try {
                // Intentar registrar en error_log de PHP como último recurso
                error_log('[ReportException] ' . $reportException->getMessage());
                error_log('[OriginalException] ' . $e->getMessage());
            } catch (Throwable $err) {
                // No hay más medidas posibles — no lanzamos excepciones adicionales
            }
        }
    }

    /**
     * Renderiza una excepción a una respuesta HTTP.
     *
     * Proporciona respuestas JSON estandarizadas para peticiones API/AJAX.
     * Para peticiones normales delega en la implementación por defecto de Laravel.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Throwable  $e
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function render($request, Throwable $e)
    {
        // Si es una petición que espera JSON, devolvemos una estructura consistente
        if ($request->wantsJson() || $request->isJson() || $request->ajax()) {
            return $this->prepareJsonResponse($request, $e);
        }

        // Para peticiones web normales, delegamos al handler base (muestra páginas de error)
        return parent::render($request, $e);
    }

    /**
     * Construye una respuesta JSON estandarizada para excepciones.
     *
     * Incluye el mensaje y el código HTTP cuando es posible. En entorno
     * de desarrollo (`APP_DEBUG=true`) puede incluir el stack trace.
     *
     * @param  mixed  $request
     * @param  \Throwable  $e
     * @return \Illuminate\Http\JsonResponse
     */
    protected function prepareJsonResponse($request, Throwable $e)
    {
        $status = 500;
        $message = 'Error interno del servidor';

        if ($e instanceof HttpExceptionInterface) {
            $status = $e->getStatusCode();
            $message = $e->getMessage() ?: \Symfony\Component\HttpFoundation\Response::$statusTexts[$status] ?? $message;
        } elseif ($e instanceof ModelNotFoundException) {
            $status = 404;
            $message = 'Registro no encontrado';
        } elseif ($e instanceof AuthenticationException) {
            $status = 401;
            $message = 'No autenticado';
        } elseif ($e instanceof ValidationException) {
            $status = 422;
            $message = 'Error de validación';
        }

        $payload = [
            'success' => false,
            'message' => $message,
            'code' => $status,
        ];

        // Incluir detalles en entorno de desarrollo para facilitar debugging
        if (config('app.debug')) {
            $payload['exception'] = get_class($e);
            $payload['trace'] = $e->getTrace();
            $payload['errors'] = method_exists($e, 'errors') ? $e->errors() : null;
        }

        return response()->json($payload, $status);
    }
}
