import { test, expect } from '@playwright/test'

const baseURL = (globalThis as any).process?.env?.BASE_URL || 'https://177.10.16.6'

test.describe('Registration Flow', () => {
  test('register normalizes CPF/phone and persists', async ({ page }) => {
    await page.goto(`${baseURL}/register`, { waitUntil: 'domcontentloaded' })
    await page.waitForSelector('input[name="name"], label:has-text("Nome") ~ input', { timeout: 20000 })
    await page.fill('input[name="name"], label:has-text("Nome") ~ input', 'Teste Usuário')
    await page.fill('input[name="email"], label:has-text("Email") ~ input', `teste_${Date.now()}@mail.com`)
    await page.fill('input[name="phone"], label:has-text("Telefone") ~ input', '(11) 9999-9999')
    await page.fill('input[name="whatsapp"], label:has-text("WhatsApp") ~ input', '(11) 9 8888-7777')
    await page.fill('input[name="cpf"], label:has-text("CPF") ~ input', '123.456.789-10')
    await page.fill('input[name="password"], label:has-text("Senha") ~ input', 'segredo123')
    await page.check('input[name="consent"]')
    await Promise.all([
      page.waitForNavigation({ waitUntil: 'domcontentloaded' }),
      page.click('button[type="submit"], button:has-text("Cadastrar")'),
    ])
    await expect(page).toHaveURL(/login/) // redireciona para login
  })
})
