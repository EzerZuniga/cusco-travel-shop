<?php

namespace App\Services;

use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Log;

class EmailService
{
    /**
     * Enviar email de contacto
     */
    public function sendContact(array $data): bool
    {
        try {
            Mail::raw(
                "Nombre: {$data['nombre']}\n" .
                "Email: {$data['email']}\n" .
                "Mensaje: {$data['mensaje']}",
                function ($message) use ($data) {
                    $message->to(config('mail.from.address'))
                        ->subject('Nuevo mensaje de contacto - Cusco Travel Shop')
                        ->from($data['email'], $data['nombre']);
                }
            );

            return true;
        } catch (\Exception $e) {
            Log::error('Error al enviar email de contacto: ' . $e->getMessage());
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
                    $message->to($data['email'])
                        ->subject('Confirmación de reserva - Cusco Travel Shop');
                }
            );

            return true;
        } catch (\Exception $e) {
            Log::error('Error al enviar email de confirmación: ' . $e->getMessage());
            return false;
        }
    }

    /**
     * Método genérico para enviar emails
     */
    public function send(array $data): bool
    {
        try {
            Mail::raw($data['body'] ?? '', function ($message) use ($data) {
                $message->to($data['to'])
                    ->subject($data['subject'] ?? 'Mensaje desde Cusco Travel Shop');
            });

            return true;
        } catch (\Exception $e) {
            Log::error('Error al enviar email: ' . $e->getMessage());
            return false;
        }
    }
}
