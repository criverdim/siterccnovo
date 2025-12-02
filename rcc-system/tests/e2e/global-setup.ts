import { execSync } from 'node:child_process'
import fs from 'node:fs'
import path from 'node:path'

export default async () => {
  process.env.APP_ENV = 'testing'
  process.env.APP_DEBUG = 'false'
  process.env.DB_CONNECTION = 'sqlite'
  const dbPath = path.resolve('database', 'database.sqlite')
  process.env.DB_DATABASE = dbPath
  process.env.SESSION_DRIVER = 'file'
  process.env.CACHE_STORE = 'database'

  try {
    if (!fs.existsSync('database')) {
      fs.mkdirSync('database', { recursive: true })
    }
    if (!fs.existsSync(dbPath)) {
      fs.writeFileSync(dbPath, '')
    }
  } catch {}

  try {
    execSync('php artisan config:clear', { stdio: 'inherit' })
  } catch {}
  try {
    execSync('php artisan migrate --force', { stdio: 'inherit' })
  } catch {}
  try {
    execSync('php artisan db:seed --force', { stdio: 'inherit' })
  } catch {}
  try {
    execSync('php artisan db:seed --class=SettingsSeeder --force', { stdio: 'inherit' })
  } catch {}
}
