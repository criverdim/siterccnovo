🎯 Objetivo Geral

Criar um Sistema Web completo usando Laravel + MySQL, com:

Site público moderno e extremamente bonito

Página inicial com carrossel de fotos, eventos e links importantes

Página de Grupos de Oração

Sistema de Cadastro Único

Sistema de Eventos Gratuitos e Pagos

Integração completa com Mercado Pago (PIX + Cartão)

Geração de Ingressos (PDF, QR Code)

Painel Administrativo com Filament

Sistema completo de Pastoreio (presenças, histórico, dashboards, ranking)

Integração com WhatsApp Business API

Relatórios, estatísticas e sorteios

Segurança moderada (sem exagerar complexidade)

O foco é excelência visual, organização lógica, simplicidade operacional e fluidez no uso diário.

🛠️ Tecnologias Obrigatórias

Backend: Laravel 11

Frontend: Blade + Tailwind (ou Livewire onde necessário)

Admin: FilamentPHP

Banco de Dados: MySQL ou MariaDB

Pagamentos: Mercado Pago (PIX & Cartão)

Mensageria: WhatsApp Business API

Deploy Futuro: Hostinger (compatível)

🎨 Design e Layout (Prioridade Máxima)

Interface extremamente bonita, clean, moderna e responsiva

Paleta baseada em verde + neutros + dourado

Ícones modernos: Font Awesome ou Material Icons

Carrosséis de imagens em alta qualidade

Home com forte apelo visual para evangelizar e convidar

Uso amplo de componentes visuais para clareza da navegação

🌐 Páginas Públicas
1. Página Inicial (Home)

A Home deve mostrar:

Carrossel principal com imagens de eventos anteriores

Lista de próximos eventos com destaque

Logo do Grupo de Oração

Link para Cadastro

Link para Área do Usuário

Link para Calendário

Link para página de Grupos de Oração

Ícones das Redes Sociais

Rodapé com:

Endereço

Telefone

WhatsApp

Redes sociais

2. Página de Grupos de Oração

Listar todos os grupos cadastrados pelo admin

Cada grupo deve exibir:

Nome

Carrossel automático de fotos

Dia da semana

Horário

Local completo

Botão “Quero participar” → leva ao cadastro

Esta página deve ser acessível por link na home

Totalmente gerenciada pelo painel admin

👤 Cadastro Único de Usuário

Usado para tudo: eventos, presença, WhatsApp, ingressos.

Campos Obrigatórios

Nome completo

Email

Telefone

WhatsApp

Data de nascimento

Endereço completo (CEP, rua, número, complemento opcional, bairro, cidade, estado)

CPF (validado, mas não obrigatório)

Grupo de oração que participa (lista do admin)

Gênero (opcional)

Senha

Consentimento LGPD

Campos adicionais apenas se necessário (Progressive Profiling)

Dados exigidos pelo Mercado Pago quando for pagar com cartão

Complementos de endereço

CPF se for exigência do método de pagamento

Se usuário tentar participar de um evento:

Busca por CPF, nome, email ou telefone

Se existir → pedir apenas dados faltantes

Se não existir → cadastrar automaticamente

Se evento for pago → seguir para pagamento

Ingresso gerado apenas após pagamento aprovado

🎫 Eventos
Criados no Painel Admin

Com os campos:

Nome

Descrição rica

Fotos

Local

Dia início / fim

Hora início / fim

Pago ou gratuito

Valor

Se terá café, almoço etc

Se gera ingresso

Se permite pagamento online

Capacidade

Se aparece na página inicial

Página do Evento

Mostrar todas informações em layout moderno e convidativo.

Botão Participar

Acessa fluxo do Cadastro Único

Verifica duplicidade

Solicita só o que falta

Se pago → Mercado Pago

💳 Pagamento via Mercado Pago

Integração completa via API.

Credenciais configuráveis no admin

Access Token (Produção)

Public Key (Produção)

Access Token (Sandbox)

Public Key (Sandbox)

Modo atual: Sandbox / Produção

URL de Webhook

Opções habilitadas: PIX, Cartão, Boleto

Campos necessários (Cartão)

O sistema deve solicitar automaticamente quando necessário:

Número do cartão

Nome impresso

Validade

CVV

CPF

Email

Telefone

Data de nascimento

Endereço completo (exigido pelo Mercado Pago)

