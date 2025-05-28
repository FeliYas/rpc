<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Logo;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    public function showLoginForm()
    {
        $logo = Logo::where('seccion', 'login')->first();
        return view('auth.login', compact('logo'));
    }
    public function login(Request $request)
    {
        // Validar que el campo 'login' esté presente y que la contraseña sea obligatoria
        $request->validate([
            'login' => 'required|string',
            'password' => 'required',
        ]);

        // Verificar si el valor ingresado es un correo o un nombre de usuario
        $loginField = filter_var($request->login, FILTER_VALIDATE_EMAIL) ? 'email' : 'name';
        
        // Intentar autenticar al usuario utilizando el campo correcto
        if (Auth::attempt([$loginField => $request->login, 'password' => $request->password], $request->filled('remember'))) {
            $user = Auth::user(); // Obtener el usuario autenticado
        
            // Verificar si el usuario tiene el rol 'pedido'
            if ($user->role === 'pedido') {
                return redirect()->route('pedidos');
            }
        
            // Si no, redirigir al dashboard
            return redirect()->intended(route('dashboard'));
        }

// Si falla, redirigir de vuelta al login con un error
return back()->with(['loginError' => 'Credenciales incorrectas']);

    }

    public function logout(Request $request)
    {
        Auth::guard('web')->logout();

        // $request->session()->invalidate();
        // $request->session()->regenerateToken();

        return redirect()->route('login');
    }
    
}
