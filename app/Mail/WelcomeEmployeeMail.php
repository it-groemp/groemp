<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class WelcomeEmployeeMail extends Mailable
{
    use Queueable, SerializesModels;

    public $name;
    public $link;

    /**
     * Create a new message instance.
     */
    public function __construct($name, $link)
    {
        $this->name = $name;
        $this->link = $link;
    }

    public function build(){
        return $this->subject('Welcome to Groemp')
                    ->from(env("MAIL_FROM_ADDRESS"),env("MAIL_FROM_NAME"))
                    ->markdown('mails.welcome-employee-mail');
    }
}
