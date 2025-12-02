import { test, expect } from '@playwright/test'

const baseURL = (globalThis as any).process?.env?.BASE_URL || 'http://127.0.0.1:8000'
const ADMIN_EMAIL = (globalThis as any).process?.env?.ADMIN_EMAIL
const ADMIN_PASSWORD = (globalThis as any).process?.env?.ADMIN_PASSWORD

async function loginAdmin(page: any) {
  const loginUrl = `${baseURL}/testing/login-admin?email=${encodeURIComponent(ADMIN_EMAIL!)}`
  await page.goto(loginUrl, { waitUntil: 'domcontentloaded' })
  const ok = await page.locator('text=ok').count().catch(() => 0)
  if (!ok) {
    await page.goto(`${baseURL}/admin/login`, { waitUntil: 'domcontentloaded' })
    const emailSel = 'input[type="email"], input[name="email"]'
    const passSel = 'input[type="password"], input[name="password"]'
    await page.waitForSelector(emailSel, { timeout: 20000 })
    await page.fill(emailSel, ADMIN_EMAIL!)
    await page.fill(passSel, ADMIN_PASSWORD!)
    const submit = page.locator('button[type="submit"], button:has-text("Sign in"), button:has-text("Entrar"), button:has-text("Login")').first()
    await Promise.all([
      page.waitForURL('**/admin', { timeout: 45000 }).catch(() => {}),
      submit.click(),
    ])
  }
  await page.goto(`${baseURL}/admin`, { waitUntil: 'domcontentloaded' })
  await page.waitForSelector('.fi-sidebar', { timeout: 30000 })
}

test.describe('Pastoreio & Presença Pages', () => {
  test.skip(!ADMIN_EMAIL || !ADMIN_PASSWORD, 'Admin credentials are required')

  test('open pages and check components render', async ({ page }) => {
    await loginAdmin(page)
    await page.goto(`${baseURL}/admin/pastoreio-history`, { waitUntil: 'domcontentloaded' })
    await page.waitForLoadState('networkidle').catch(() => {})
    const count = await page.locator('.fi-section, .fi-card, .fi-table').count()
    expect(count).toBeGreaterThan(0)

    await page.goto(`${baseURL}/admin/presenca-rapida`, { waitUntil: 'domcontentloaded' })
    const groupSelector = page.locator('label:has-text("Grupo") ~ select, .fi-form-field:has-text("Grupo") select, select[name*="group"], select')
    await expect(groupSelector.first()).toBeVisible()
  })
})
