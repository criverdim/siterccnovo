import { test, expect } from '@playwright/test'

async function loginAdmin(page: any, baseURL: string, email: string, password: string) {
  await page.goto(`${baseURL}/admin/login`, { waitUntil: 'domcontentloaded' })
  await page.fill('input[type="email"]', email)
  await page.fill('input[type="password"]', password)
  await Promise.all([
    page.waitForURL('**/admin', { timeout: 30000 }).catch(() => {}),
    page.click('button[type="submit"], button:has-text("Sign in"), button:has-text("Entrar")'),
  ])
}

const baseURL = (globalThis as any).process?.env?.BASE_URL || 'https://177.10.16.6'
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

    // open create modal/form
    const createBtn = page.locator('button:has-text("Create"), a:has-text("Create"), button:has-text("Criar"), a:has-text("Criar")')
    await expect(createBtn).toBeVisible()
    await createBtn.click()

    // select a key and validate dynamic section shows
    const keySelect = page.locator('select[name*="key"], select').first()
    await keySelect.waitFor({ state: 'visible', timeout: 20000 })
    await keySelect.selectOption('brand')
    // check for brand section content (label or helperText)
    const brandSectionCandidates = [
      'label:has-text("Logotipo")',
      '.fi-form-field:has-text("Logotipo")',
      'text=Formatos: PNG, SVG, JPG'
    ]
    let brandVisible = false
    for (const sel of brandSectionCandidates) {
      if (await page.locator(sel).count() > 0) { brandVisible = true; break }
    }
    expect(brandVisible).toBeTruthy()
  })

  test('API integration smoke: create a brand setting', async ({ page }) => {
    test.skip(!ADMIN_EMAIL || !ADMIN_PASSWORD, 'Admin credentials are required')
    await loginAdmin(page, baseURL, ADMIN_EMAIL!, ADMIN_PASSWORD!)
    await page.goto(`${baseURL}/admin/settings`, { waitUntil: 'domcontentloaded' })
    const createBtn = page.locator('button:has-text("Create"), a:has-text("Create"), button:has-text("Criar"), a:has-text("Criar")')
    await createBtn.first().waitFor({ state: 'visible', timeout: 15000 })
    await createBtn.first().click()
    await page.waitForSelector('select', { timeout: 15000 })
    await page.selectOption('select', 'brand')
    // submit without file should still create empty brand record
    const saveCandidates = [
      'button[type="submit"]',
      'button:has-text("Save")',
      'button:has-text("Salvar")',
    ]
    let clicked = false
    for (const sel of saveCandidates) {
      const btn = page.locator(sel).first()
      if (await btn.count()) {
        await btn.click({ trial: false }).catch(() => {})
        clicked = true
        break
      }
    }
    expect(clicked).toBeTruthy()
    await expect(page.locator('table')).toBeVisible()
    await expect(page.locator('table >> text=brand')).toBeVisible()
    // navigate to public pages and ensure logo container renders
    for (const path of ['/', '/events', '/groups', '/calendar']) {
      await page.goto(`${baseURL}${path}`, { waitUntil: 'domcontentloaded' })
      const logo = page.locator('header img.site-logo')
      await expect(logo).toBeVisible()
    }
  })
})
