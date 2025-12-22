#!/usr/bin/env bash
set -euo pipefail

ROOT_DIR="$(cd "$(dirname "${BASH_SOURCE[0]}")/.." && pwd)"
cd "$ROOT_DIR"

if ! command -v git >/dev/null 2>&1; then
  echo "git não encontrado" >&2
  exit 1
fi

if ! git rev-parse --is-inside-work-tree >/dev/null 2>&1; then
  echo "Repositório git não detectado em $ROOT_DIR" >&2
  exit 1
fi

git config core.hooksPath scripts/git-hooks
chmod +x scripts/git-hooks/_run-deploy.sh scripts/git-hooks/post-merge scripts/git-hooks/post-checkout scripts/git-hooks/post-rewrite
echo "Auto-deploy habilitado via core.hooksPath=scripts/git-hooks"
