<?php

namespace App\mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;

class EnviarTokenPassword extends Mailable
{
    use Queueable, SerializesModels;

    private $name;
    private $token;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($name,$token)
    {
        $this->name= $name;
        $this->token= $token;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->from(env("MAIL_FROM_ADDRESS", "naoresponda@healthbook.com.br"), strtoupper(env("MAIL_FROM_NAME", "HEALTHBOOK")) )
            ->subject('Código de redefinição de senha HEALTHBOOK')
            ->markdown('mail.token_reset_password')
            ->with([
                'name' => $this->name,
                'token' => $this->token
            ]);
    }
}
