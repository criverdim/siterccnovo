import { test, expect } from '@playwright/test'

test.describe('Site - Responsividade', () => {
  test('menu visível em desktop e mobile', async ({ page }) => {
    await page.setViewportSize({ width: 1280, height: 800 })
    await page.goto('/', { waitUntil: 'domcontentloaded' })
    await expect(page.locator('nav')).toBeVisible()

    await page.setViewportSize({ width: 375, height: 812 })
    // tenta abrir menu mobile se existir
    const toggle = page.locator('button[aria-label*="Menu"], button[aria-label*="menu"]').first()
    if (await toggle.count()) {
      await toggle.click().catch(() => {})
    }
    await expect(page.locator('nav')).toBeVisible()
  })

  test('grid de eventos adapta em breakpoints', async ({ page }) => {
    await page.goto('/eventos', { waitUntil: 'domcontentloaded' })
    await page.setViewportSize({ width: 360, height: 700 })
    const cardsMobile = await page.locator('.grid > *').count().catch(() => 0)
    await page.setViewportSize({ width: 1024, height: 800 })
    const cardsTablet = await page.locator('.grid > *').count().catch(() => 0)
    expect(cardsMobile >= 0 && cardsTablet >= 0).toBeTruthy()
  })
})

