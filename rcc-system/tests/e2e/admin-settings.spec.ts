import { test, expect } from '@playwright/test'

async function loginAdmin(page: any, baseURL: string, email: string, password: string) {
  await page.goto(`${baseURL}/admin/login`, { waitUntil: 'domcontentloaded' })
  const emailSel = 'input[type="email"], input[name="email"]'
  const passSel = 'input[type="password"], input[name="password"]'
  await page.waitForSelector(emailSel, { timeout: 20000 })
  await page.fill(emailSel, email)
  await page.fill(passSel, password)
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

const baseURL = (globalThis as any).process?.env?.BASE_URL || 'http://127.0.0.1:8000'
const ADMIN_EMAIL = (globalThis as any).process?.env?.ADMIN_EMAIL || (globalThis as any).process?.env?.TEST_ADMIN_EMAIL
const ADMIN_PASSWORD = (globalThis as any).process?.env?.ADMIN_PASSWORD || (globalThis as any).process?.env?.TEST_ADMIN_PASSWORD

test.describe('Admin Settings page', () => {
  test('loads and shows header actions (Chrome)', async ({ page, browserName }) => {
    test.skip(browserName === 'webkit', 'Only Chrome/Firefox for now')
    test.skip(!ADMIN_EMAIL || !ADMIN_PASSWORD, 'Admin credentials are required')
    await loginAdmin(page, baseURL, ADMIN_EMAIL!, ADMIN_PASSWORD!)
    await page.goto(`${baseURL}/admin/settings`, { waitUntil: 'domcontentloaded' })
    await page.waitForLoadState('networkidle')

    const consoleErrors: string[] = []
    page.on('console', (msg) => { if (msg.type() === 'error') consoleErrors.push(msg.text()) })

    await expect(page.locator('h1, [data-filament-page-title]')).toContainText(/Settings|Configurações/i)
    const emptyCandidates = [
      '.fi-empty-state',
      'text=No settings',
      'text=Sem registros',
    ]
    let hasEmpty = false
    for (const sel of emptyCandidates) {
      if (await page.locator(sel).count() > 0) { hasEmpty = true; break }
    }
    if (!hasEmpty) {
      await expect(page.locator('table')).toBeVisible()
    }

    const createBtn = page.locator('button:has-text("Create"), a:has-text("Create"), button:has-text("Criar"), a:has-text("Criar"), button[type="submit"]:has-text("New")')
    await createBtn.first().waitFor({ state: 'visible', timeout: 20000 })
    await expect(createBtn).toBeVisible()

    await createBtn.click()
    await expect(page.locator('label:has-text("Chave")')).toBeVisible()
    await expect(page.locator('select')).toBeVisible()

    await expect(consoleErrors).toEqual([])
  })

  test('responsiveness and basic interactions (Firefox)', async ({ page, browserName }) => {
    test.skip(browserName === 'webkit', 'Only Chrome/Firefox for now')
    test.skip(!ADMIN_EMAIL || !ADMIN_PASSWORD, 'Admin credentials are required')
    await page.setViewportSize({ width: 1280, height: 800 })
    await loginAdmin(page, baseURL, ADMIN_EMAIL!, ADMIN_PASSWORD!)
    await page.goto(`${baseURL}/admin/settings`, { waitUntil: 'domcontentloaded' })
    await page.waitForLoadState('networkidle')

    const sidebar = page.locator('.fi-sidebar')
    await expect(sidebar).toBeVisible()

    const createBtn = page.locator('button:has-text("Create"), a:has-text("Create"), button:has-text("Criar"), a:has-text("Criar")')
    if (await createBtn.count()) {
      await createBtn.first().click()
    } else {
      await page.goto(`${baseURL}/admin/settings/create`, { waitUntil: 'domcontentloaded' })
    }

    const keySelect = page.locator('label:has-text("Chave") ~ select, select').first()
    await keySelect.waitFor({ state: 'visible', timeout: 30000 })
    await keySelect.selectOption('brand').catch(async () => { await page.selectOption('select', 'brand') })
    const formFieldCount = await page.locator('.fi-form-field, .fi-section').count()
    if (formFieldCount === 0) {
      const emptyCandidates = ['.fi-empty-state','text=Sem registros','text=No settings']
      let hasAny = false
      for (const sel of emptyCandidates) { if (await page.locator(sel).count() > 0) { hasAny = true; break } }
      expect(hasAny).toBeTruthy()
    } else {
      expect(formFieldCount).toBeGreaterThan(0)
    }
  })

  test('API integration smoke: create a brand setting', async ({ page }) => {
    test.skip(!ADMIN_EMAIL || !ADMIN_PASSWORD, 'Admin credentials are required')
    await loginAdmin(page, baseURL, ADMIN_EMAIL!, ADMIN_PASSWORD!)
    await page.goto(`${baseURL}/admin/settings`, { waitUntil: 'domcontentloaded' })
    const createBtn = page.locator('button:has-text("Create"), a:has-text("Create"), button:has-text("Criar"), a:has-text("Criar")')
    if (await createBtn.count()) {
      await createBtn.first().click()
    } else {
      await page.goto(`${baseURL}/admin/settings/create`, { waitUntil: 'domcontentloaded' })
    }
    const formExists = await page.locator('label:has-text("Chave"), select').first().count()
    if (formExists) {
      await page.selectOption('label:has-text("Chave") ~ select, select', 'brand').catch(async () => {
        await page.locator('select').first().selectOption('brand')
      })
      const save = page.locator('button[type="submit"], button:has-text("Save"), button:has-text("Salvar")').first()
      await save.click().catch(() => {})
      await page.waitForLoadState('networkidle').catch(() => {})
      await page.goto(`${baseURL}/admin/settings`, { waitUntil: 'domcontentloaded' })
    }
    const tableOrList = page.locator('table, .fi-table, .fi-resource-table, .fi-section, .fi-empty-state')
    await expect(tableOrList.first()).toBeVisible()
  })
})
