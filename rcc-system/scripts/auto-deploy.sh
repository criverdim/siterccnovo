#!/usr/bin/env bash
set -euo pipefail

ROOT_DIR="$(cd "$(dirname "${BASH_SOURCE[0]}")/.." && pwd)"
cd "$ROOT_DIR"

REMOTE="${REMOTE:-origin}"
BRANCH="${BRANCH:-main}"

for arg in "$@"; do
  case "$arg" in
    --remote=*) REMOTE="${arg#*=}" ;;
    --branch=*) BRANCH="${arg#*=}" ;;
    *)
      echo "Argumento desconhecido: $arg" >&2
      exit 2
      ;;
  esac
done

if ! command -v git >/dev/null 2>&1; then
  exit 0
fi

if ! git rev-parse --is-inside-work-tree >/dev/null 2>&1; then
  exit 0
fi

if [[ ! -f ".env" ]]; then
  exit 0
fi

APP_ENV="$(php -r '$env=@file_get_contents(".env"); if($env===false){exit(0);} foreach(preg_split("/\\r\\n|\\n|\\r/",$env) as $line){ if(str_starts_with($line,"APP_ENV=")){ $v=trim(substr($line,8)); $v=trim($v,"\"'\''"); echo $v; break; } }' 2>/dev/null || true)"
if [[ "$APP_ENV" != "production" ]]; then
  exit 0
fi

LOCK_FILE="/tmp/rcc-auto-deploy.lock"
exec 9>"$LOCK_FILE"
if ! flock -n 9; then
  exit 0
fi

mkdir -p storage/logs

{
  echo "=== auto-deploy: $(date -u +%Y-%m-%dT%H:%M:%SZ) ==="
  if ! git remote get-url "$REMOTE" >/dev/null 2>&1; then
    echo "remote inválido: $REMOTE"
    echo "=== done ==="
    exit 0
  fi
  git fetch --prune "$REMOTE"
  LOCAL_REF="$(git rev-parse HEAD)"
  REMOTE_REF="$(git rev-parse "$REMOTE/$BRANCH")"
  echo "local=$LOCAL_REF remote=$REMOTE_REF"
  if [[ "$LOCAL_REF" != "$REMOTE_REF" ]]; then
    git pull --ff-only "$REMOTE" "$BRANCH"
    composer run -n deploy
  else
    echo "sem alterações"
  fi
  echo "=== done ==="
} >> storage/logs/deploy.log 2>&1
