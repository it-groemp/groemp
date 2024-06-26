<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class ApproverCompanyBenefitsMail extends Mailable
{
    use Queueable, SerializesModels;

    /**
     * Create a new message instance.
     */
    public $link;
    public $function;

    public function __construct($link, $function)
    {
        $this->link = $link;
        $this->function = $function;
    }    

    public function build()
    {
        return $this->subject("Approve Selected Company Benefits")->from(env("MAIL_FROM_ADDRESS"),env("MAIL_FROM_NAME"))->markdown("mails.approver-company-benefits");
    }
}
