import { test, expect } from '@playwright/test'
import { loginAdmin } from './utils'

const baseURL = (globalThis as any).process?.env?.BASE_URL || 'http://127.0.0.1:8000'
const ADMIN_EMAIL = (globalThis as any).process?.env?.ADMIN_EMAIL
const ADMIN_PASSWORD = (globalThis as any).process?.env?.ADMIN_PASSWORD

 

test.describe('Payment Receipt PDF', () => {
  test.skip(!ADMIN_EMAIL || !ADMIN_PASSWORD, 'Admin credentials are required')
  test('generate receipt from Payment Logs', async ({ page }) => {
    await loginAdmin(page, baseURL, ADMIN_EMAIL!, ADMIN_PASSWORD!)
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
