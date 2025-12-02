import { test, expect } from '@playwright/test'

const baseURL = (globalThis as any).process?.env?.BASE_URL || 'http://127.0.0.1:8000'
const ADMIN_EMAIL = (globalThis as any).process?.env?.ADMIN_EMAIL
const ADMIN_PASSWORD = (globalThis as any).process?.env?.ADMIN_PASSWORD

async function loginAdmin(page: any) {
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
  await page.goto(`${baseURL}/admin`, { waitUntil: 'domcontentloaded' })
  const sidebar = page.locator('.fi-sidebar')
  await sidebar.waitFor({ state: 'visible', timeout: 30000 }).catch(async () => {
    await page.waitForLoadState('networkidle').catch(() => {})
    await page.reload({ waitUntil: 'domcontentloaded' })
    await sidebar.waitFor({ state: 'visible', timeout: 15000 }).catch(() => {})
  })
}

test.describe('Settings Templates', () => {
  test.skip(!ADMIN_EMAIL || !ADMIN_PASSWORD, 'Admin credentials are required')
test('open templates section', async ({ page, browserName }) => {
    await loginAdmin(page)
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
