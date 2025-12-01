import { test, expect } from '@playwright/test'

const baseURL = (globalThis as any).process?.env?.BASE_URL || 'https://177.10.16.6'
const ADMIN_EMAIL = (globalThis as any).process?.env?.ADMIN_EMAIL
const ADMIN_PASSWORD = (globalThis as any).process?.env?.ADMIN_PASSWORD

async function loginAdmin(page: any) {
  await page.goto(`${baseURL}/admin/login`, { waitUntil: 'domcontentloaded' })
  await page.fill('input[type="email"]', ADMIN_EMAIL!)
  await page.fill('input[type="password"]', ADMIN_PASSWORD!)
  await Promise.all([
    page.waitForURL('**/admin', { timeout: 30000 }).catch(() => {}),
    page.click('button[type="submit"], button:has-text("Sign in"), button:has-text("Entrar")'),
  ])
}

test.describe('Visitas Resource', () => {
  test.skip(!ADMIN_EMAIL || !ADMIN_PASSWORD, 'Admin credentials are required')
  test('open Visitas list', async ({ page }) => {
    await loginAdmin(page)
    await page.goto(`${baseURL}/admin/resources/visitas`, { waitUntil: 'domcontentloaded' })
    await expect(page.locator('h1, [data-filament-page-title]')).toContainText(/Visitas|Visits/i)
  })
})
