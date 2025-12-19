@component('mail::message')
# Recebemos sua mensagem

Olá {{ $contactData['name'] ?? '' }},

Obrigado por entrar em contato com o RCC System.
Recebemos sua mensagem com o assunto **{{ $contactData['subject'] ?? '—' }}** e em breve nossa equipe retornará.

Resumo:

**Mensagem:**
{{ $contactData['message'] ?? '' }}

Se precisar complementar, responda este e-mail.

Atenciosamente,
RCC System

@endcomponent
