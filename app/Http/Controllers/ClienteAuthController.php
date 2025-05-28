<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Cliente; // Asegúrate de importar el modelo correcto aquí

class ClienteAuthController extends Controller
{
    public function login(Request $request)
    {
        $request->validate([
            'login' => 'required|string',
            'password' => 'required',
        ]);

        $loginField = filter_var($request->login, FILTER_VALIDATE_EMAIL) ? 'email' : 'usuario';

        // Primero verificamos si las credenciales son correctas
        if (Auth::guard('cliente')->attempt([$loginField => $request->login, 'password' => $request->password])) {

            // Las credenciales son correctas, ahora verificamos si está autorizado
            $cliente = Auth::guard('cliente')->user();

            if (!$cliente->autorizado) {
                // El usuario no está autorizado
                Auth::guard('cliente')->logout(); // Lo deslogueamos

                $errorMessage = 'Tu cuenta aún no ha sido autorizada. Por favor, espera la aprobación del administrador.';

                // Check if this is an AJAX request
                if ($request->ajax() || $request->wantsJson()) {
                    return response()->json([
                        'success' => false,
                        'message' => $errorMessage
                    ]);
                }

                // Para peticiones no-AJAX
                return back()->with('loginError', $errorMessage);
            }

            // El usuario está autorizado y puede acceder
            // Check if this is an AJAX request
            if ($request->ajax() || $request->wantsJson()) {
                return response()->json([
                    'success' => true,
                    'redirect' => route('productos.zonaprivada')
                ]);
            }

            // Para peticiones no-AJAX
            return redirect()->intended(route('productos.zonaprivada'));
        }

        // Las credenciales son incorrectas
        $errorMessage = 'Credenciales incorrectas';

        if ($request->ajax() || $request->wantsJson()) {
            return response()->json([
                'success' => false,
                'message' => $errorMessage
            ]);
        }

        // Para peticiones no-AJAX
        return back()->with('loginError', $errorMessage);
    }

    public function logout(Request $request)
    {
        Auth::guard('cliente')->logout();

        // $request->session()->invalidate();
        // $request->session()->regenerateToken();

        return redirect('/')->with('success', 'Sesión cerrada correctamente.');
    }
}
