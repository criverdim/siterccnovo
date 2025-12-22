#!/usr/bin/env bash
set -euo pipefail

ROOT_DIR="$(cd "$(dirname "${BASH_SOURCE[0]}")/.." && pwd)"
cd "$ROOT_DIR"

GIT_DIR=""
if command -v git >/dev/null 2>&1; then
  GIT_DIR="$(git rev-parse --git-dir 2>/dev/null || true)"
fi

if [[ -z "$GIT_DIR" || ! -d "$GIT_DIR" ]]; then
  echo "Repositório git não detectado em $ROOT_DIR; hooks não instalados."
  exit 0
fi

GIT_DIR_ABS="$(cd "$GIT_DIR" 2>/dev/null && pwd || true)"
if [[ -z "$GIT_DIR_ABS" ]]; then
  echo "Não foi possível resolver o diretório git; hooks não instalados."
  exit 0
fi

case "$GIT_DIR_ABS" in
  "$ROOT_DIR"/*) ;;
  *)
    echo "Diretório git fora do projeto ($GIT_DIR_ABS); hooks não instalados."
    exit 0
    ;;
esac

mkdir -p "$GIT_DIR/hooks"

install_one() {
  local name="$1"
  local src="scripts/git-hooks/$name"
  local dst="$GIT_DIR/hooks/$name"

  if [[ ! -f "$src" ]]; then
    echo "Hook não encontrado: $src" >&2
    exit 1
  fi

  if [[ -f "$dst" ]]; then
    cp -f "$dst" "$dst.bak.$(date -u +%Y%m%dT%H%M%SZ)"
  fi

  cp -f "$src" "$dst"
  chmod +x "$dst"
}

install_one post-merge

echo "Hooks instalados em $GIT_DIR/hooks"
