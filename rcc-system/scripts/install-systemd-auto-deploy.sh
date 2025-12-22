#!/usr/bin/env bash
set -euo pipefail

ROOT_DIR="$(cd "$(dirname "${BASH_SOURCE[0]}")/.." && pwd)"
cd "$ROOT_DIR"

if [[ ! -d /run/systemd/system ]]; then
  echo "systemd não detectado; instale via cron ou outro agendador."
  exit 0
fi

UNIT_DIR="/etc/systemd/system"
SERVICE_SRC="scripts/systemd/rcc-auto-deploy.service"
TIMER_SRC="scripts/systemd/rcc-auto-deploy.timer"
SERVICE_DST="$UNIT_DIR/rcc-auto-deploy.service"
TIMER_DST="$UNIT_DIR/rcc-auto-deploy.timer"

if [[ ! -w "$UNIT_DIR" ]]; then
  echo "Sem permissão para escrever em $UNIT_DIR."
  echo "Copie manualmente:"
  echo "  sudo cp $ROOT_DIR/$SERVICE_SRC $SERVICE_DST"
  echo "  sudo cp $ROOT_DIR/$TIMER_SRC $TIMER_DST"
  echo "  sudo systemctl daemon-reload"
  echo "  sudo systemctl enable --now rcc-auto-deploy.timer"
  exit 0
fi

cp -f "$SERVICE_SRC" "$SERVICE_DST"
cp -f "$TIMER_SRC" "$TIMER_DST"
systemctl daemon-reload
systemctl enable --now rcc-auto-deploy.timer
systemctl status rcc-auto-deploy.timer --no-pager
