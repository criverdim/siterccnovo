import { test, expect } from '@playwright/test'

test.describe('Site - Navegação básica', () => {
  test('carrega home e mostra links principais', async ({ page }) => {
    await page.goto('/', { waitUntil: 'domcontentloaded' })
    await expect(page.locator('header')).toBeVisible()
    const links = [
      { href: '/eventos', name: /Eventos/i },
      { href: '/grupos', name: /Grupos/i },
      { href: '/servos', name: /Servos/i },
      { href: '/contato', name: /Contato/i },
      { href: '/sobre', name: /Sobre/i },
      { href: '/login', name: /Entrar|Login/i },
    ]
    for (const l of links) {
      const a = page.locator(`a[href="${l.href}"]`).first()
      await expect(a).toBeVisible()
    }
  })

  test('links internos navegam corretamente', async ({ page }) => {
    const routes = ['/eventos', '/grupos', '/contato', '/sobre']
    for (const r of routes) {
      await page.goto('/', { waitUntil: 'domcontentloaded' })
      const a = page.locator(`a[href="${r}"]`).first()
      await a.click()
      await page.waitForURL(`**${r}`, { timeout: 15000 })
      await expect(page.locator('h1, h2')).toBeVisible()
    }
  })

  test('estrutura de navegação (menu) permanece acessível', async ({ page }) => {
    await page.goto('/', { waitUntil: 'domcontentloaded' })
    const nav = page.locator('nav')
    await expect(nav).toBeVisible()
    // tenta abrir menu mobile se existir
    const toggle = page.locator('button[aria-label*="Menu"], button[aria-label*="menu"]').first()
    if (await toggle.count()) {
      await toggle.click().catch(() => {})
      await expect(nav).toBeVisible()
    }
  })
})

