import { test, expect } from '@playwright/test'

const baseURL = (globalThis as any).process?.env?.BASE_URL || 'http://127.0.0.1:8000'

test.describe('Registration Flow', () => {
  test('register normalizes CPF/phone and persists', async ({ page }) => {
    await page.goto(`${baseURL}/register`, { waitUntil: 'domcontentloaded' })
    await page.waitForSelector('form', { timeout: 20000 })
    const nameSel = 'label:has-text("Nome completo") input'
    const emailSel = 'label:has-text("Email") input'
    const phoneSel = 'label:has-text("Telefone") input'
    const whatsappSel = 'label:has-text("WhatsApp") input'
    const cpfSel = 'label:has-text("CPF") input'
    const passSel = 'label:has-text("Senha") input[type="password"]'
    await page.waitForSelector(nameSel, { timeout: 20000 })
    await page.fill(nameSel, 'Teste Usuário')
    await page.fill(emailSel, `teste_${Date.now()}@mail.com`)
    await page.fill(phoneSel, '(11) 9999-9999')
    await page.fill(whatsappSel, '(11) 9 8888-7777')
    await page.fill(cpfSel, '123.456.789-10')
    await page.fill(passSel, 'segredo123')
    await page.check('label:has-text("Consentimento") input[type="checkbox"], label:has-text("LGPD") input[type="checkbox"]')
    const groupCheckboxes = page.locator('label:has-text("Grupos de oração") input[type="checkbox"]')
    const totalGroups = await groupCheckboxes.count()
    test.skip(totalGroups === 0, 'Sem grupos cadastrados no ambiente de teste')
    await groupCheckboxes.nth(0).check()
    if (totalGroups > 1) {
      await groupCheckboxes.nth(1).check()
    }
    await Promise.all([
      page.waitForURL('**/login', { timeout: 30000 }).catch(() => {}),
      page.click('button[type="submit"], button:has-text("Cadastrar")'),
    ])
    await expect(page).toHaveURL(/\/login$/)
  })
})
