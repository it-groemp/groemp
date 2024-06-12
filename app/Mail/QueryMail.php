<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class QueryMail extends Mailable
{
    use Queueable, SerializesModels;

    /**
     * Create a new message instance.
     */
    public $name;
    public $email;
    public $query;

    public function __construct($name, $email, $query)
    {
        $this->name = $name;
        $this->email = $email;
        $this->query = $query;
    }
    
    public function build()
    {
        return $this->subject('Query from Customer')->cc($this->email)->from(config("mail.from.address"),$this->name)->markdown('mails.query-mail');
    }
}
