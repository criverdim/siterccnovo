import { defineConfig, devices } from '@playwright/test'

const SITE_BASE_URL = (globalThis as any).process?.env?.SITE_BASE_URL

export default defineConfig({
  testDir: 'tests/e2e',
  timeout: 90_000,
  workers: 1,
  retries: 0,
  reporter: [
    ['html', { outputFolder: 'playwright-report', open: 'never' }],
    ['junit', { outputFile: 'playwright-report/results.xml' }],
    ['json', { outputFile: 'playwright-report/results.json' }],
  ],
  globalSetup: './tests/e2e/global-setup.ts',
  webServer: [
    {
      command: 'APP_ENV=testing PLAYWRIGHT=1 APP_URL=http://127.0.0.1:8000 SESSION_SECURE_COOKIE=false DB_CONNECTION=sqlite DB_DATABASE=database/database.sqlite SESSION_DRIVER=file CACHE_STORE=database php artisan serve --host=127.0.0.1 --port=8000',
      url: 'http://127.0.0.1:8000',
      reuseExistingServer: true,
      timeout: 120_000,
    },
  ],
  use: {
    baseURL: (globalThis as any).process?.env?.BASE_URL || 'http://127.0.0.1:8000',
    ignoreHTTPSErrors: true,
    actionTimeout: 30_000,
    navigationTimeout: 45_000,
    trace: 'on-first-retry',
    video: 'retain-on-failure',
    screenshot: 'only-on-failure',
  },
  projects: [
    {
      name: 'admin-chromium',
      testIgnore: /site-.*\.spec\.ts/,
      use: { ...devices['Desktop Chrome'], baseURL: (globalThis as any).process?.env?.BASE_URL || 'http://127.0.0.1:8000' },
    },
    {
      name: 'admin-firefox',
      testIgnore: /site-.*\.spec\.ts/,
      use: { ...devices['Desktop Firefox'], baseURL: (globalThis as any).process?.env?.BASE_URL || 'http://127.0.0.1:8000' },
    },
    ...(SITE_BASE_URL ? [
      {
        name: 'site-chromium',
        testMatch: /site-.*\.spec\.ts/,
        use: { ...devices['Desktop Chrome'], baseURL: SITE_BASE_URL },
      },
      {
        name: 'site-firefox',
        testMatch: /site-.*\.spec\.ts/,
        use: { ...devices['Desktop Firefox'], baseURL: SITE_BASE_URL },
      },
    ] : []),
  ],
})
