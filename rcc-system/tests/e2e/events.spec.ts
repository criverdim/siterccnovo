import { test, expect } from '@playwright/test'

const baseURL = (globalThis as any).process?.env?.BASE_URL || 'http://127.0.0.1:8000'

test.describe('Página de Eventos (/events)', () => {
  test('renderiza estrutura básica e filtros acessíveis', async ({ page }) => {
    await page.goto(`${baseURL}/events`, { waitUntil: 'domcontentloaded' })
    await page.waitForLoadState('networkidle').catch(() => {})
    await expect(page.locator('h1:has-text("Eventos RCC")')).toBeVisible()
    const form = page.locator('form[role="search"]')
    await expect(form).toBeVisible()
    await expect(form.locator('input[name="q"]')).toBeVisible()
    await expect(form.locator('select[name="paid"]')).toBeVisible()
    await expect(form.locator('select[name="month"]')).toBeVisible()
    await expect(form.locator('button:has-text("Filtrar")')).toBeVisible()
    await expect(form.locator('a:has-text("Limpar")')).toBeVisible()
  })

  test('aplica filtros e depois limpa mantendo página funcional', async ({ page }) => {
    await page.goto(`${baseURL}/events`, { waitUntil: 'domcontentloaded' })
    await page.fill('input[name="q"]', `teste ${Date.now()}`)
    await page.selectOption('select[name="paid"]', '')
    await page.selectOption('select[name="month"]', '1')
    await Promise.all([
      page.waitForURL('**/events?**', { timeout: 20000 }).catch(() => {}),
      page.click('button:has-text("Filtrar")'),
    ])
    const urlAfterFilter = page.url()
    expect(urlAfterFilter).toMatch(/\/events\?.+/)
    await Promise.all([
      page.waitForURL('**/events', { timeout: 20000 }).catch(() => {}),
      page.click('a:has-text("Limpar")'),
    ])
    await expect(page).toHaveURL(/\/events$/)
  })

  test('lista exibe cards ou estado vazio', async ({ page }) => {
    await page.goto(`${baseURL}/events`, { waitUntil: 'domcontentloaded' })
    const cards = page.locator('article >> a:has-text("Detalhes")')
    const count = await cards.count()
    if (count === 0) {
      await expect(page.locator('text=Nenhum evento encontrado.')).toBeVisible()
    } else {
      const first = cards.first()
      await expect(first).toBeVisible()
    }
  })

  test('detalhe do evento: hero visível e participar redireciona login', async ({ page }) => {
    await page.goto(`${baseURL}/events`, { waitUntil: 'domcontentloaded' })
    const detailLinks = page.locator('article >> a:has-text("Detalhes")')
    const total = await detailLinks.count()
    test.skip(total === 0, 'Sem eventos cadastrados no ambiente de teste')
    await detailLinks.first().click()
    await page.waitForLoadState('domcontentloaded')
    const heroImg = page.locator('img[alt*="Evento"], img[alt*="event"]')
    await expect(heroImg.first()).toBeVisible()
    const box = await heroImg.first().boundingBox()
    expect((box?.height ?? 0)).toBeGreaterThan(40)
    const participate = page.locator('#participateForm button[type="submit"]')
    await expect(participate).toBeVisible()
    await Promise.all([
      page.waitForURL('**/login**', { timeout: 30000 }).catch(() => {}),
      participate.click(),
    ])
    await expect(page).toHaveURL(/\/login\?redirect=/)
  })
})
