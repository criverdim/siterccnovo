import { test, expect } from '@playwright/test'

async function navTiming(page: any) {
  const t = await page.evaluate(() => {
    const nav = performance.getEntriesByType('navigation')[0] as any
    if (nav) return { dcl: nav.domContentLoadedEventEnd, load: nav.loadEventEnd }
    const pt = performance.timing as any
    return {
      dcl: Math.max(0, pt.domContentLoadedEventEnd - pt.navigationStart),
      load: Math.max(0, pt.loadEventEnd - pt.navigationStart),
    }
  })
  return t
}

test.describe('Site - Performance', () => {
  test('home carrega rapidamente', async ({ page }) => {
    await page.goto('/', { waitUntil: 'domcontentloaded' })
    const t = await navTiming(page)
    expect(t.dcl).toBeLessThan(4000)
  })

  test('eventos carrega em tempo aceitável', async ({ page }) => {
    await page.goto('/eventos', { waitUntil: 'domcontentloaded' })
    const t = await navTiming(page)
    expect(t.dcl).toBeLessThan(4500)
  })
})

