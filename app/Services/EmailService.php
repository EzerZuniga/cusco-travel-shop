<?php

namespace App\Services;

use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Log;

class EmailService
{
    /**
     * Enviar email de contacto
     *
     * @param array $data Espera claves: nombre, email, mensaje
     * @return bool True si el envío fue intentado con éxito
     */
    public function sendContact(array $data): bool
    {
        try {
            $nombre = $data['nombre'] ?? 'Sin nombre';
            $email = $data['email'] ?? config('mail.from.address');
            $mensaje = $data['mensaje'] ?? '';

            Mail::raw(
                "Nombre: {$nombre}\n" .
                "Email: {$email}\n" .
                "Mensaje: {$mensaje}",
                function ($message) use ($email, $nombre) {
                    $to = config('mail.from.address');
                    $message->to($to)
                        ->subject('Nuevo mensaje de contacto - Cusco Travel Shop')
                        ->from($email, $nombre);
                }
            );

            return true;
        } catch (\Exception $e) {
            Log::error('EmailService::sendContact fallo: ' . $e->getMessage(), [
                'data' => \Illuminate\Support\Arr::only($data, ['email', 'nombre'])
            ]);
            return false;
        }
    }

    /**
     * Enviar email de confirmación de reserva
     */
    public function sendReservationConfirmation(array $data): bool
    {
        try {
            Mail::raw(
                "Su reserva ha sido confirmada.\n\n" .
                "Tour: {$data['tour']}\n" .
                "Fecha: {$data['fecha']}\n" .
                "Personas: {$data['personas']}\n" .
                "Total: {$data['total']}",
                function ($message) use ($data) {
                    $to = $data['email'] ?? null;
                    if (!$to) {
                        throw new \InvalidArgumentException('Falta email en datos de reserva');
                    }
                    $message->to($to)
                        ->subject('Confirmación de reserva - Cusco Travel Shop');
                }
            );

            return true;
        } catch (\Exception $e) {
            Log::error('EmailService::sendReservationConfirmation fallo: ' . $e->getMessage(), ['data' => $data]);
            return false;
        }
    }

    /**
     * Método genérico para enviar emails
     */
    public function send(array $data): bool
    {
        try {
            $to = $data['to'] ?? null;
            if (!$to) {
                throw new \InvalidArgumentException('Parametro "to" requerido para enviar email');
            }

            Mail::raw($data['body'] ?? '', function ($message) use ($data, $to) {
                $message->to($to)
                    ->subject($data['subject'] ?? 'Mensaje desde Cusco Travel Shop');
                if (!empty($data['from'])) {
                    $message->from($data['from']);
                }
            });

            return true;
        } catch (\Exception $e) {
            Log::error('EmailService::send fallo: ' . $e->getMessage(), ['data' => $data]);
            return false;
        }
    }
}
