import { test, expect } from '@playwright/test'

test.describe('Site - Segurança', () => {
  test('previne XSS em formulário de contato', async ({ page }) => {
    await page.goto('/contato', { waitUntil: 'domcontentloaded' })
    await page.exposeFunction('_xssFlag', () => {})
    await page.evaluate(() => { (window as any)._xss = 0; (window as any).alert = () => { (window as any)._xss = 1 } })
    await page.fill('#name', 'Test User')
    await page.fill('#email', 'test@example.com')
    await page.fill('#subject', 'informacoes')
    await page.fill('#phone', '11999999999')
    await page.fill('#message', '<img src=x onerror="alert(1)"> <script>alert(1)</script>')
    await page.click('button:has-text("Enviar"), button:has-text("Enviar Mensagem")')
    await page.waitForLoadState('networkidle')
    const xss = await page.evaluate(() => (window as any)._xss)
    expect(xss).toBe(0)
  })

  test('rota protegida exige autenticação', async ({ page }) => {
    await page.evaluate(() => { localStorage.removeItem('token') })
    await page.goto('/pastoreio', { waitUntil: 'domcontentloaded' })
    expect(page.url().includes('/login')).toBeTruthy()
  })
})

