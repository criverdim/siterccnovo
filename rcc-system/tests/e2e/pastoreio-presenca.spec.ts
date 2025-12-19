import { test, expect } from '@playwright/test'
import { loginAdmin } from './utils'

const baseURL = (globalThis as any).process?.env?.BASE_URL || 'http://127.0.0.1:8000'
const ADMIN_EMAIL = (globalThis as any).process?.env?.ADMIN_EMAIL
const ADMIN_PASSWORD = (globalThis as any).process?.env?.ADMIN_PASSWORD

 

test.describe('Pastoreio & Presença Pages', () => {
  test.skip(!ADMIN_EMAIL || !ADMIN_PASSWORD, 'Admin credentials are required')

  test('open pages and check components render', async ({ page }) => {
    await loginAdmin(page, baseURL, ADMIN_EMAIL!, ADMIN_PASSWORD!)
    await page.goto(`${baseURL}/admin/pastoreio-history`, { waitUntil: 'domcontentloaded' })
    await page.waitForLoadState('networkidle').catch(() => {})
    const count = await page.locator('.fi-section, .fi-card, .fi-table').count()
    expect(count).toBeGreaterThan(0)

    await page.goto(`${baseURL}/admin/presenca-rapida`, { waitUntil: 'domcontentloaded' })
    const groupSelector = page.locator('label:has-text("Grupo") ~ select, .fi-form-field:has-text("Grupo") select, select[name*="group"], select')
    await expect(groupSelector.first()).toBeVisible()
  })
})
