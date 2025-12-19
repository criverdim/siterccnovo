import { test, expect } from '@playwright/test'

test.describe('Site - Fluxos de autenticação', () => {
  test('login com mock de API', async ({ page }) => {
    await page.route('**/api/auth/login', async (route) => {
      const request = route.request()
      const body = await request.postDataJSON().catch(()=>({}))
      const ok = body?.email?.includes('@') && body?.password?.length > 0
      await route.fulfill({
        status: ok ? 200 : 400,
        contentType: 'application/json',
        body: JSON.stringify(ok ? { token: 'mock-token-123' } : { error: 'invalid' }),
      })
    })
    await page.route('**/api/user/profile', async (route) => {
      await route.fulfill({
        status: 200,
        contentType: 'application/json',
        body: JSON.stringify({ user: { email: 'user@example.com', role: 'user' } }),
      })
    })
    await page.goto('/login', { waitUntil: 'domcontentloaded' })
    await page.fill('input[placeholder="Email"]', 'user@example.com')
    await page.fill('input[placeholder="Senha"]', 'secret123')
    await page.click('button:has-text("Login"), button:has-text("Entrar")')
    await page.waitForURL('**/dashboard', { timeout: 15000 })
    const token = await page.evaluate(() => localStorage.getItem('token'))
    expect(token).toBe('mock-token-123')
  })

  test('registro com mock de API', async ({ page }) => {
    await page.route('**/api/auth/register', async (route) => {
      await route.fulfill({
        status: 200,
        contentType: 'application/json',
        body: JSON.stringify({ token: 'mock-token-456' }),
      })
    })
    await page.route('**/api/user/profile', async (route) => {
      await route.fulfill({
        status: 200,
        contentType: 'application/json',
        body: JSON.stringify({ user: { email: 'new@example.com', role: 'user' } }),
      })
    })
    await page.goto('/registro', { waitUntil: 'domcontentloaded' })
    await page.fill('input[placeholder="Nome"]', 'Novo Usuário')
    await page.fill('input[placeholder="Email"]', 'new@example.com')
    await page.fill('input[placeholder="Senha"]', 'secret123')
    await page.click('button:has-text("Criar conta")')
    await page.waitForURL('**/dashboard', { timeout: 20000 })
    const token = await page.evaluate(() => localStorage.getItem('token'))
    expect(token).toBe('mock-token-456')
  })

  test('acesso admin bloqueado sem token', async ({ page }) => {
    await page.evaluate(() => { localStorage.removeItem('token') })
    await page.goto('/admin', { waitUntil: 'domcontentloaded' })
    // Deve redirecionar ou exigir login
    const url = page.url()
    expect(url === `${page.url().replace(/\/admin$/, '')}/login` || url.includes('/login') || url.endsWith('/')).toBeTruthy()
  })
})

