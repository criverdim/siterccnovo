import { test, expect } from '@playwright/test'
import fs from 'fs'

const baseURL = (globalThis as any).process?.env?.BASE_URL || 'http://127.0.0.1:8000'
const ADMIN_EMAIL = (globalThis as any).process?.env?.ADMIN_EMAIL
const ADMIN_PASSWORD = (globalThis as any).process?.env?.ADMIN_PASSWORD

test.describe('Admin layout smoke', () => {
  test.beforeEach(async ({ page }) => {
    test.skip(!ADMIN_EMAIL || !ADMIN_PASSWORD, 'Admin credentials are required')
    try {
      const raw = fs.readFileSync('cookies.txt', 'utf-8')
      const lines: string[] = raw.split('\n').filter((line: string) => line && !line.startsWith('#'))
      const cookies = lines.map((line: string) => {
        const parts = line.split(/\s+/)
        const domain = parts[0].replace('HttpOnly_', '')
        return {
          name: parts[5],
          value: parts[6],
          domain,
          path: parts[2],
          httpOnly: parts[0].startsWith('HttpOnly_'),
          secure: parts[3] === 'TRUE',
        }
      })
      await page.context().addCookies(cookies)
    } catch {}
    await page.goto(`${baseURL}/admin/login`, { waitUntil: 'domcontentloaded' })
    await page.waitForSelector('input[type="email"]', { timeout: 15000 })
    await page.fill('input[type="email"]', ADMIN_EMAIL!)
    await page.fill('input[type="password"]', ADMIN_PASSWORD!)
    await Promise.all([
      page.waitForURL('**/admin', { timeout: 45000 }).catch(() => {}),
      page.click('button:has-text("Sign in"), button:has-text("Entrar"), button:has-text("Login")'),
    ])
    const sidebar = page.locator('.fi-sidebar')
    await sidebar.waitFor({ state: 'visible', timeout: 30000 }).catch(async () => {
      await page.waitForLoadState('networkidle').catch(() => {})
      await page.reload({ waitUntil: 'domcontentloaded' })
      await sidebar.waitFor({ state: 'visible', timeout: 15000 }).catch(() => {})
    })
  })

test('theme CSS and sticky sidebar', async ({ page }) => {
  const cssLinkCount = await page.locator('head link[rel="stylesheet"][href*="admin-"][href$=".css"]').count()
  expect(cssLinkCount).toBeGreaterThan(0)
  const position = await page.evaluate(() => getComputedStyle(document.querySelector('.fi-sidebar')!).position)
  expect(position).toBe('sticky')
  await page.screenshot({ path: 'test-results/admin-layout-sticky-chromium.png', fullPage: true })
})

  test('navigation groups present', async ({ page }) => {
    const candidates = ['Gerenciamento','Eventos','Logs','Configurações','Management','Events','Settings']
    let found = 0
    for (const label of candidates) {
      found += await page.locator('.fi-sidebar-group-label', { hasText: label }).count()
    }
    expect(found).toBeGreaterThanOrEqual(3)
    await page.screenshot({ path: 'test-results/admin-layout-groups-chromium.png', fullPage: true })
  })

  test('admin-user-cards redesign basics', async ({ page }) => {
    await page.goto(`${baseURL}/admin/admin-user-cards`, { waitUntil: 'domcontentloaded' })
    await page.waitForSelector('#users-grid', { timeout: 15000 })
    await page.waitForLoadState('networkidle').catch(() => {})

    const firstCard = page.locator('.uc-card').first()
    await firstCard.scrollIntoViewIfNeeded()
    await expect(firstCard).toBeVisible()

    // Botão "Ver detalhes →" estilizado
    const detailsBtn = firstCard.locator('button.btn-details')
    await expect(detailsBtn).toBeVisible()
    const bgColor = await detailsBtn.evaluate(el => getComputedStyle(el).backgroundColor)
    // #2E86AB em rgb
    expect(bgColor).toMatch(/rgb\(46,\s*134,\s*171\)/)

    // Layout com espaçamento interno de ~18px
    const bodyPadding = await firstCard.locator('.uc-card-body').evaluate(el => getComputedStyle(el).padding)
    expect(bodyPadding).toMatch(/18px/)

    // Ícones 24x24
    const iconSize = await firstCard.locator('.uc-icon').first().evaluate(el => [getComputedStyle(el).width, getComputedStyle(el).height])
    expect(iconSize[0]).toBe('24px')
    expect(iconSize[1]).toBe('24px')
  })

  test('admin-user-cards basic responsiveness', async ({ page }) => {
    await page.goto(`${baseURL}/admin/admin-user-cards`, { waitUntil: 'domcontentloaded' })
    await page.waitForSelector('.uc-card', { timeout: 15000 })

    // Desktop
    await page.setViewportSize({ width: 1440, height: 900 })
    await page.screenshot({ path: 'test-results/admin-user-cards-1440.png' })

    // Tablet
    await page.setViewportSize({ width: 1024, height: 800 })
    await page.screenshot({ path: 'test-results/admin-user-cards-1024.png' })

    // Mobile
    await page.setViewportSize({ width: 390, height: 844 })
    await page.screenshot({ path: 'test-results/admin-user-cards-390.png' })
  })
})
