<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Storage;

class PedidoRealizado extends Mailable
{
    use Queueable, SerializesModels;

    public $pedido;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($pedido)
    {
        $this->pedido = $pedido;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        $mail = $this->subject('Nuevo pedido - ' . $this->pedido['cliente']->nombre)
            ->view('emails.pedido');

        // Adjuntar archivo si existe
        if (!empty($this->pedido['archivo']['path']) && Storage::disk('public')->exists($this->pedido['archivo']['path'])) {
            $mail->attach(
                Storage::disk('public')->path($this->pedido['archivo']['path']),
                [
                    'as' => $this->pedido['archivo']['nombre'] ?? 'archivo_adjunto.pdf',
                ]
            );
        }

        return $mail;
    }
}
