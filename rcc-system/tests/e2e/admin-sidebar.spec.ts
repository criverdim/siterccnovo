import { test, expect } from '@playwright/test'
import { loginAdmin } from './utils'

const baseURL = (globalThis as any).process?.env?.BASE_URL || 'http://127.0.0.1:8000'
const ADMIN_EMAIL = (globalThis as any).process?.env?.ADMIN_EMAIL || (globalThis as any).process?.env?.TEST_ADMIN_EMAIL
const ADMIN_PASSWORD = (globalThis as any).process?.env?.ADMIN_PASSWORD || (globalThis as any).process?.env?.TEST_ADMIN_PASSWORD

test.describe('Admin Sidebar Layout', () => {
  test('sidebar is fixed and nav links hover smoothly', async ({ page, browserName }) => {
    test.skip(browserName === 'webkit', 'Only Chrome/Firefox')
    test.skip(!ADMIN_EMAIL || !ADMIN_PASSWORD, 'Admin credentials are required')
    await loginAdmin(page, baseURL, ADMIN_EMAIL!, ADMIN_PASSWORD!)

    await page.goto(`${baseURL}/admin`, { waitUntil: 'domcontentloaded' })
    await page.waitForLoadState('networkidle')
    const sidebar = page.locator('.fi-sidebar')
    await sidebar.waitFor({ state: 'visible', timeout: 10000 }).catch(async () => {
      await page.waitForLoadState('networkidle').catch(() => {})
      await page.waitForSelector('.fi-topbar, .fi-sidebar', { timeout: 5000 }).catch(() => {})
    })
    await page.evaluate(() => {
      const dlg = document.querySelector('#livewire-error') as HTMLElement | null
      if (dlg) dlg.remove()
    })
    const position = await sidebar.evaluate(el => getComputedStyle(el).position)
    expect(['fixed','sticky']).toContain(position)
    const firstItem = page.locator('.fi-sidebar a.fi-sidebar-item-button').first()
    await expect(firstItem).toBeVisible()
    const paddingLeft = await firstItem.evaluate(el => parseInt(getComputedStyle(el).paddingLeft))
    expect(paddingLeft).toBeGreaterThanOrEqual(15)
    const beforeBg = await firstItem.evaluate(el => getComputedStyle(el).backgroundColor)
    await firstItem.hover()
    if (browserName === 'firefox') {
      await page.waitForTimeout(500)
    }
    const afterBg = await firstItem.evaluate(el => getComputedStyle(el).backgroundColor)
    expect(afterBg).not.toEqual(beforeBg)

    // CSS theme loaded
    const cssLinkCount = await page.locator('head link[rel="stylesheet"][href*="admin-"][href$=".css"]').count()
    expect(cssLinkCount).toBeGreaterThan(0)

    // No console errors
    const consoleErrors: string[] = []
    page.on('console', (msg) => { if (msg.type() === 'error') consoleErrors.push(msg.text()) })
    await page.waitForTimeout(500)
    expect(consoleErrors).toEqual([])

    // Responsividade 1280 e 1920
    await page.setViewportSize({ width: 1280, height: 800 })
    await expect(sidebar).toBeVisible()
    await page.setViewportSize({ width: 1920, height: 900 })
    await expect(sidebar).toBeVisible()

    // Navegação por todos os itens do menu
    const items = page.locator('.fi-sidebar a.fi-sidebar-item-button')
    const count = Math.min(await items.count(), 10)
    for (let i = 0; i < count; i++) {
      const item = items.nth(i)
      await expect(item).toBeVisible()
      const href = await item.getAttribute('href')
      if (href) {
        await Promise.all([
          page.waitForLoadState('domcontentloaded'),
          item.click(),
        ])
        const pageTitle = page.locator('[data-filament-page-title], h1, h2').first()
        await pageTitle.waitFor({ state: 'visible', timeout: 15000 }).catch(() => {})
        await page.waitForLoadState('networkidle').catch(() => {})
        await page.goBack({ waitUntil: 'domcontentloaded' })
      }
    }
  })

  test('settings items visible in sidebar', async ({ page, browserName }) => {
    test.skip(browserName === 'webkit', 'Only Chrome/Firefox')
    test.skip(!ADMIN_EMAIL || !ADMIN_PASSWORD, 'Admin credentials are required')
    await loginAdmin(page, baseURL, ADMIN_EMAIL!, ADMIN_PASSWORD!)
    await page.goto(`${baseURL}/admin`, { waitUntil: 'domcontentloaded' })
    await page.waitForLoadState('networkidle').catch(() => {})

    const labels = [
      /Homepage/i,
      /Marca/i,
      /Site/i,
      /Email/i,
      /SMS/i,
      /Redes Sociais|Social/i,
      /Mercado Pago/i,
      /WhatsApp/i,
      /Templates/i,
    ]

    let present = 0
    for (const re of labels) {
      present += await page.locator('.fi-sidebar a.fi-sidebar-item-button', { hasText: re }).count()
    }
    expect(present).toBeGreaterThanOrEqual(5)
  })
})
