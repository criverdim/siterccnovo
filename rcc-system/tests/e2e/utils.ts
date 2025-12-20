export async function loginAdmin(page: any, baseURL: string, email?: string, password?: string) {
  const params = new URLSearchParams()
  if (email) params.set('email', email)
  if (password) { params.set('password', password); params.set('create', '1') }
  const loginUrl = `${baseURL}/testing/login-admin?${params.toString()}`
  await page.goto(loginUrl, { waitUntil: 'domcontentloaded' }).catch(() => {})
  const ok = await page.locator('text=ok').count().catch(() => 0)
  if (!ok) {
    await page.goto(`${baseURL}/admin/login`, { waitUntil: 'domcontentloaded' })
    const emailSel = 'input[type="email"], input[name="email"], input[autocomplete="username"]'
    const passSel = 'input[type="password"], input[name="password"], input[autocomplete="current-password"]'
    await page.waitForSelector(emailSel, { timeout: 20000 })
    if (email) {
      await page.fill(emailSel, email)
    }
    if (password) {
      await page.fill(passSel, password)
    }
    const submit = page.locator('button[type="submit"], button:has-text("Sign in"), button:has-text("Entrar"), button:has-text("Login")').first()
    await Promise.all([
      page.waitForURL('**/admin**', { timeout: 45000 }).catch(() => {}),
      submit.click(),
    ])
  }
  await page.goto(`${baseURL}/admin`, { waitUntil: 'domcontentloaded' })
  const sidebar = page.locator('.fi-sidebar')
  await sidebar.waitFor({ state: 'visible', timeout: 10000 }).catch(async () => {
    await page.waitForLoadState('networkidle').catch(() => {})
    await page.waitForSelector('.fi-topbar, .fi-sidebar', { timeout: 5000 }).catch(() => {})
  })
}
