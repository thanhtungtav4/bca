#!/usr/bin/env bash
# Deploy BCA Partners to live (bca.nttung.dev) — code + cache sync.
#
# Does:
#   1. rsync theme code (underscores + underscores-child-bca) via sync-theme.sh
#   2. Flush WP cache on live
#   3. Write deploy marker (timestamp) to wp-content/deploy-marker.txt
#
# For DB/content updates that can't be done via code (copyright text, ACF
# option values, image imports), do them manually via `ssh ... "wp eval ..."`
# or write a one-off script. There is no auto DB migration step here by
# design — the live DB is treated as the source of truth for content.
#
# Does NOT touch:
#   - .deploy.env (security — contains SSHPASS, never committed)
#   - Database content / options / posts
#
# Usage:
#   ./bin/sync-live.sh

set -euo pipefail

ROOT="$(cd "$(dirname "${BASH_SOURCE[0]}")/.." && pwd)"
cd "$ROOT"

# 0. Pre-flight: .deploy.env must exist (not committed — security).
if [ ! -f .deploy.env ]; then
    echo "❌ .deploy.env not found. Copy from .deploy.env.example and fill in." >&2
    exit 1
fi
set -a; . ./.deploy.env; set +a

# Helper: SSH with sshpass using env var (avoids leaking password to ps).
_ssh() {
    sshpass -e ssh -p "${SSH_PORT}" -o StrictHostKeyChecking=no "${SSH_USER}@${SSH_HOST}" "$@"
}

echo "━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━"
echo "BCA Partners — deploy to ${SSH_HOST}"
echo "━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━"

# 1. Code sync — delegate to existing sync-theme.sh (no re-implementation).
echo ""
echo "→ [1/3] rsync theme code"
bash "$ROOT/sync-theme.sh"

# 2. Cache flush + version stamp.
echo ""
echo "→ [2/3] flush WP cache"
_ssh 'cd /var/www/bca.nttung.dev/htdocs
   wp cache flush --allow-root 2>&1 | tail -1'

echo ""
echo "→ [3/3] write deploy marker"
_ssh 'cd /var/www/bca.nttung.dev/htdocs
   echo "deploy: $(date -u +%FT%TZ)" > wp-content/deploy-marker.txt
   echo "  marker written"'

echo ""
echo "━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━"
echo "✓ Deployed: https://bca.nttung.dev"
echo "━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━"
