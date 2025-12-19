import { test, expect } from '@playwright/test'
import { loginAdmin } from './utils'

const baseURL = (globalThis as any).process?.env?.BASE_URL || 'http://127.0.0.1:8000'
const ADMIN_EMAIL = (globalThis as any).process?.env?.ADMIN_EMAIL
const ADMIN_PASSWORD = (globalThis as any).process?.env?.ADMIN_PASSWORD

test.describe('Settings Templates', () => {
  test.skip(!ADMIN_EMAIL || !ADMIN_PASSWORD, 'Admin credentials are required')
  test('open templates section', async ({ page, browserName }) => {
    await loginAdmin(page, baseURL, ADMIN_EMAIL!, ADMIN_PASSWORD!)
    await page.goto(`${baseURL}/admin/settings`, { waitUntil: 'domcontentloaded' })
    await page.waitForLoadState('networkidle').catch(() => {})
    const createBtn = page.locator('button:has-text("Create"), a:has-text("Create"), button:has-text("Criar"), a:has-text("Criar")')
    if (await createBtn.count()) {
      await createBtn.first().click()
    } else {
      await page.goto(`${baseURL}/admin/settings/create`, { waitUntil: 'domcontentloaded' })
    }
    await page.selectOption('label:has-text("Chave") ~ select, select', 'templates').catch(async () => {
      await page.locator('select').first().selectOption('templates')
    })
    if (browserName === 'firefox') {
      await page.waitForTimeout(7000)
    }
    await page.waitForSelector('[contenteditable]', { timeout: 30000 }).catch(() => {})
    const editableCount = await page.locator('[contenteditable]').count()
    expect(editableCount).toBeGreaterThan(0)
  })
})
