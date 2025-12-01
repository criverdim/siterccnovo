import { defineConfig, devices } from '@playwright/test'

export default defineConfig({
  testDir: 'tests/e2e',
  timeout: 30_000,
  retries: 0,
  reporter: 'list',
  webServer: {
    command: 'php artisan serve --host=127.0.0.1 --port=8000',
    url: 'http://127.0.0.1:8000',
    reuseExistingServer: true,
    timeout: 120_000,
  },
  use: {
    baseURL: process.env.BASE_URL || 'http://127.0.0.1:8000',
    ignoreHTTPSErrors: true,
    trace: 'off',
    video: 'off',
    screenshot: 'off',
  },
  projects: [
    { name: 'chromium', use: { ...devices['Desktop Chrome'] } },
    { name: 'firefox', use: { ...devices['Desktop Firefox'] } },
  ],
})
