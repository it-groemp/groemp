<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class UpdatePasswordAdminMail extends Mailable
{
    use Queueable, SerializesModels;

    public $name;

    public function __construct($name)
    {
        $this->name = $name;
    }    

    public function build()
    {
        return $this->subject("Password Updated")->from(env("MAIL_FROM_ADDRESS"),env("MAIL_FROM_NAME"))->markdown("mails.change-password-admin-mail");
    }
}
