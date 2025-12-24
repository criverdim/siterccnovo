O usuário solicitou uma reformulação completa da área administrativa (/admin), focada em gestão de eventos e ingressos. Como o sistema utiliza **FilamentPHP**, as alterações devem seguir a estrutura desse framework.

### Estrutura do Plano

#### 1. Reformulação do Menu Lateral
**Objetivo:** Agrupar recursos relacionados a eventos sob um único menu "Gestão de Eventos".
*   **Ação:** Editar `EventResource.php` para definir `$navigationGroup = 'Gestão de Eventos'`.
*   **Ação:** As novas páginas criadas (Dashboard, Ingressos) também usarão este grupo.

#### 2. Criação do Dashboard de Eventos
**Objetivo:** Uma visão geral centralizada com KPIs e gráficos.
*   **Ação:** Criar uma nova Página Filament (`EventDashboard`).
*   **Widgets:**
    *   `EventStatsOverview`: Cards com Total Vendido (R$), Ingressos Vendidos, Check-ins Realizados.
    *   `EventSalesChart`: Gráfico de linha (Vendas por dia).
    *   `EventCheckinChart`: Gráfico de barra (Check-ins por hora/evento).
*   **Implementação:** Usar as classes de Widget do Filament (`StatsOverviewWidget`, `ChartWidget`).

#### 3. Gerenciamento de Ingressos (Resource)
**Objetivo:** Listagem completa e controle manual.
*   **Ação:** Criar um novo Resource `TicketResource` (baseado no model `EventParticipation`).
*   **Features:**
    *   Listagem com colunas: Evento, Usuário, Status (Pago/Pendente/Cancelado), Check-in (Sim/Não).
    *   Filtros: Por Evento, Por Status.
    *   Ações de Tabela:
        *   *Aprovar Pagamento Manualmente*: Muda status para `approved` e dispara geração de ingresso.
        *   *Estornar/Cancelar*: Integração com Mercado Pago (se possível) ou apenas cancelamento lógico no banco.
        *   *Reenviar Email*: Dispara `TicketService`.

#### 4. Scanner de Ingressos (QR Code)
**Objetivo:** Página otimizada para mobile para validar entrada via câmera.
*   **Ação:** Criar uma Página Filament Customizada (`TicketScanner`).
*   **Tecnologia:** Usar a biblioteca JS `html5-qrcode` dentro de uma View Blade customizada.
*   **Fluxo:**
    1.  Operador acessa a página no celular.
    2.  Câmera lê o QR Code (formato `TICKET:{UUID}`).
    3.  JS envia requisição AJAX para endpoint de validação.
    4.  Backend valida: Existe? É deste evento? Já foi usado?
    5.  Backend retorna Sucesso/Erro + Dados do Participante.
    6.  Frontend mostra modal Verde (OK) ou Vermelho (Erro) com som.

#### 5. Configuração WhatsApp
**Objetivo:** Permitir configurar envio de mensagens.
*   **Ação:** Verificar se já existe `SettingsWhatsApp.php` (visto na lista de arquivos) e garantir que tenha os campos necessários (API Key, URL) ou criar se necessário.

### Passos de Execução Imediata
1.  **Menu:** Ajustar `EventResource`.
2.  **Dashboard:** Criar a página e widgets básicos.
3.  **Tickets:** Criar `TicketResource` com as ações solicitadas.
4.  **Scanner:** Implementar a página de leitura de QR Code.
