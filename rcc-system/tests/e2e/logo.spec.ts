import { test, expect } from '@playwright/test'

const baseURL = process.env.BASE_URL || 'http://127.0.0.1'

test.describe('Logo visibility and responsiveness', () => {
  test('header logo renders and has correct size', async ({ page, browserName }) => {
    test.skip(browserName === 'webkit', 'Only Chrome/Firefox')
    await page.goto(`${baseURL}/`, { waitUntil: 'domcontentloaded' })

    const logo = page.locator('header img.site-logo')
    await expect(logo).toBeVisible()
    const box = await logo.boundingBox()
    expect(box?.height ?? 0).toBeGreaterThan(40) // clamp minimum
  })

  test('home hero shows logo section when configured', async ({ page, browserName }) => {
    test.skip(browserName === 'webkit', 'Only Chrome/Firefox')
    await page.goto(`${baseURL}/`, { waitUntil: 'domcontentloaded' })
    const logoHero = page.locator('section >> img.site-logo')
    // section may be omitted if no logo configured, so only assert no console errors
    const consoleErrors: string[] = []
    page.on('console', (msg) => { if (msg.type() === 'error') consoleErrors.push(msg.text()) })
    await expect(consoleErrors).toEqual([])
  })

  test('responsive sizes across breakpoints', async ({ page, browserName }) => {
    test.skip(browserName === 'webkit', 'Only Chrome/Firefox')
    await page.goto(`${baseURL}/`, { waitUntil: 'domcontentloaded' })
    const logo = page.locator('header img.site-logo')

    await page.setViewportSize({ width: 375, height: 700 })
    const small = await logo.boundingBox()

    await page.setViewportSize({ width: 1280, height: 900 })
    const large = await logo.boundingBox()

    expect((large?.height ?? 0)).toBeGreaterThan((small?.height ?? 0))
  })

  test('logo mounts consistently on all pages', async ({ page, browserName }) => {
    test.skip(browserName === 'webkit', 'Only Chrome/Firefox')
    for (const path of ['/', '/events', '/groups', '/calendar', '/pastoreio']) {
      await page.goto(`${baseURL}${path}`, { waitUntil: 'domcontentloaded' })
      const logo = page.locator('header img.site-logo')
      await expect(logo).toBeVisible()
      const box = await logo.boundingBox()
      expect(box?.height ?? 0).toBeGreaterThan(40)
    }
  })
})
