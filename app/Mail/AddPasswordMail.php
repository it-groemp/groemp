<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class AddPasswordMail extends Mailable
{
    use Queueable, SerializesModels;

    /**
     * Create a new message instance.
     */
    public function __construct($name, $link)
    {
        $this->name = $name;
        $this->link = $link;
    }

    

    public function build()
    {
        return $this->subject('Add Password Link')->from(env("MAIL_USERNAME"),env("MAIL_FROM_NAME"))->markdown('mails.first-password-mail');
    }
}
