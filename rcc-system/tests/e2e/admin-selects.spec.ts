import { test, expect } from '@playwright/test'
import { loginAdmin } from './utils'

const baseURL = (globalThis as any).process?.env?.BASE_URL || 'http://127.0.0.1:8000'
const ADMIN_EMAIL = (globalThis as any).process?.env?.ADMIN_EMAIL || (globalThis as any).process?.env?.TEST_ADMIN_EMAIL
const ADMIN_PASSWORD = (globalThis as any).process?.env?.ADMIN_PASSWORD || (globalThis as any).process?.env?.TEST_ADMIN_PASSWORD

test.describe('Admin Selects styling', () => {
  test('selects não mostram seta duplicada', async ({ page, browserName }) => {
    test.skip(browserName === 'webkit', 'Validado em Chrome/Firefox')
    test.skip(!ADMIN_EMAIL || !ADMIN_PASSWORD, 'Credenciais de admin são necessárias')

    await loginAdmin(page, baseURL, ADMIN_EMAIL!, ADMIN_PASSWORD!)

    // Abrir a listagem de usuários e ir para editar o primeiro registro
    await page.goto(`${baseURL}/admin/users`, { waitUntil: 'domcontentloaded' })
    await page.waitForLoadState('networkidle')

    const editAction = page.locator('button:has([data-icon="heroicon-o-pencil"]), a[href*="/edit"]').first()
    if (await editAction.count() === 0) {
      // Fallback: tentar rota direta de edição do primeiro registro
      await page.goto(`${baseURL}/admin/users/1/edit`, { waitUntil: 'domcontentloaded' }).catch(() => {})
    } else {
      await editAction.click()
      await page.waitForLoadState('networkidle')
    }

    // Garantir que o formulário abriu
    const form = page.locator('form').first()
    await expect(form).toBeVisible()

    // Verificar selects na página (gender, role, status, etc.)
    const selectWrappers = page.locator('.fi-fo-select')
    const count = await selectWrappers.count()
    expect(count).toBeGreaterThan(0)

    for (let i = 0; i < count; i++) {
      const wrapper = selectWrappers.nth(i)
      const hasNative = await wrapper.locator('select').count()

      if (hasNative) {
        const bgImage = await wrapper.locator('select').evaluate((el) => getComputedStyle(el).backgroundImage)
        expect(bgImage).toBe('none')
      }

      const choices = wrapper.locator('.choices')
      if (await choices.count()) {
        const afterContent = await choices.evaluate((el) => getComputedStyle(el, '::after').getPropertyValue('content'))
        // Com nossa correção CSS, o ::after deve estar sem conteúdo (none)
        expect(afterContent?.replace(/["']/g, '')).toBe('none')
      }
    }
  })
})
