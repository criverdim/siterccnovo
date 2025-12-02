import { defineConfig, devices } from '@playwright/test'

export default defineConfig({
  testDir: 'tests/e2e',
  timeout: 90_000,
  workers: 1,
  retries: 0,
  reporter: 'list',
  globalSetup: './tests/e2e/global-setup.ts',
  webServer: {
    command: 'APP_ENV=testing PLAYWRIGHT=1 DB_CONNECTION=sqlite DB_DATABASE=database/database.sqlite SESSION_DRIVER=file CACHE_STORE=database php artisan serve --host=127.0.0.1 --port=8000',
    url: 'http://127.0.0.1:8000',
    reuseExistingServer: true,
    timeout: 120_000,
  },
  use: {
    baseURL: (globalThis as any).process?.env?.BASE_URL || 'http://127.0.0.1:8000',
    ignoreHTTPSErrors: true,
    actionTimeout: 30_000,
    navigationTimeout: 45_000,
    trace: 'off',
    video: 'off',
    screenshot: 'off',
  },
  projects: [
    { name: 'chromium', use: { ...devices['Desktop Chrome'] } },
    { name: 'firefox', use: { ...devices['Desktop Firefox'] } },
  ],
})
