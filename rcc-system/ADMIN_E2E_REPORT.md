# Relatório E2E Playwright

Data: 2025-12-15 16:43:29

## Resumo por Projeto
- admin-chromium: ✅ 20 • ❌ 11 • ⏭️ 2
- admin-firefox: ✅ 20 • ❌ 11 • ⏭️ 2
- site-chromium: ✅ 5 • ❌ 7 • ⏭️ 0
- site-firefox: ✅ 5 • ❌ 7 • ⏭️ 0

## Detalhes dos Testes

### admin-chromium
- FAILED • cria usuário básico (admin-crud-basic.spec.ts) • 36299ms
  - Erro: TimeoutError: page.fill: Timeout 30000ms exceeded.
Call log:
[2m  - waiting for locator('input[name="name"]')[22m

- FAILED • cria grupo de oração básico (admin-crud-basic.spec.ts) • 34870ms
  - Erro: TimeoutError: page.fill: Timeout 30000ms exceeded.
Call log:
[2m  - waiting for locator('input[name="name"]')[22m

- FAILED • cria evento básico (admin-crud-basic.spec.ts) • 35216ms
  - Erro: TimeoutError: page.fill: Timeout 30000ms exceeded.
Call log:
[2m  - waiting for locator('input[name="name"]')[22m

- PASSED • theme CSS and sticky sidebar (admin-layout.spec.ts) • 1432ms
- PASSED • navigation groups present (admin-layout.spec.ts) • 1223ms
- FAILED • admin-user-cards redesign basics (admin-layout.spec.ts) • 8098ms
  - Erro: Error: [2mexpect([22m[31mlocator[39m[2m).[22mtoBeVisible[2m([22m[2m)[22m failed

Locator: locator('.uc-card').first().locator('button.btn-details')
Expected: visible
Timeout: 5000ms
Error: element(s) not found

Call log:
[2m  - Expect "toBeVisible" with timeout 5000ms[22m
[2m  - waiting for locator('.uc-card').first().locator('button.btn-details')[22m

- PASSED • admin-user-cards basic responsiveness (admin-layout.spec.ts) • 2682ms
- PASSED • dashboard carrega em tempo aceitável (admin-performance.spec.ts) • 1965ms
- PASSED • bloqueia acesso não autenticado (admin-permissions-access.spec.ts) • 2620ms
- PASSED • permite acesso com credenciais válidas (admin-permissions-access.spec.ts) • 2055ms
- PASSED • sidebar visível em desktop e acessível em mobile (admin-responsiveness.spec.ts) • 2236ms
- FAILED • set role to admin and verify access to Pastoreio pages (admin-roles.spec.ts) • 19152ms
  - Erro: TimeoutError: page.waitForSelector: Timeout 15000ms exceeded.
Call log:
[2m  - waiting for locator('table') to be visible[22m

- PASSED • basic security: sidebar visible after auth (admin-security.spec.ts) • 2165ms
- FAILED • busca segura contra strings de injeção (admin-security.spec.ts) • 35482ms
  - Erro: TimeoutError: page.fill: Timeout 30000ms exceeded.
Call log:
[2m  - waiting for locator('input[type="search"], input[placeholder*="Buscar"], input[placeholder*="Search"]')[22m
[2m    - locator resolved to 2 elements. Proceeding with the first one: <input type="search" role="textbox" autocomplete="off" spellcheck="false" aria-label="Todos" name="search_terms" autocapitalize="off" aria-autocomplete="list" class="choices__input choices__input--cloned" placeholder="Comece a digitar para pesquisar..."/>[22m
[2m    - fill("1 OR 1=1 -- 1765816298835")[22m
[2m  - attempting fill action[22m
[2m    2 × waiting for element to be visible, enabled and editable[22m
[2m      - element is not visible[22m
[2m    - retrying fill action[22m
[2m    - waiting 20ms[22m
[2m    2 × waiting for element to be visible, enabled and editable[22m
[2m      - element is not visible[22m
[2m    - retrying fill action[22m
[2m      - waiting 100ms[22m
[2m    60 × waiting for element to be visible, enabled and editable[22m
[2m       - element is not visible[22m
[2m     - retrying fill action[22m
[2m       - waiting 500ms[22m

- PASSED • campos de texto não executam scripts (admin-security.spec.ts) • 2429ms
- PASSED • loads and shows header actions (Chrome) (admin-settings.spec.ts) • 3360ms
- FAILED • responsiveness and basic interactions (Firefox) (admin-settings.spec.ts) • 3371ms
  - Erro: Error: [2mexpect([22m[31mreceived[39m[2m).[22mtoBeTruthy[2m()[22m

Received: [31mfalse[39m
- PASSED • API integration smoke: create a brand setting (admin-settings.spec.ts) • 35411ms
- FAILED • sidebar is fixed and nav links hover smoothly (admin-sidebar.spec.ts) • 10450ms
  - Erro: Error: [2mexpect([22m[31mlocator[39m[2m).[22mtoBeVisible[2m([22m[2m)[22m failed

Locator: locator('.fi-sidebar a.fi-sidebar-item-button').nth(1)
Expected: visible
Timeout: 5000ms
Error: element(s) not found

Call log:
[2m  - Expect "toBeVisible" with timeout 5000ms[22m
[2m  - waiting for locator('.fi-sidebar a.fi-sidebar-item-button').nth(1)[22m

- PASSED • allows free vertical cropping without aspect lock (logo-editor.spec.ts) • 1776ms
- PASSED • drag handles allow diagonal and vertical resize (logo-editor.spec.ts) • 1267ms
- PASSED • header logo renders and has correct size (logo.spec.ts) • 815ms
- PASSED • home hero shows logo section when configured (logo.spec.ts) • 808ms
- PASSED • responsive sizes across breakpoints (logo.spec.ts) • 1166ms
- PASSED • logo mounts consistently on all pages (logo.spec.ts) • 1645ms
- PASSED • open pages and check components render (pastoreio-presenca.spec.ts) • 2875ms
- FAILED • generate receipt from Payment Logs (payment-receipt.spec.ts) • 55540ms
  - Erro: Error: [2mexpect([22m[31mlocator[39m[2m).[22mtoBeVisible[2m([22m[2m)[22m failed

Locator: locator('table, .fi-table, .fi-section, .fi-empty-state')
Expected: visible
Timeout: 5000ms
Error: element(s) not found

Call log:
[2m  - Expect "toBeVisible" with timeout 5000ms[22m
[2m  - waiting for locator('table, .fi-table, .fi-section, .fi-empty-state')[22m

- PASSED • renderiza checkboxes de grupos com rótulos acessíveis (register.spec.ts) • 1375ms
- FAILED • valida seleção mínima de um grupo (register.spec.ts) • 6664ms
  - Erro: Error: [2mexpect([22m[31mlocator[39m[2m).[22mtoBeVisible[2m([22m[2m)[22m failed

Locator: locator('text=Selecione pelo menos um grupo de oração')
Expected: visible
Timeout: 5000ms
Error: element(s) not found

Call log:
[2m  - Expect "toBeVisible" with timeout 5000ms[22m
[2m  - waiting for locator('text=Selecione pelo menos um grupo de oração')[22m

- SKIPPED • realiza cadastro com múltiplos grupos selecionados (register.spec.ts) • 1351ms
- SKIPPED • register normalizes CPF/phone and persists (registration-flow.spec.ts) • 1414ms
- FAILED • open templates section (settings-templates.spec.ts) • 34840ms
  - Erro: Error: [2mexpect([22m[31mreceived[39m[2m).[22mtoBeGreaterThan[2m([22m[32mexpected[39m[2m)[22m

Expected: > [32m0[39m
Received:   [31m0[39m
- PASSED • open Visitas list (visitas.spec.ts) • 2590ms

### admin-firefox
- FAILED • cria usuário básico (admin-crud-basic.spec.ts) • 36388ms
  - Erro: TimeoutError: page.fill: Timeout 30000ms exceeded.
Call log:
[2m  - waiting for locator('input[name="name"]')[22m

- FAILED • cria grupo de oração básico (admin-crud-basic.spec.ts) • 35152ms
  - Erro: TimeoutError: page.fill: Timeout 30000ms exceeded.
Call log:
[2m  - waiting for locator('input[name="name"]')[22m

- FAILED • cria evento básico (admin-crud-basic.spec.ts) • 35513ms
  - Erro: TimeoutError: page.fill: Timeout 30000ms exceeded.
Call log:
[2m  - waiting for locator('input[name="name"]')[22m

- PASSED • theme CSS and sticky sidebar (admin-layout.spec.ts) • 2421ms
- PASSED • navigation groups present (admin-layout.spec.ts) • 1543ms
- FAILED • admin-user-cards redesign basics (admin-layout.spec.ts) • 8036ms
  - Erro: Error: [2mexpect([22m[31mlocator[39m[2m).[22mtoBeVisible[2m([22m[2m)[22m failed

Locator: locator('.uc-card').first().locator('button.btn-details')
Expected: visible
Timeout: 5000ms
Error: element(s) not found

Call log:
[2m  - Expect "toBeVisible" with timeout 5000ms[22m
[2m  - waiting for locator('.uc-card').first().locator('button.btn-details')[22m

- PASSED • admin-user-cards basic responsiveness (admin-layout.spec.ts) • 3883ms
- PASSED • dashboard carrega em tempo aceitável (admin-performance.spec.ts) • 2076ms
- PASSED • bloqueia acesso não autenticado (admin-permissions-access.spec.ts) • 2984ms
- PASSED • permite acesso com credenciais válidas (admin-permissions-access.spec.ts) • 2140ms
- PASSED • sidebar visível em desktop e acessível em mobile (admin-responsiveness.spec.ts) • 2624ms
- FAILED • set role to admin and verify access to Pastoreio pages (admin-roles.spec.ts) • 18723ms
  - Erro: TimeoutError: page.waitForSelector: Timeout 15000ms exceeded.
Call log:
[2m  - waiting for locator('table') to be visible[22m

- PASSED • basic security: sidebar visible after auth (admin-security.spec.ts) • 3385ms
- FAILED • busca segura contra strings de injeção (admin-security.spec.ts) • 34309ms
  - Erro: TimeoutError: page.fill: Timeout 30000ms exceeded.
Call log:
[2m  - waiting for locator('input[type="search"], input[placeholder*="Buscar"], input[placeholder*="Search"]')[22m
[2m    - locator resolved to 2 elements. Proceeding with the first one: <input type="search" role="textbox" autocomplete="off" spellcheck="false" aria-label="Todos" name="search_terms" autocapitalize="none" aria-autocomplete="list" class="choices__input choices__input--cloned" placeholder="Comece a digitar para pesquisar..."/>[22m
[2m    - fill("1 OR 1=1 -- 1765816685975")[22m
[2m  - attempting fill action[22m
[2m    2 × waiting for element to be visible, enabled and editable[22m
[2m      - element is not visible[22m
[2m    - retrying fill action[22m
[2m    - waiting 20ms[22m
[2m    2 × waiting for element to be visible, enabled and editable[22m
[2m      - element is not visible[22m
[2m    - retrying fill action[22m
[2m      - waiting 100ms[22m
[2m    59 × waiting for element to be visible, enabled and editable[22m
[2m       - element is not visible[22m
[2m     - retrying fill action[22m
[2m       - waiting 500ms[22m

- PASSED • campos de texto não executam scripts (admin-security.spec.ts) • 3743ms
- PASSED • loads and shows header actions (Chrome) (admin-settings.spec.ts) • 4040ms
- FAILED • responsiveness and basic interactions (Firefox) (admin-settings.spec.ts) • 4172ms
  - Erro: Error: [2mexpect([22m[31mreceived[39m[2m).[22mtoBeTruthy[2m()[22m

Received: [31mfalse[39m
- PASSED • API integration smoke: create a brand setting (admin-settings.spec.ts) • 35759ms
- FAILED • sidebar is fixed and nav links hover smoothly (admin-sidebar.spec.ts) • 11565ms
  - Erro: Error: [2mexpect([22m[31mlocator[39m[2m).[22mtoBeVisible[2m([22m[2m)[22m failed

Locator: locator('.fi-sidebar a.fi-sidebar-item-button').nth(1)
Expected: visible
Timeout: 5000ms
Error: element(s) not found

Call log:
[2m  - Expect "toBeVisible" with timeout 5000ms[22m
[2m  - waiting for locator('.fi-sidebar a.fi-sidebar-item-button').nth(1)[22m

- PASSED • allows free vertical cropping without aspect lock (logo-editor.spec.ts) • 2935ms
- PASSED • drag handles allow diagonal and vertical resize (logo-editor.spec.ts) • 1495ms
- PASSED • header logo renders and has correct size (logo.spec.ts) • 956ms
- PASSED • home hero shows logo section when configured (logo.spec.ts) • 1102ms
- PASSED • responsive sizes across breakpoints (logo.spec.ts) • 1045ms
- PASSED • logo mounts consistently on all pages (logo.spec.ts) • 2334ms
- PASSED • open pages and check components render (pastoreio-presenca.spec.ts) • 3178ms
- FAILED • generate receipt from Payment Logs (payment-receipt.spec.ts) • 52809ms
  - Erro: Error: [2mexpect([22m[31mlocator[39m[2m).[22mtoBeVisible[2m([22m[2m)[22m failed

Locator: locator('table, .fi-table, .fi-section, .fi-empty-state')
Expected: visible
Timeout: 5000ms
Error: element(s) not found

Call log:
[2m  - Expect "toBeVisible" with timeout 5000ms[22m
[2m  - waiting for locator('table, .fi-table, .fi-section, .fi-empty-state')[22m

- PASSED • renderiza checkboxes de grupos com rótulos acessíveis (register.spec.ts) • 2318ms
- FAILED • valida seleção mínima de um grupo (register.spec.ts) • 6522ms
  - Erro: Error: [2mexpect([22m[31mlocator[39m[2m).[22mtoBeVisible[2m([22m[2m)[22m failed

Locator: locator('text=Selecione pelo menos um grupo de oração')
Expected: visible
Timeout: 5000ms
Error: element(s) not found

Call log:
[2m  - Expect "toBeVisible" with timeout 5000ms[22m
[2m  - waiting for locator('text=Selecione pelo menos um grupo de oração')[22m

- SKIPPED • realiza cadastro com múltiplos grupos selecionados (register.spec.ts) • 2424ms
- SKIPPED • register normalizes CPF/phone and persists (registration-flow.spec.ts) • 1091ms
- FAILED • open templates section (settings-templates.spec.ts) • 40508ms
  - Erro: Error: [2mexpect([22m[31mreceived[39m[2m).[22mtoBeGreaterThan[2m([22m[32mexpected[39m[2m)[22m

Expected: > [32m0[39m
Received:   [31m0[39m
- PASSED • open Visitas list (visitas.spec.ts) • 7798ms

### site-chromium
- FAILED • login com mock de API (site-auth-flows.spec.ts) • 730ms
  - Erro: TypeError: request.postDataJSON(...).catch is not a function
- PASSED • registro com mock de API (site-auth-flows.spec.ts) • 730ms
- FAILED • acesso admin bloqueado sem token (site-auth-flows.spec.ts) • 317ms
  - Erro: Error: page.evaluate: SecurityError: Failed to read the 'localStorage' property from 'Window': Access is denied for this document.
    at UtilityScript.evaluate (<anonymous>:292:16)
    at UtilityScript.<anonymous> (<anonymous>:1:44)
- FAILED • carrega home e mostra links principais (site-navigation.spec.ts) • 6404ms
  - Erro: Error: [2mexpect([22m[31mlocator[39m[2m).[22mtoBeVisible[2m([22m[2m)[22m failed

Locator: locator('a[href="/contato"]').first()
Expected: visible
Timeout: 5000ms
Error: element(s) not found

Call log:
[2m  - Expect "toBeVisible" with timeout 5000ms[22m
[2m  - waiting for locator('a[href="/contato"]').first()[22m

- FAILED • links internos navegam corretamente (site-navigation.spec.ts) • 747ms
  - Erro: Error: [2mexpect([22m[31mlocator[39m[2m).[22mtoBeVisible[2m([22m[2m)[22m failed

Locator: locator('h1, h2')
Expected: visible
Error: strict mode violation: locator('h1, h2') resolved to 2 elements:
    1) <h1 class="text-xl font-bold text-rcc-800">Renovação Carismática</h1> aka getByRole('link', { name: 'Renovação Carismática Católica' })
    2) <h2 class="text-xl font-semibold">Eventos</h2> aka getByRole('heading', { name: 'Eventos' })

Call log:
[2m  - Expect "toBeVisible" with timeout 5000ms[22m
[2m  - waiting for locator('h1, h2')[22m

- PASSED • estrutura de navegação (menu) permanece acessível (site-navigation.spec.ts) • 588ms
- PASSED • home carrega rapidamente (site-performance.spec.ts) • 492ms
- PASSED • eventos carrega em tempo aceitável (site-performance.spec.ts) • 494ms
- FAILED • menu visível em desktop e mobile (site-responsiveness.spec.ts) • 5904ms
  - Erro: Error: [2mexpect([22m[31mlocator[39m[2m).[22mtoBeVisible[2m([22m[2m)[22m failed

Locator:  locator('nav')
Expected: visible
Received: hidden
Timeout:  5000ms

Call log:
[2m  - Expect "toBeVisible" with timeout 5000ms[22m
[2m  - waiting for locator('nav')[22m
[2m    9 × locator resolved to <nav class="hidden md:flex space-x-8">…</nav>[22m
[2m      - unexpected value "hidden"[22m

- PASSED • grid de eventos adapta em breakpoints (site-responsiveness.spec.ts) • 628ms
- FAILED • previne XSS em formulário de contato (site-security.spec.ts) • 749ms
  - Erro: Error: page.fill: Error: Element is not an <input>, <textarea> or [contenteditable] element
Call log:
[2m  - waiting for locator('#subject')[22m
[2m    - locator resolved to <select required="" id="subject" name="subject" class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500 transition-colors">…</select>[22m
[2m    - fill("informacoes")[22m
[2m  - attempting fill action[22m
[2m    - waiting for element to be visible, enabled and editable[22m

- FAILED • rota protegida exige autenticação (site-security.spec.ts) • 381ms
  - Erro: Error: page.evaluate: SecurityError: Failed to read the 'localStorage' property from 'Window': Access is denied for this document.
    at UtilityScript.evaluate (<anonymous>:292:16)
    at UtilityScript.<anonymous> (<anonymous>:1:44)

### site-firefox
- FAILED • login com mock de API (site-auth-flows.spec.ts) • 1936ms
  - Erro: TypeError: request.postDataJSON(...).catch is not a function
- PASSED • registro com mock de API (site-auth-flows.spec.ts) • 1972ms
- FAILED • acesso admin bloqueado sem token (site-auth-flows.spec.ts) • 412ms
  - Erro: Error: page.evaluate: The operation is insecure.
@debugger eval code line 290 > eval:2:7
evaluate@debugger eval code:292:16
@debugger eval code:1:44

- FAILED • carrega home e mostra links principais (site-navigation.spec.ts) • 7070ms
  - Erro: Error: [2mexpect([22m[31mlocator[39m[2m).[22mtoBeVisible[2m([22m[2m)[22m failed

Locator: locator('a[href="/contato"]').first()
Expected: visible
Timeout: 5000ms
Error: element(s) not found

Call log:
[2m  - Expect "toBeVisible" with timeout 5000ms[22m
[2m  - waiting for locator('a[href="/contato"]').first()[22m

- FAILED • links internos navegam corretamente (site-navigation.spec.ts) • 2162ms
  - Erro: Error: [2mexpect([22m[31mlocator[39m[2m).[22mtoBeVisible[2m([22m[2m)[22m failed

Locator: locator('h1, h2')
Expected: visible
Error: strict mode violation: locator('h1, h2') resolved to 2 elements:
    1) <h1 class="text-xl font-bold text-rcc-800">Renovação Carismática</h1> aka getByRole('link', { name: 'Renovação Carismática Católica' })
    2) <h2 class="text-xl font-semibold">Eventos</h2> aka getByRole('heading', { name: 'Eventos' })

Call log:
[2m  - Expect "toBeVisible" with timeout 5000ms[22m
[2m  - waiting for locator('h1, h2')[22m

- PASSED • estrutura de navegação (menu) permanece acessível (site-navigation.spec.ts) • 1881ms
- PASSED • home carrega rapidamente (site-performance.spec.ts) • 613ms
- PASSED • eventos carrega em tempo aceitável (site-performance.spec.ts) • 567ms
- FAILED • menu visível em desktop e mobile (site-responsiveness.spec.ts) • 5869ms
  - Erro: Error: [2mexpect([22m[31mlocator[39m[2m).[22mtoBeVisible[2m([22m[2m)[22m failed

Locator:  locator('nav')
Expected: visible
Received: hidden
Timeout:  5000ms

Call log:
[2m  - Expect "toBeVisible" with timeout 5000ms[22m
[2m  - waiting for locator('nav')[22m
[2m    9 × locator resolved to <nav class="hidden md:flex space-x-8">…</nav>[22m
[2m      - unexpected value "hidden"[22m

- PASSED • grid de eventos adapta em breakpoints (site-responsiveness.spec.ts) • 1770ms
- FAILED • previne XSS em formulário de contato (site-security.spec.ts) • 1000ms
  - Erro: Error: page.fill: Error: Element is not an <input>, <textarea> or [contenteditable] element
Call log:
[2m  - waiting for locator('#subject')[22m
[2m    - locator resolved to <select required="" id="subject" name="subject" class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500 transition-colors">…</select>[22m
[2m    - fill("informacoes")[22m
[2m  - attempting fill action[22m
[2m    - waiting for element to be visible, enabled and editable[22m

- FAILED • rota protegida exige autenticação (site-security.spec.ts) • 1467ms
  - Erro: Error: page.evaluate: The operation is insecure.
@debugger eval code line 290 > eval:2:7
evaluate@debugger eval code:292:16
@debugger eval code:1:44


## Como Reproduzir
- Executar: ADMIN_EMAIL="admin@example.com" ADMIN_PASSWORD="secret" BASE_URL="http://127.0.0.1:8000" SITE_BASE_URL="http://127.0.0.1:3002" npm run test:e2e
- Relatório HTML: playwright-report/index.html
