#!/usr/bin/env bash
set -euo pipefail

ROOT_DIR="$(cd "$(dirname "${BASH_SOURCE[0]}")/.." && pwd)"
cd "$ROOT_DIR"

PULL=0
SKIP_NPM=0
SKIP_COMPOSER=0
SKIP_MIGRATE=0
SKIP_ASSETS=0
SKIP_CACHE=0
RESTART_QUEUE=1

for arg in "$@"; do
  case "$arg" in
    --pull) PULL=1 ;;
    --skip-npm) SKIP_NPM=1 ;;
    --skip-composer) SKIP_COMPOSER=1 ;;
    --skip-migrate) SKIP_MIGRATE=1 ;;
    --skip-assets) SKIP_ASSETS=1 ;;
    --skip-cache) SKIP_CACHE=1 ;;
    --no-queue-restart) RESTART_QUEUE=0 ;;
    *)
      echo "Argumento desconhecido: $arg" >&2
      exit 2
      ;;
  esac
done

if [[ "$PULL" == "1" ]]; then
  git pull --ff-only
fi

if [[ "$SKIP_COMPOSER" != "1" ]]; then
  composer install --no-dev --optimize-autoloader --no-interaction
fi

if [[ "$SKIP_NPM" != "1" && -f package.json ]]; then
  if ! command -v npm >/dev/null 2>&1; then
    if [[ -f public/build/manifest.json ]]; then
      echo "npm não encontrado; mantendo assets existentes (use --skip-npm para silenciar)" >&2
    else
      echo "npm não encontrado e assets não encontrados em public/build (use --skip-npm para pular)" >&2
      exit 1
    fi
  else
    if [[ -f package-lock.json ]]; then
      npm ci
    else
      npm install
    fi

    npm run build
  fi
fi

if [[ ! -e public/storage ]]; then
  php artisan storage:link || true
fi

if [[ "$SKIP_MIGRATE" != "1" ]]; then
  php artisan migrate --force
fi

if [[ "$SKIP_ASSETS" != "1" ]]; then
  php artisan filament:assets
  php artisan vendor:publish --tag=livewire:assets --force
fi

php artisan optimize:clear

if [[ "$SKIP_CACHE" != "1" ]]; then
  php artisan config:cache
  php artisan route:cache
  php artisan view:cache
fi

if [[ "$RESTART_QUEUE" == "1" ]]; then
  php artisan queue:restart || true
fi

php artisan about
