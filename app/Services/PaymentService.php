<?php

namespace App\Services;

use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Http;

use Illuminate\Support\Arr;

/**
 * Servicio responsable de la integración con pasarelas de pago.
 *
 * Actualmente soporta una pasarela "mock" para desarrollo. Para
 * integrar una pasarela real (Stripe, PayPal, Culqi, etc.) añadir
 * la lógica en los métodos privados correspondientes y configurar
 * las credenciales en `config/services.php` o variables de entorno.
 */
class PaymentService
{
    /**
     * Procesar un pago
     * 
     * @param array $data Datos del pago (amount, currency, payment_method, etc.)
     * @return array Resultado del procesamiento
     */
    public function processPayment(array $data): array
    {
        try {
            // Validación básica de entrada
            $amount = Arr::get($data, 'amount');
            $currency = Arr::get($data, 'currency', 'PEN');
            if (empty($amount) || !is_numeric($amount)) {
                return [
                    'success' => false,
                    'message' => 'El campo "amount" es requerido y debe ser numérico.'
                ];
            }

            $paymentMethod = Arr::get($data, 'payment_method', 'card');
            $description = Arr::get($data, 'description', 'Pago de reserva');

            $paymentData = compact('amount', 'currency', 'paymentMethod', 'description');

            // Seleccionar pasarela desde configuración (env PAYMENT_GATEWAY)
            $gateway = config('services.payment.gateway', env('PAYMENT_GATEWAY', 'mock'));

            Log::info('PaymentService: iniciando pago', ['gateway' => $gateway, 'data' => $paymentData]);

            if ($gateway === 'mock') {
                // Simulación segura para entorno de desarrollo / pruebas
                $transactionId = 'MOCK-TXN-' . uniqid();
                return [
                    'success' => true,
                    'message' => 'Pago simulado exitosamente (mock)',
                    'transaction_id' => $transactionId,
                    'amount' => (float)$amount,
                    'currency' => $currency,
                    'raw' => ['mock' => true]
                ];
            }

            // Ejemplo placeholder para Stripe (no funcional sin credenciales)
            if ($gateway === 'stripe') {
                $secret = config('services.stripe.secret');
                if (empty($secret)) {
                    return [
                        'success' => false,
                        'message' => 'Stripe no configurado.'
                    ];
                }

                // Preparar payload según la API del proveedor (placeholder)
                $payload = [
                    'amount' => (int)round($amount * 100), // en centavos
                    'currency' => strtolower($currency),
                    'description' => $description,
                    'payment_method' => $paymentMethod,
                ];

                // Este bloque es ilustrativo: adaptar según la API real
                $resp = Http::withToken($secret)
                    ->asForm()
                    ->post('https://api.stripe.com/v1/payment_intents', $payload);

                if ($resp->successful()) {
                    $body = $resp->json();
                    return [
                        'success' => true,
                        'message' => 'Pago procesado (stripe)',
                        'transaction_id' => $body['id'] ?? ('STRIPE-' . uniqid()),
                        'amount' => $amount,
                        'currency' => $currency,
                        'raw' => $body,
                    ];
                }

                Log::warning('PaymentService: respuesta no exitosa de Stripe', ['status' => $resp->status(), 'body' => $resp->body()]);
                return [
                    'success' => false,
                    'message' => 'Error al procesar el pago con Stripe',
                    'raw' => ['status' => $resp->status(), 'body' => $resp->body()]
                ];
            }

            // Si la pasarela no está implementada, informar al dev
            Log::error('PaymentService: pasarela desconocida', ['gateway' => $gateway]);
            return [
                'success' => false,
                'message' => 'Pasarela de pago no soportada: ' . $gateway
            ];

        } catch (\Exception $e) {
            Log::error('Error al procesar pago: ' . $e->getMessage());
            return [
                'success' => false,
                'message' => 'Error al procesar el pago: ' . $e->getMessage(),
                'exception' => $e->getMessage()
            ];
        }
    }

    /**
     * Verificar el estado de un pago
     */
    public function verifyPayment(string $transactionId): array
    {
        try {
            // Verificación básica: Si usamos mock, devolvemos estado simulado
            $gateway = config('services.payment.gateway', env('PAYMENT_GATEWAY', 'mock'));
            Log::info('PaymentService: verificando pago', ['gateway' => $gateway, 'transaction_id' => $transactionId]);

            if ($gateway === 'mock') {
                return [
                    'success' => true,
                    'status' => 'completed',
                    'transaction_id' => $transactionId,
                    'raw' => ['mock' => true]
                ];
            }

            if ($gateway === 'stripe') {
                $secret = config('services.stripe.secret');
                if (empty($secret)) {
                    return ['success' => false, 'message' => 'Stripe no configurado.'];
                }
                // Placeholder: llamar API de stripe para obtener estado
                $resp = Http::withToken($secret)->get('https://api.stripe.com/v1/payment_intents/' . $transactionId);
                if ($resp->successful()) {
                    $body = $resp->json();
                    return [
                        'success' => true,
                        'status' => $body['status'] ?? 'unknown',
                        'transaction_id' => $transactionId,
                        'raw' => $body,
                    ];
                }
                return ['success' => false, 'message' => 'Error al verificar en Stripe', 'raw' => $resp->body()];
            }

            return ['success' => false, 'message' => 'Pasarela no soportada: ' . $gateway];
        } catch (\Exception $e) {
            Log::error('Error al verificar pago: ' . $e->getMessage());
            
            return [
                'success' => false,
                'message' => 'Error al verificar el pago'
            ];
        }
    }

    /**
     * Reembolsar un pago
     */
    public function refundPayment(string $transactionId, ?float $amount = null): array
    {
        try {
            $gateway = config('services.payment.gateway', env('PAYMENT_GATEWAY', 'mock'));
            Log::info('PaymentService: solicitando reembolso', ['gateway' => $gateway, 'transaction_id' => $transactionId, 'amount' => $amount]);

            if ($gateway === 'mock') {
                return [
                    'success' => true,
                    'message' => 'Reembolso simulado exitosamente (mock)',
                    'refund_id' => 'MOCK-REF-' . uniqid(),
                    'amount' => $amount
                ];
            }

            if ($gateway === 'stripe') {
                $secret = config('services.stripe.secret');
                if (empty($secret)) {
                    return ['success' => false, 'message' => 'Stripe no configurado.'];
                }
                // Placeholder: implementar la llamada real a la API de reembolsos
                $resp = Http::withToken($secret)->post('https://api.stripe.com/v1/refunds', [
                    'charge' => $transactionId,
                    'amount' => $amount ? (int)round($amount * 100) : null,
                ]);

                if ($resp->successful()) {
                    $body = $resp->json();
                    return ['success' => true, 'message' => 'Reembolso creado (stripe)', 'refund_id' => $body['id'] ?? ('REF-' . uniqid()), 'raw' => $body];
                }
                return ['success' => false, 'message' => 'Error al crear reembolso en Stripe', 'raw' => $resp->body()];
            }

            return ['success' => false, 'message' => 'Pasarela no soportada: ' . $gateway];
        } catch (\Exception $e) {
            Log::error('Error al procesar reembolso: ' . $e->getMessage());
            
            return [
                'success' => false,
                'message' => 'Error al procesar el reembolso'
            ];
        }
    }
}
