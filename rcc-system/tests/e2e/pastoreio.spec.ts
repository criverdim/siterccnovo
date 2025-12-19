import { test, expect } from '@playwright/test'

const baseURL = (globalThis as any).process?.env?.BASE_URL || 'http://127.0.0.1:8000'

test.describe('Página de Pastoreio (/pastoreio)', () => {
  test('renderiza estrutura básica e seções principais', async ({ page }) => {
    await page.goto(`${baseURL}/pastoreio`, { waitUntil: 'domcontentloaded' })
    await page.waitForLoadState('networkidle').catch(() => {})
    await expect(page.locator('h1:has-text("Pastoreio")')).toBeVisible()
    await expect(page.locator('#react-pastoreio-app')).toBeVisible()
    await expect(page.locator('text=Buscar fiel')).toBeVisible()
    await expect(page.locator('text=Registrar presença')).toBeVisible()
    await expect(page.locator('text=Sorteio')).toBeVisible()
  })

  test('form de presença inicia com botão desabilitado sem grupo', async ({ page }) => {
    await page.goto(`${baseURL}/pastoreio`, { waitUntil: 'domcontentloaded' })
    const registrarBtn = page.locator('button:has-text("Registrar")')
    await expect(registrarBtn).toBeVisible()
    await expect(registrarBtn).toBeDisabled()
  })

  test('botão de sorteio exige grupo e data', async ({ page }) => {
    await page.goto(`${baseURL}/pastoreio`, { waitUntil: 'domcontentloaded' })
    const sortearBtn = page.locator('button:has-text("Sortear")')
    await expect(sortearBtn).toBeVisible()
    await expect(sortearBtn).toBeDisabled()
  })

  test('fluxo completo: cria grupo, registra presença e sorteia', async ({ page, request }) => {
    const csrf = await request.get(`${baseURL}/csrf-token`)
    const token = (await csrf.json()).csrf_token
    const seedRes = await request.get(`${baseURL}/__e2e/seed-group`)
    expect(seedRes.ok()).toBeTruthy()
    const seed = await seedRes.json()
    const groupId = String(seed.id)

    const today = new Date()
    const iso = `${today.getFullYear()}-${String(today.getMonth()+1).padStart(2,'0')}-${String(today.getDate()).padStart(2,'0')}`
    const attRes = await request.post(`${baseURL}/pastoreio/attendance`, {
      headers: { 'Content-Type': 'application/json', 'X-Requested-With': 'XMLHttpRequest', 'X-CSRF-TOKEN': token },
      data: { group_id: parseInt(groupId, 10), date: iso, name: 'Teste E2E', phone: '11999990000' },
    })
    expect(attRes.ok()).toBeTruthy()
    const att = await attRes.json()
    expect(att.status).toBe('ok')

    await page.goto(`${baseURL}/pastoreio`, { waitUntil: 'domcontentloaded' })
    await page.selectOption('div:has(h2:has-text("Registrar presença")) select', groupId)
    await page.fill('div:has(h2:has-text("Sorteio")) input[type="date"]', iso)
    await page.click('button:has-text("Sortear")')
    await expect(page.locator('text=Sorteado: usuário #')).toBeVisible()
  })
})
