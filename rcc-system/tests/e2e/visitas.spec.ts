import { test, expect } from '@playwright/test'
import { loginAdmin } from './utils'

const baseURL = (globalThis as any).process?.env?.BASE_URL || 'http://127.0.0.1:8000'
const ADMIN_EMAIL = (globalThis as any).process?.env?.ADMIN_EMAIL
const ADMIN_PASSWORD = (globalThis as any).process?.env?.ADMIN_PASSWORD

 

test.describe('Visitas Resource', () => {
  test.skip(!ADMIN_EMAIL || !ADMIN_PASSWORD, 'Admin credentials are required')
  test('open Visitas list', async ({ page, browserName }) => {
    await loginAdmin(page, baseURL, ADMIN_EMAIL!, ADMIN_PASSWORD!)
    await page.goto(`${baseURL}/admin/visitas`, { waitUntil: 'domcontentloaded' })
    if (browserName === 'firefox') {
      await page.waitForTimeout(5000)
    }
    await page.waitForLoadState('networkidle').catch(() => {})
    const title = page.locator('[data-filament-page-title], h1').first()
    await expect(title).toContainText(/Visitas|Visits/i)
  })
})
