<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class ContactoController extends Controller
{
    public function index()
    {
        return view('contacto');
    }

    public function send(Request $request)
    {
        $request->validate([
            'nombre' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'mensaje' => 'required|string|max:1000',
        ]);

        // Enviar email de contacto
        $emailService = new \App\Services\EmailService();
        $emailSent = $emailService->sendContact($request->all());

        if ($emailSent) {
            return redirect()->route('contacto.index')
                ->with('success', 'Mensaje enviado correctamente');
        }

        return redirect()->route('contacto.index')
            ->with('error', 'Error al enviar el mensaje. Por favor, intente nuevamente.');
    }
}
