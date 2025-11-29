<?php

namespace App\Mail;

use App\Models\EventParticipation;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class TicketMailable extends Mailable
{
    use Queueable, SerializesModels;

    public function __construct(public EventParticipation $participation, public string $pdfPath) {}

    public function build()
    {
        return $this->subject('Seu ingresso')
            ->view('emails.ticket')
            ->attach($this->pdfPath, ['as' => 'ingresso.pdf', 'mime' => 'application/pdf']);
    }
}
