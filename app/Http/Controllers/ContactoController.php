<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Mail\ContactoMail;
use App\Models\Contacto;
use App\Models\Logo;
use App\Models\Metadato;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Validator;

class ContactoController extends Controller
{
    public function show()
    {
        $contacto = Contacto::first();
        $mapa = $contacto->iframe;
        $metadatos = Metadato::where('seccion', 'contacto')->first();
        $logos = Logo::whereIn('seccion', ['navbar', 'footer'])->get();
        $contactos = Contacto::select('direccion', 'email', 'telefonouno', 'telefonodos')->get();
        return view('guest.contacto', compact('metadatos', 'mapa', 'logos', 'contactos'));
    }
    public function index()
    {
        $contacto = Contacto::first();
        $logo = Logo::where('seccion', 'footer')->first();
        $mapa = $contacto->iframe;
        return view('admin.contacto', compact('contacto', 'logo', 'mapa'));
    }

    public function update(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'direccion' => 'nullable',
            'telefonouno' => 'nullable',
            'telefonodos' => 'nullable',
            'email' => 'nullable|email|max:255',
            'iframe' => 'nullable|string',
        ]);
        if ($validator->fails()) {
            return $this->error_response($validator->messages()->first());
        }
        $contacto = Contacto::findOrFail($id);

        $contacto->update([
            'direccion' => $request->direccion,
            'telefonouno' => $request->telefonouno,
            'telefonodos' => $request->telefonodos,
            'email' => $request->email,
            'iframe' => $request->iframe,
        ]);


        return $this->success_response('Contacto actualizado exitosamente.');
    }

    public function enviar(Request $request)
    {
        // Validar los datos del formulario
        $validator = Validator::make($request->all(), [
            'nombre' => 'required|string|max:100',
            'apellido' => 'required|string|max:100',
            'telefono' => 'required|string|max:20',
            'empresa' => 'nullable|string|max:100',
            'mensaje' => 'required|string',
            'g-recaptcha-response' => 'required'
        ]);
        if ($validator->fails()) {
            return $this->error_response($validator->messages()->first());
        }

        // Verificar el token de reCAPTCHA
        $recaptcha = $this->verificarRecaptcha($request->input('g-recaptcha-response'));

        if (!$recaptcha['success']) {
            return $this->error_response('La verificación de seguridad ha fallado. Por favor, inténtalo de nuevo.');
        }

        // Si el score es muy bajo (posible bot), puedes rechazar la solicitud
        if ($recaptcha['score'] < 0.7) {
            return $this->error_response('La verificación de seguridad ha detectado actividad sospechosa. Por favor, inténtalo de nuevo más tarde.');
        }

        $contacto = Contacto::first()->email;



        if (!$contacto) {
            return redirect()->back()->with('error', 'No se encontró un contacto con el tipo "email".');
        }

        // Enviar el correo
        Mail::to($contacto)->send(new ContactoMail($validator->validated()));

        // Redireccionar con mensaje de éxito
        return redirect()->back()->with('success', 'Mensaje enviado correctamente. Nos pondremos en contacto a la brevedad.');
    }
    private function verificarRecaptcha($token)
    {
        $response = Http::asForm()->post('https://www.google.com/recaptcha/api/siteverify', [
            'secret' => env('RECAPTCHA_SECRET_KEY'),
            'response' => $token,
            'remoteip' => request()->ip()
        ]);

        return $response->json();
    }
}
