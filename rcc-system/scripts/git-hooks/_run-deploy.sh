#!/usr/bin/env bash
set -euo pipefail

ROOT_DIR="$(git rev-parse --show-toplevel)"
cd "$ROOT_DIR"

if [[ ! -f ".env" ]]; then
  exit 0
fi

APP_ENV="$(php -r '$env=@file_get_contents(".env"); if($env===false){exit(0);} foreach(preg_split("/\\r\\n|\\n|\\r/",$env) as $line){ if(str_starts_with($line,"APP_ENV=")){ $v=trim(substr($line,8)); $v=trim($v,"\"'\''"); echo $v; break; } }' 2>/dev/null || true)"

if [[ "$APP_ENV" != "production" ]]; then
  exit 0
fi

mkdir -p storage/logs

HOOK_NAME="${1:-hook}"

{
  echo "=== $HOOK_NAME deploy: $(date -u +%Y-%m-%dT%H:%M:%SZ) ==="
  composer run -n deploy
  echo "=== done ==="
} >> storage/logs/deploy.log 2>&1
