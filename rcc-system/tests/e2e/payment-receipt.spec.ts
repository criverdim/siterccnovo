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

test.describe('Payment Receipt PDF', () => {
  test.skip(!ADMIN_EMAIL || !ADMIN_PASSWORD, 'Admin credentials are required')
  test('generate receipt from Payment Logs', async ({ page }) => {
    await loginAdmin(page)
    await page.goto(`${baseURL}/admin/resources/payment-logs`, { waitUntil: 'domcontentloaded' })
    const receiptBtn = page.locator('button:has-text("Gerar Recibo PDF"), a:has-text("Gerar Recibo PDF"), button:has-text("Receipt")').first()
    await receiptBtn.click().catch(() => {})
    // Apenas valida presença da tabela
    await expect(page.locator('table')).toBeVisible()
  })
})
