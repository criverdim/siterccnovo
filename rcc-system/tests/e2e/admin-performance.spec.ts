import { test, expect } from '@playwright/test'
import { loginAdmin } from './utils'

const baseURL = (globalThis as any).process?.env?.BASE_URL || 'http://127.0.0.1:8000'
const ADMIN_EMAIL = (globalThis as any).process?.env?.ADMIN_EMAIL
const ADMIN_PASSWORD = (globalThis as any).process?.env?.ADMIN_PASSWORD

 

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

test.describe('Admin - Performance', () => {
  test('dashboard carrega em tempo aceitável', async ({ page, browserName }) => {
    test.skip(browserName === 'webkit', 'Somente Chrome/Firefox')
    test.skip(!ADMIN_EMAIL || !ADMIN_PASSWORD, 'Credenciais admin ausentes')
    await loginAdmin(page, baseURL, ADMIN_EMAIL!, ADMIN_PASSWORD!)
    await page.goto(`${baseURL}/admin`, { waitUntil: 'domcontentloaded' })
    const t = await navTiming(page)
    expect(t.dcl).toBeLessThan(5000)
  })
})
