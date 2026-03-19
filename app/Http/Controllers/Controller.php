<?php
namespace App\Http\Controllers;

use Illuminate\Routing\Controller as BaseController;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

/**
 * Controlador base de la aplicación.
 *
 * Aquí se colocan utilidades compartidas por los controladores web/API:
 * - Respuestas JSON estandarizadas
 * - Detección de peticiones API/AJAX
 * - Helper para paginación JSON
 *
 * Comentarios y métodos en español para facilitar mantenimiento.
 */
class Controller extends BaseController
{
    /**
     * Determina si la petición debe responder como API/JSON.
     * Considera `Accept: application/json`, AJAX y rutas que comienzan con `api/`.
     */
    protected function isApiRequest(Request $request): bool
    {
        return $request->wantsJson() || $request->ajax() || $request->is('api/*');
    }

    /**
     * Respuesta JSON estándar para respuestas exitosas.
     * Devuelve estructura: { success: true, message, data }
     */
    protected function successResponse($data = null, string $message = '', int $status = 200): JsonResponse
    {
        $payload = ['success' => true];
        if ($message !== '') $payload['message'] = $message;
        if (!is_null($data)) $payload['data'] = $data;

        return response()->json($payload, $status);
    }

    /**
     * Respuesta JSON estándar para errores.
     * Devuelve estructura: { success: false, message, errors? }
     */
    protected function errorResponse(string $message = 'Error interno', int $status = 500, $errors = null): JsonResponse
    {
        $payload = ['success' => false, 'message' => $message];
        if (!is_null($errors)) $payload['errors'] = $errors;

        return response()->json($payload, $status);
    }

    /**
     * Formatea una paginación tipo LengthAwarePaginator para API.
     * Devuelve estructura con `data` y `meta` (total, per_page, current_page, last_page).
     */
    protected function paginatedResponse(LengthAwarePaginator $paginator): JsonResponse
    {
        $data = $paginator->items();
        $meta = [
            'total' => $paginator->total(),
            'per_page' => $paginator->perPage(),
            'current_page' => $paginator->currentPage(),
            'last_page' => $paginator->lastPage(),
        ];

        return $this->successResponse($data, '', 200)->setData(array_merge(json_decode($this->successResponse($data)->getContent(), true), ['meta' => $meta]));
    }
}
