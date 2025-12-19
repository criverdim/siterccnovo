import { test, expect } from '@playwright/test'
import { loginAdmin } from './utils'

const baseURL = (globalThis as any).process?.env?.BASE_URL || 'http://127.0.0.1:8000'
const ADMIN_EMAIL = (globalThis as any).process?.env?.ADMIN_EMAIL
const ADMIN_PASSWORD = (globalThis as any).process?.env?.ADMIN_PASSWORD

 

test.describe('Admin Roles Setup', () => {
  test.skip(!ADMIN_EMAIL || !ADMIN_PASSWORD, 'Admin credentials are required')

  test('set role to admin and verify access to Pastoreio pages', async ({ page }) => {
    await loginAdmin(page, baseURL, ADMIN_EMAIL!, ADMIN_PASSWORD!)
    await page.goto(`${baseURL}/admin/users`, { waitUntil: 'domcontentloaded' })
    await page.waitForSelector('table', { timeout: 15000 })

    // Open first user and set role to admin
    const firstRowEdit = page.locator('table a:has-text("Edit"), table a:has-text("Editar"), table button:has-text("Edit"), table button:has-text("Editar")').first()
    await firstRowEdit.waitFor({ state: 'visible', timeout: 15000 })
    await firstRowEdit.click()
    // Select role
    const roleSelect = page.locator('select[name="role"], [data-role-select] select, label:has-text("Nível") ~ select')
    await expect(roleSelect).toBeVisible()
    await roleSelect.selectOption('admin')
    // Save
    const save = page.locator('button[type="submit"], button:has-text("Save"), button:has-text("Salvar")').first()
    await save.click().catch(async()=>{ await page.keyboard.press('Control+Enter') })
    await page.waitForLoadState('networkidle')

    // Navigate to Pastoreio pages
    await page.goto(`${baseURL}/admin/pastoreio-history`, { waitUntil: 'domcontentloaded' })
    await expect(page.locator('h1, [data-filament-page-title]')).toContainText(/Histórico|Pastoral/i)
    await page.goto(`${baseURL}/admin/presenca-rapida`, { waitUntil: 'domcontentloaded' })
    await expect(page.locator('h1, [data-filament-page-title]')).toContainText(/Presença|Attendance/i)
  })
})
