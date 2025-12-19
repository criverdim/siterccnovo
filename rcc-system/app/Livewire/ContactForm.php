<?php

namespace App\Livewire;

use App\Mail\ContactFormMail;
use Illuminate\Support\Facades\Mail;
use Livewire\Component;

class ContactForm extends Component
{
    public $name = '';

    public $email = '';

    public $phone = '';

    public $company = '';

    public $subject = '';

    public $message = '';

    public $privacy = false;

    public $success = false;

    public $error = '';

    protected $rules = [
        'name' => 'required|min:3|max:255',
        'email' => 'required|email|max:255',
        'phone' => 'nullable|string|max:20',
        'company' => 'nullable|string|max:255',
        'subject' => 'required|string|max:255',
        'message' => 'required|min:10|max:5000',
        'privacy' => 'required|accepted',
    ];

    protected $messages = [
        'name.required' => 'Por favor, informe seu nome.',
        'name.min' => 'Seu nome deve ter pelo menos 3 caracteres.',
        'email.required' => 'Por favor, informe seu e-mail.',
        'email.email' => 'Por favor, informe um e-mail válido.',
        'subject.required' => 'Por favor, selecione um assunto.',
        'message.required' => 'Por favor, escreva sua mensagem.',
        'message.min' => 'Sua mensagem deve ter pelo menos 10 caracteres.',
        'privacy.required' => 'Você deve aceitar a política de privacidade.',
    ];

    public function submit()
    {
        try {
            $this->validate();

            $contactData = [
                'name' => $this->name,
                'email' => $this->email,
                'phone' => $this->phone,
                'company' => $this->company,
                'subject' => $this->getSubjectLabel($this->subject),
                'message' => $this->message,
                'created_at' => now()->format('d/m/Y H:i:s'),
            ];

            Mail::to(config('mail.from.address', 'contato@rccsystem.com.br'))
                ->send(new ContactFormMail($contactData));

            Mail::to($this->email)
                ->send(new ContactFormMail($contactData, true));

            $this->resetForm();
            $this->success = true;
            $this->error = '';

            session()->flash('success', 'Sua mensagem foi enviada com sucesso! Entraremos em contato em breve.');

        } catch (\Exception $e) {
            $this->error = 'Erro ao enviar mensagem. Por favor, tente novamente.';
            session()->flash('error', $this->error);
        }
    }

    private function resetForm()
    {
        $this->name = '';
        $this->email = '';
        $this->phone = '';
        $this->company = '';
        $this->subject = '';
        $this->message = '';
        $this->privacy = false;
    }

    private function getSubjectLabel($subject)
    {
        $subjects = [
            'orcamento' => 'Solicitar Orçamento',
            'duvida' => 'Dúvida sobre Serviços',
            'parceria' => 'Proposta de Parceria',
            'suporte' => 'Suporte Técnico',
            'outro' => 'Outro',
        ];

        return $subjects[$subject] ?? $subject;
    }

    public function render()
    {
        return view('livewire.contact-form');
    }
}
