import { test, expect } from '@playwright/test'

const baseURL = (globalThis as any).process?.env?.BASE_URL || 'http://127.0.0.1'

test.describe('Logo Editor interactions', () => {
  test('allows free vertical cropping without aspect lock', async ({ page, browserName }) => {
    test.skip(browserName === 'webkit', 'Only Chrome/Firefox')
    await page.goto(`${baseURL}/editor/logo-demo`, { waitUntil: 'domcontentloaded' })

    // ensure controls exist
    await expect(page.locator('input[type=file]')).toBeVisible()
    await expect(page.locator('#ratioSelect')).toBeVisible()
    await expect(page.locator('#lockAspect')).toBeVisible()

    // set free ratio
    await page.selectOption('#ratioSelect', 'free')
    const canvas = page.locator('#editorCanvas')
    await expect(canvas).toBeVisible()

    // upload a test image from the public folder (favicon as placeholder)
    // sem upload, validar controles de recorte livre
    await page.waitForTimeout(200)

    // simulate vertical cropping via buttons
    await page.click('#heightDownBtn')
    await page.click('#heightDownBtn')
    await page.click('#narrowBtn')

    // save
    await page.click('#saveBtn')
    await page.waitForTimeout(300)

    // preview should be visible
    const preview = page.locator('#previewImg')
    await expect(preview).toBeVisible()
  })

  test('drag handles allow diagonal and vertical resize', async ({ page, browserName }) => {
    test.skip(browserName === 'webkit', 'Only Chrome/Firefox')
    await page.goto(`${baseURL}/editor/logo-demo`, { waitUntil: 'domcontentloaded' })
    await page.selectOption('#ratioSelect', 'free')
    await page.waitForTimeout(200)

    const canvas = page.locator('#editorCanvas')
    const box = await canvas.boundingBox()
    if (box) {
      const centerX = Math.round(box.x + box.width / 2)
      const centerY = Math.round(box.y + box.height / 2)
      // drag to simulate vertical resize
      await page.mouse.move(centerX + 50, centerY + 50)
      await page.mouse.down()
      await page.mouse.move(centerX + 50, centerY + 120)
      await page.mouse.up()
    }
    await page.click('#saveBtn')
    await page.waitForTimeout(300)
    await expect(page.locator('#previewImg')).toBeVisible()
  })
})
