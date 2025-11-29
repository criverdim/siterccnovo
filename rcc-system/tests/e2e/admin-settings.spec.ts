import { test, expect } from '@playwright/test'

const baseURL = process.env.BASE_URL || 'http://127.0.0.1'

test.describe('Admin Settings page', () => {
  test('loads and shows header actions (Chrome)', async ({ page, browserName }) => {
    test.skip(browserName === 'webkit', 'Only Chrome/Firefox for now')
    await page.goto(`${baseURL}/admin/settings`, { waitUntil: 'domcontentloaded' })

    const consoleErrors: string[] = []
    page.on('console', (msg) => { if (msg.type() === 'error') consoleErrors.push(msg.text()) })

    await expect(page.locator('h1, [data-filament-page-title]')).toContainText(/Settings|Configurações/i)
    const listEmpty = page.locator('text=No settings')
    await expect(listEmpty).toBeVisible()

    // Should have a Create action in header toolbar
    const createBtn = page.locator('button:has-text("Create"), a:has-text("Create"), button:has-text("Criar"), a:has-text("Criar")')
    await expect(createBtn).toBeVisible()

    // Click create and check form elements exist
    await createBtn.click()
    await expect(page.locator('label:has-text("Chave")')).toBeVisible()
    await expect(page.locator('select')).toBeVisible()

    // No console errors
    await expect(consoleErrors).toEqual([])
  })

  test('responsiveness and basic interactions (Firefox)', async ({ page, browserName }) => {
    test.skip(browserName === 'webkit', 'Only Chrome/Firefox for now')
    await page.setViewportSize({ width: 375, height: 700 })
    await page.goto(`${baseURL}/admin/settings`, { waitUntil: 'domcontentloaded' })

    const sidebar = page.locator('[data-filament-sidebar], nav')
    await expect(sidebar).toBeVisible()

    // open create modal/form
    const createBtn = page.locator('button:has-text("Create"), a:has-text("Create"), button:has-text("Criar"), a:has-text("Criar")')
    await expect(createBtn).toBeVisible()
    await createBtn.click()

    // select a key and validate dynamic section shows
    await page.selectOption('select', 'brand')
    await expect(page.locator('label:has-text("Logotipo"), input[type=file]')).toBeVisible()
  })

  test('API integration smoke: create a brand setting', async ({ page }) => {
    await page.goto(`${baseURL}/admin/settings`)
    const createBtn = page.locator('button:has-text("Create"), a:has-text("Create"), button:has-text("Criar"), a:has-text("Criar")')
    await createBtn.click()
    await page.selectOption('select', 'brand')
    // submit without file should still create empty brand record
    const saveBtn = page.locator('button:has-text("Save"), button:has-text("Salvar")')
    await saveBtn.click({ trial: false })
    await expect(page.locator('table')).toBeVisible()
    await expect(page.locator('table >> text=brand')).toBeVisible()
  })
})
