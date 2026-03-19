<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Usuario;
use Illuminate\Http\Request;

class UsuarioController extends Controller
{

    public function index()
    {
        $usuarios = Usuario::withCount('reservas')
            ->orderBy('created_at', 'desc')
            ->paginate(15);

        return view('admin.usuarios', compact('usuarios'));
    }

    public function show(int $id)
    {
        $usuario = Usuario::with('reservas.tour')->findOrFail($id);
        return view('admin.usuario.show', compact('usuario'));
    }

    public function update(Request $request, int $id)
    {
        $usuario = Usuario::findOrFail($id);

        $validated = $request->validate([
            'nombre' => 'sometimes|required|string|max:255',
            'email' => 'sometimes|required|email|unique:usuarios,email,' . $id,
            'telefono' => 'nullable|string|max:20',
            'is_admin' => 'sometimes|boolean',
            'rol' => 'nullable|string|max:50',
        ]);

        $usuario->update($validated);

        return redirect()->route('admin.usuarios.index')
            ->with('success', 'Usuario actualizado correctamente');
    }

    public function destroy(int $id)
    {
        $usuario = Usuario::findOrFail($id);
        $usuario->delete();

        return redirect()->route('admin.usuarios.index')
            ->with('success', 'Usuario eliminado correctamente');
    }
}
