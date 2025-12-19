import { test, expect } from '@playwright/test'

const baseURL = (globalThis as any).process?.env?.BASE_URL || 'http://127.0.0.1:8000'

test.describe('Página de Cadastro (/register)', () => {
  test('renderiza checkboxes de grupos com rótulos acessíveis', async ({ page }) => {
    await page.goto(`${baseURL}/register`, { waitUntil: 'domcontentloaded' })
    await page.waitForLoadState('networkidle').catch(() => {})

    const groupSection = page.locator('text=Grupos de oração')
    await expect(groupSection.first()).toBeVisible()

    const checkboxes = page.locator('input[type="checkbox"][aria-label*="Selecionar grupo"]')
    const count = await checkboxes.count()
    if (count > 0) {
      const firstCheckbox = checkboxes.first()
      await expect(firstCheckbox).toHaveAttribute('aria-label', /Selecionar grupo/i)
    }
  })

  test('valida seleção mínima de um grupo', async ({ page }) => {
    await page.goto(`${baseURL}/register`, { waitUntil: 'domcontentloaded' })
    await page.waitForLoadState('networkidle').catch(() => {})

    const submit = page.locator('button:has-text("Cadastrar")').first()
    await expect(submit).toBeVisible()
    await submit.click()

    const error = page.locator('#groups-error')
    await expect(error).toBeVisible()
    await expect(error).toHaveText(/Selecione pelo menos um grupo de oração/i)
  })

  test('realiza cadastro com múltiplos grupos selecionados', async ({ page }) => {
    await page.goto(`${baseURL}/register`, { waitUntil: 'domcontentloaded' })
    await page.waitForLoadState('networkidle').catch(() => {})

    const groupCheckboxes = page.locator('label:has-text("Grupos de oração") input[type="checkbox"]')
    const totalGroups = await groupCheckboxes.count()
    test.skip(totalGroups === 0, 'Sem grupos cadastrados no ambiente de teste')

    // Preenche campos obrigatórios
    await page.locator('label:has-text("Nome completo") input').fill('Usuário Teste')
    const uniqueEmail = `teste+${Date.now()}@example.com`
    await page.locator('label:has-text("Email") input').fill(uniqueEmail)
    await page.locator('label:has-text("Telefone") input').fill('11987654321')
    await page.locator('label:has-text("WhatsApp") input').fill('11987654321')
    await page.locator('label:has-text("Senha") input').fill('123456')
    await page.locator('label:has-text("Confirmação de senha") input').fill('123456')

    // Seleciona pelo menos um grupo (ideal: dois)
    await groupCheckboxes.nth(0).check()
    if (totalGroups > 1) {
      await groupCheckboxes.nth(1).check()
    }

    // Aceita LGPD
    const lgpd = page.locator('label:has-text("Consentimento LGPD") input[type="checkbox"]')
    await lgpd.first().check()

    const submit = page.locator('button:has-text("Cadastrar")').first()
    await submit.click()

    await page.waitForURL('**/login', { timeout: 30000 }).catch(() => {})
    await expect(page).toHaveURL(/\/login$/)
  })
})