Campos necessários (PIX)

Nome

CPF

Email

Telefone

📡 Webhook Mercado Pago

Criar endpoint que receba notificações:

approved

pending

rejected

cancelled

refunded

expired (PIX)

Regras:

Webhook deve ser idempotente

Registro completo no banco

Ao receber “approved”:

Atualizar participação

Gerar ingresso (PDF + QR)

Enviar por email e WhatsApp

🎟️ Ingressos

Ao ser aprovado:

Gerar PDF em alta qualidade

Incluir QR Code único

Registrar hash único no banco (anti fraude)

Enviar para email e WhatsApp

Disponível na área do usuário

🤝 Ministérios RCC

No painel admin:

Ministérios padrão já cadastrados

CRUD para adicionar novos

Usuário ao marcar que é “servo”, deve escolher um ou mais ministérios

Cadastro deve refletir automaticamente novas opções criadas pelo admin

🙋‍♂️ Pastoreio (Módulo Completo)

Página restrita a usuários autorizados.

Funcionalidade principal

Registrar presença na porta do grupo de oração.

Fluxo

Buscar por nome, CPF, telefone

Se não existir → cadastro rápido

Registrar presença para o dia

Gravar histórico completo

Exibir dashboard individual

Dashboard Individual (Fiel)

Linha do tempo completa

Percentual de presença

Gráfico de barras

Faltas consecutivas

Histórico por meses

Ranking dentro do grupo

Botão “Enviar mensagem no WhatsApp”

Dashboard Geral (Pastoreio)

Deve conter:

Indicadores principais

Média de presença geral

Percentual dos últimos 30/60/90 dias

Ranking dos que mais participam

Fieis em risco (pouca frequência)

Novos participantes

Fidelidade mensal

Gráficos

Pizza: presença geral

Linha: evolução ao longo dos meses

Barras: ranking dos mais presentes

Heatmap: presenças por dia da semana

🎰 Sorteio Automático

Na página do grupo:

Selecionar data

O sistema pega todas presenças daquele dia

Executa sorteio aleatório

Salva no banco:

user_id

group_id

date

rng_seed

prêmio (opcional)

📲 Integração WhatsApp Business API

Enviar mensagens automáticas:

Confirmação de inscrição

Envio de ingresso

Lembrete de evento

Ausência consecutiva

Boas-vindas

Comunicação pastoral

Registrar no banco:

mensagem

payload

status de entrega

delivered_at

🧰 Painel Administrativo (Filament)

Deve conter:

Dashboard geral

CRUD de usuários

Ferramenta para detectar e unir duplicados

CRUD de grupos de oração

CRUD de ministérios

CRUD de eventos

CRUD de presenças

CRUD de sorteios

Relatórios e exportação Excel/CSV

Logs de WhatsApp

Logs de Mercado Pago

Editor de páginas estáticas (opcional)

Configurações gerais

Configurações de Mercado Pago

🗄️ Banco de Dados – Campos Requeridos
Tabela users

name

email

phone

whatsapp

birth_date

cpf (nullable)

cep

address

number

complement

district

city

state

gender

group_id

is_servo

ministerio_id (múltiplos)

profile_completed_at

consent_at

status

Tabela event_participations

user_id

event_id

payment_status

payment_method

mp_payment_id

mp_payload_raw

ticket_uuid

ticket_qr_hash

checked_in_at

Tabela groups

name

description

weekday

time

address

photos

Tabela group_attendance

user_id

group_id

date

created_by

source

Tabela group_draws

user_id

group_id

date

rng_seed

prize

Tabela wa_messages

user_id

message

payload

status

📈 MVP + Evolução Natural
MVP

Cadastro básico

Eventos básicos

Pastoreio simples

Grupos

Inscrição gratuita

Versão 1

Mercado Pago

Ingressos PDF

WhatsApp

Pastoreio completo

Versão 2

Dashboard avançada

Sorteios

Relatórios

Estatísticas avançadas

📌 Rotas Principais
Público

GET /

GET /events

GET /events/{id}

GET /groups

GET /groups/{id}

Usuário

POST /register

POST /events/{id}/participate

POST /checkout

POST /webhooks/mercadopago

Pastoreio

GET /pastoreio

POST /pastoreio/search

POST /pastoreio/attendance

POST /pastoreio/draw