<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class ApproverEmployeeBenefitsUpdateMail extends Mailable
{
    use Queueable, SerializesModels;

    /**
     * Create a new message instance.
     */
    public $link;

    public function __construct($link)
    {
        $this->link = $link;
    }    

    public function build()
    {
        return $this->subject('Approve Employee\'s Benefits')->from(env("MAIL_FROM_ADDRESS"),env("MAIL_FROM_NAME"))->markdown('mails.approver-employee-benefits-updated-mail.blade');
    }
}
