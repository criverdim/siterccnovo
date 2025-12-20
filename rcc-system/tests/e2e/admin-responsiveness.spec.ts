import { test, expect } from '@playwright/test'
import { loginAdmin } from './utils'

const baseURL = (globalThis as any).process?.env?.BASE_URL || 'http://127.0.0.1:8000'
const ADMIN_EMAIL = (globalThis as any).process?.env?.ADMIN_EMAIL
const ADMIN_PASSWORD = (globalThis as any).process?.env?.ADMIN_PASSWORD

 

test.describe('Admin - Responsividade', () => {
  test('sidebar visível em desktop e acessível em mobile', async ({ page, browserName }) => {
    test.skip(browserName === 'webkit', 'Somente Chrome/Firefox')
    test.skip(!ADMIN_EMAIL || !ADMIN_PASSWORD, 'Credenciais admin ausentes')
    await loginAdmin(page, baseURL, ADMIN_EMAIL!, ADMIN_PASSWORD!)
    await page.goto(`${baseURL}/admin`, { waitUntil: 'domcontentloaded' })
    await page.setViewportSize({ width: 1440, height: 900 })
    await expect(page.locator('.fi-sidebar')).toBeVisible()
    await page.setViewportSize({ width: 375, height: 812 })
    await expect(page.locator('.fi-sidebar')).toBeVisible()
  })

  test('users sem barra horizontal em múltiplos tamanhos', async ({ page }) => {
    test.skip(!ADMIN_EMAIL || !ADMIN_PASSWORD, 'Credenciais admin ausentes')
    await loginAdmin(page, baseURL, ADMIN_EMAIL!, ADMIN_PASSWORD!)
    await page.goto(`${baseURL}/admin/users`, { waitUntil: 'domcontentloaded' })
    const sizes = [
      { width: 1280, height: 800 },
      { width: 1600, height: 900 },
      { width: 1920, height: 1080 },
      { width: 768, height: 1024 },
      { width: 1024, height: 768 },
      { width: 390, height: 844 },
      { width: 360, height: 780 },
      { width: 412, height: 915 },
    ]
    const noOverflow = async () => {
      const pageNoOverflow = await page.evaluate(() => {
        const el = document.scrollingElement || document.documentElement
        return el.scrollWidth <= el.clientWidth
      })
      const tableNoOverflow = await page.locator('.fi-ta-ctn').evaluateAll((nodes) => {
        return nodes.every(n => (n.scrollWidth <= n.clientWidth))
      }).catch(() => true)
      return pageNoOverflow && tableNoOverflow
    }
    for (const s of sizes) {
      await page.setViewportSize(s)
      await page.waitForLoadState('networkidle').catch(() => {})
      const ok = await noOverflow()
      expect(ok).toBeTruthy()
    }
  })
})
