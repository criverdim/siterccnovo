import { test, expect } from '@playwright/test'
import { loginAdmin } from './utils'

const baseURL = (globalThis as any).process?.env?.BASE_URL || 'http://127.0.0.1:8000'
const ADMIN_EMAIL = (globalThis as any).process?.env?.ADMIN_EMAIL
const ADMIN_PASSWORD = (globalThis as any).process?.env?.ADMIN_PASSWORD

 

test.describe('Admin - Permissões e acesso', () => {
  test('bloqueia acesso não autenticado', async ({ page }) => {
    await page.goto(`${baseURL}/admin`, { waitUntil: 'domcontentloaded' })
    const sidebarCount = await page.locator('.fi-sidebar').count().catch(() => 0)
    expect(sidebarCount).toBe(0)
  })

  test('permite acesso com credenciais válidas', async ({ page, browserName }) => {
    test.skip(browserName === 'webkit', 'Somente Chrome/Firefox')
    test.skip(!ADMIN_EMAIL || !ADMIN_PASSWORD, 'Credenciais admin ausentes')
    await loginAdmin(page, baseURL, ADMIN_EMAIL!, ADMIN_PASSWORD!)
    await page.goto(`${baseURL}/admin`, { waitUntil: 'domcontentloaded' })
    await expect(page.locator('.fi-sidebar')).toBeVisible()
  })
})
