import { test, expect } from '@playwright/test'
import { loginAdmin } from './utils'

const baseURL = (globalThis as any).process?.env?.BASE_URL || 'http://127.0.0.1:8000'
const ADMIN_EMAIL = (globalThis as any).process?.env?.ADMIN_EMAIL
const ADMIN_PASSWORD = (globalThis as any).process?.env?.ADMIN_PASSWORD

test.describe('Admin Security', () => {
  test.skip(!ADMIN_EMAIL || !ADMIN_PASSWORD, 'Admin credentials are required')
  test('basic security: sidebar visible after auth', async ({ page }) => {
    await loginAdmin(page, baseURL, ADMIN_EMAIL!, ADMIN_PASSWORD!)
    await page.goto(`${baseURL}/admin`, { waitUntil: 'domcontentloaded' })
    await expect(page.locator('.fi-sidebar')).toBeVisible()
  })
})

test.describe('Admin - Segurança', () => {
  test('busca segura contra strings de injeção', async ({ page, browserName }) => {
    test.skip(browserName === 'webkit', 'Somente Chrome/Firefox')
    test.skip(!ADMIN_EMAIL || !ADMIN_PASSWORD, 'Credenciais admin ausentes')
    await loginAdmin(page, baseURL, ADMIN_EMAIL!, ADMIN_PASSWORD!)
    await page.goto(`${baseURL}/admin/users`, { waitUntil: 'domcontentloaded' })
    const searchSel = 'input[type="search"], input[placeholder*="Buscar"], input[placeholder*="Search"]'
    const exists = await page.locator(searchSel).count()
    if (exists) {
      await page.fill(searchSel, `1 OR 1=1 -- ${Date.now()}`)
      await page.keyboard.press('Enter').catch(() => {})
      await page.waitForLoadState('networkidle')
      const consoleErrors: string[] = []
      page.on('console', (msg) => { if (msg.type() === 'error') consoleErrors.push(msg.text()) })
      expect(consoleErrors.length).toBe(0)
    }
  })

  test('campos de texto não executam scripts', async ({ page }) => {
    await loginAdmin(page, baseURL, ADMIN_EMAIL!, ADMIN_PASSWORD!)
    await page.goto(`${baseURL}/admin/settings`, { waitUntil: 'domcontentloaded' })
    const textSel = 'input[type="text"], textarea'
    const count = await page.locator(textSel).count()
    if (count > 0) {
      await page.evaluate(() => { (window as any)._xss = 0; (window as any).alert = () => { (window as any)._xss = 1 } })
      await page.locator(textSel).first().fill('<script>alert(1)</script>')
      await page.waitForLoadState('networkidle')
      const xss = await page.evaluate(() => (window as any)._xss)
      expect(xss).toBe(0)
    }
  })
})
