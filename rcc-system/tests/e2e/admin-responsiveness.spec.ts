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
})
