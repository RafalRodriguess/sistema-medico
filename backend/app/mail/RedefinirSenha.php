<?php

namespace App\mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;

class RedefinirSenha extends Mailable
{
    use Queueable, SerializesModels;

    private $name;
    private $link;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($name,$link)
    {
        $this->name= $name;
        $this->link= $link;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->from(env("MAIL_FROM_ADDRESS", "naoresponda@healthbook.com.br"), env("MAIL_FROM_NAME", "HEALTHBOOK"))
            ->subject('Redefinição de senha HEALTHBOOK')
            ->markdown('mail.reset_password')
            ->with([
                'name' => $this->name,
                'link' => $this->link
            ]);
    }
}
