import { test, expect } from '@playwright/test'

const ADMIN_EMAIL = process.env.ADMIN_EMAIL
const ADMIN_PASSWORD = process.env.ADMIN_PASSWORD

let consoleErrors: string[] = []

test.describe('Admin layout', () => {
  test.beforeEach(async ({ page }) => {
    if (!ADMIN_EMAIL || !ADMIN_PASSWORD) throw new Error('ADMIN_EMAIL and ADMIN_PASSWORD envs are required')
    consoleErrors = []
    page.on('console', (msg) => {
      if (msg.type() === 'error') consoleErrors.push(msg.text())
    })
    await page.goto('/admin')
    await page.fill('input[type="email"]', ADMIN_EMAIL)
    await page.fill('input[type="password"]', ADMIN_PASSWORD)
    await page.click('button[type="submit"]')
    await page.waitForURL('**/admin', { timeout: 30000 })
    await expect(page.locator('.fi-sidebar')).toBeVisible()
  })

  test('theme css loaded', async ({ page }) => {
    const cssLinkCount = await page.locator('head link[rel="stylesheet"][href*="admin-"][href$=".css"]').count()
    expect(cssLinkCount).toBeGreaterThan(0)
  })

  test('sidebar sticky and responsive', async ({ page }) => {
    const position = await page.evaluate(() => {
      const el = document.querySelector('.fi-sidebar') as HTMLElement
      return getComputedStyle(el).position
    })
    expect(position).toBe('sticky')

    await page.setViewportSize({ width: 1280, height: 800 })
    await expect(page.locator('.fi-sidebar')).toBeVisible()
    await page.setViewportSize({ width: 1920, height: 900 })
    await expect(page.locator('.fi-sidebar')).toBeVisible()

    const topBefore = await page.evaluate(() => {
      const el = document.querySelector('.fi-sidebar') as HTMLElement
      return el.getBoundingClientRect().top
    })
    await page.evaluate(() => window.scrollTo(0, 1000))
    await page.waitForTimeout(300)
    const topAfter = await page.evaluate(() => {
      const el = document.querySelector('.fi-sidebar') as HTMLElement
      return el.getBoundingClientRect().top
    })
    expect(Math.abs(topAfter - topBefore)).toBeLessThan(5)
  })

  test('topbar blur and navigation groups', async ({ page }) => {
    const blurValue = await page.evaluate(() => {
      const el = document.querySelector('.fi-topbar') as HTMLElement
      const s = getComputedStyle(el) as any
      return s.backdropFilter || (s as any).webkitBackdropFilter
    })
    expect(blurValue).toContain('blur')

    const labels = ['Gerenciamento', 'Eventos', 'Logs', 'Configurações']
    for (const label of labels) {
      await expect(page.locator('.fi-sidebar-group-label').filter({ hasText: label })).toHaveCount(1)
    }
  })

  test('no console errors', async () => {
    expect(consoleErrors.length).toBe(0)
  })
})

