<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class ContactFormMail extends Mailable
{
    use Queueable, SerializesModels;

    public $contactData;

    public $isUserConfirmation;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($contactData, $isUserConfirmation = false)
    {
        $this->contactData = $contactData;
        $this->isUserConfirmation = $isUserConfirmation;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        if ($this->isUserConfirmation) {
            return $this->subject('Confirmação de Recebimento - RCC System')
                ->markdown('emails.contact-confirmation')
                ->with([
                    'contactData' => $this->contactData,
                    'isUserConfirmation' => true,
                ]);
        }

        return $this->subject('Novo Contato via Site - RCC System')
            ->markdown('emails.contact-admin')
            ->with([
                'contactData' => $this->contactData,
                'isUserConfirmation' => false,
            ]);
    }
}
