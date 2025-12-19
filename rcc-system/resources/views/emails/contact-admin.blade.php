@component('mail::message')
# Novo Contato Recebido

**Nome:** {{ $contactData['name'] ?? '' }}

**E-mail:** {{ $contactData['email'] ?? '' }}

**Telefone:** {{ $contactData['phone'] ?? '—' }}

**Empresa:** {{ $contactData['company'] ?? '—' }}

**Assunto:** {{ $contactData['subject'] ?? '—' }}

**Mensagem:**

{{ $contactData['message'] ?? '' }}

Recebido em {{ $contactData['created_at'] ?? now()->format('d/m/Y H:i') }}.

@endcomponent
