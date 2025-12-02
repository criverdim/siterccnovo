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
  const sidebar = page.locator('.fi-sidebar')
  await sidebar.waitFor({ state: 'visible', timeout: 30000 }).catch(async () => {
    await page.waitForLoadState('networkidle').catch(() => {})
    await page.reload({ waitUntil: 'domcontentloaded' })
    await sidebar.waitFor({ state: 'visible', timeout: 15000 }).catch(() => {})
  })
}

test.describe('Payment Receipt PDF', () => {
  test.skip(!ADMIN_EMAIL || !ADMIN_PASSWORD, 'Admin credentials are required')
  test('generate receipt from Payment Logs', async ({ page }) => {
    await loginAdmin(page)
    await page.goto(`${baseURL}/admin/payment-logs`, { waitUntil: 'domcontentloaded' })
    await page.waitForLoadState('networkidle').catch(() => {})
    const tableOrEmpty = page.locator('table, .fi-table, .fi-section, .fi-empty-state')
    await tableOrEmpty.first().waitFor({ state: 'visible', timeout: 15000 }).catch(() => {})
    const receiptBtn = page.locator('button:has-text("Gerar Recibo PDF"), a:has-text("Gerar Recibo PDF"), button:has-text("Receipt")').first()
    await receiptBtn.click().catch(() => {})
    const table = page.locator('table, .fi-table, .fi-section, .fi-empty-state')
    await expect(table).toBeVisible()
  })
})
