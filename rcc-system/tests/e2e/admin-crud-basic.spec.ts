import { test, expect } from '@playwright/test'
import { loginAdmin } from './utils'

const baseURL = (globalThis as any).process?.env?.BASE_URL || 'http://127.0.0.1:8000'
const ADMIN_EMAIL = (globalThis as any).process?.env?.ADMIN_EMAIL
const ADMIN_PASSWORD = (globalThis as any).process?.env?.ADMIN_PASSWORD

test.describe('Admin - CRUD básico', () => {
  test.skip(!ADMIN_EMAIL || !ADMIN_PASSWORD, 'Credenciais admin ausentes')

  test('cria usuário básico', async ({ page }) => {
    await loginAdmin(page, baseURL, ADMIN_EMAIL!, ADMIN_PASSWORD!)
    await page.goto(`${baseURL}/admin/users`, { waitUntil: 'domcontentloaded' })
    const createBtn = page.locator('button:has-text("Create"), a:has-text("Create"), button:has-text("Criar"), a:has-text("Criar")').first()
    if (await createBtn.count()) {
      await createBtn.click()
    } else {
      await page.goto(`${baseURL}/admin/users/create`, { waitUntil: 'domcontentloaded' })
    }
    const ts = Date.now()
    const name = `Usuário E2E ${ts}`
    const email = `e2e.user.${ts}@example.com`
    await page.fill('input[name="name"]', name)
    await page.fill('input[name="email"]', email)
    await page.fill('input[name="phone"]', '+55 11 99999-9999')
    await page.fill('input[name="whatsapp"]', '+55 11 99999-9999')
    const roleSelect = page.locator('select[name="role"], label:has-text("Nível") ~ select').first()
    await roleSelect.selectOption('servo').catch(async () => {
      await page.selectOption('select', 'servo')
    })
    const save = page.locator('button[type="submit"], button:has-text("Save"), button:has-text("Salvar")').first()
    await save.click().catch(async () => { await page.keyboard.press('Control+Enter') })
    await page.waitForLoadState('networkidle')
    await page.goto(`${baseURL}/admin/users`, { waitUntil: 'domcontentloaded' })
    await expect(page.locator(`text=${name}`)).toHaveCount(1)
  })

  test('cria grupo de oração básico', async ({ page }) => {
    await loginAdmin(page, baseURL, ADMIN_EMAIL!, ADMIN_PASSWORD!)
    await page.goto(`${baseURL}/admin/groups`, { waitUntil: 'domcontentloaded' })
    const createBtn = page.locator('button:has-text("Cadastrar"), a:has-text("Cadastrar"), button:has-text("Create"), a:has-text("Create")').first()
    if (await createBtn.count()) {
      await createBtn.click()
    } else {
      await page.goto(`${baseURL}/admin/groups/create`, { waitUntil: 'domcontentloaded' })
    }
    const ts = Date.now()
    const groupName = `Grupo E2E ${ts}`
    await page.fill('input[name="name"]', groupName)
    await page.fill('input[name="responsible"]', 'Responsável E2E')
    const weekday = page.locator('select[name="weekday"]')
    await weekday.selectOption('monday').catch(async () => { await page.selectOption('select', 'monday') })
    const timeInput = page.locator('input[name="time"], label:has-text("Horário") ~ input, [data-time-input]')
    await timeInput.first().fill('10:00').catch(async () => {
      const label = page.locator('label:has-text("Horário")').first()
      await label.click().catch(() => {})
      await page.keyboard.type('10:00')
    })
    await page.fill('textarea[name="address"], label:has-text("Endereço") ~ textarea', 'Rua Teste, 123 - Centro, São Paulo/SP')
    const save = page.locator('button[type="submit"], button:has-text("Save"), button:has-text("Salvar")').first()
    await save.click().catch(async () => { await page.keyboard.press('Control+Enter') })
    await page.waitForLoadState('networkidle')
    await page.goto(`${baseURL}/admin/groups`, { waitUntil: 'domcontentloaded' })
    await expect(page.locator(`text=${groupName}`)).toHaveCount(1)
  })

  test('cria evento básico', async ({ page }) => {
    await loginAdmin(page, baseURL, ADMIN_EMAIL!, ADMIN_PASSWORD!)
    await page.goto(`${baseURL}/admin/events`, { waitUntil: 'domcontentloaded' })
    const createBtn = page.locator('button:has-text("Create"), a:has-text("Create"), button:has-text("Criar"), a:has-text("Criar")').first()
    if (await createBtn.count()) {
      await createBtn.click()
    } else {
      await page.goto(`${baseURL}/admin/events/create`, { waitUntil: 'domcontentloaded' })
    }
    const ts = Date.now()
    const eventName = `Evento E2E ${ts}`
    await page.fill('input[name="name"]', eventName)
    const desc = page.locator('[contenteditable], textarea[name="description"]')
    if (await desc.count()) {
      await desc.first().fill('Descrição do evento E2E').catch(async () => {
        await page.keyboard.type('Descrição do evento E2E')
      })
    }
    await page.fill('input[name="location"], label:has-text("Local") ~ input', 'Local E2E')
    const dateVal = new Date(Date.now() + 7 * 24 * 60 * 60 * 1000).toISOString().slice(0, 10)
    const startDate = page.locator('input[name="start_date"], label:has-text("Data de Início") ~ input')
    await startDate.first().fill(dateVal).catch(async () => {
      await startDate.first().click().catch(() => {})
      await page.keyboard.type(dateVal)
    })
    const startTime = page.locator('input[name="start_time"], label:has-text("Horário de Início") ~ input')
    await startTime.first().fill('10:00').catch(async () => {
      await startTime.first().click().catch(() => {})
      await page.keyboard.type('10:00')
    })
    const save = page.locator('button[type="submit"], button:has-text("Save"), button:has-text("Salvar")').first()
    await save.click().catch(async () => { await page.keyboard.press('Control+Enter') })
    await page.waitForLoadState('networkidle')
    await page.goto(`${baseURL}/admin/events`, { waitUntil: 'domcontentloaded' })
    await expect(page.locator(`text=${eventName}`)).toHaveCount(1)
  })
})
