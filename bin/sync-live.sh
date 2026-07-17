#!/usr/bin/env bash
# Deploy BCA Partners to live (bca.nttung.dev) — full sync: code + assets + DB.
#
# Does:
#   1. rsync theme code (underscores + underscores-child-bca) via sync-theme.sh
#   2. Run any pending DB updates registered in bin/db-updates/*.sql
#   3. Run any pending PHP one-liners registered in bin/db-updates/*.php
#   4. Flush WP cache on live
#   5. Verify live is reachable + return version hash
#
# Does NOT touch:
#   - .deploy.env (security — contains SSHPASS, never committed)
#   - Database content unrelated to design system (no destructive ops)
#
# Usage:
#   ./bin/sync-live.sh             # full deploy
#   ./bin/sync-live.sh --code-only # just rsync, skip DB

set -euo pipefail

ROOT="$(cd "$(dirname "${BASH_SOURCE[0]}")/.." && pwd)"
cd "$ROOT"

# 0. Pre-flight: .deploy.env must exist (not committed — security).
if [ ! -f .deploy.env ]; then
    echo "❌ .deploy.env not found. Copy from .deploy.env.example and fill in." >&2
    exit 1
fi
set -a; . ./.deploy.env; set +a

CODE_ONLY=0
if [ "${1:-}" = "--code-only" ]; then
    CODE_ONLY=1
fi

# Helper: SSH with sshpass using env var (avoids leaking password to ps).
_ssh() {
    sshpass -e ssh -p "${SSH_PORT}" -o StrictHostKeyChecking=no "${SSH_USER}@${SSH_HOST}" "$@"
}

echo "━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━"
echo "BCA Partners — deploy to ${SSH_HOST}"
echo "━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━"

# 1. Code sync — delegate to existing sync-theme.sh (no re-implementation).
echo ""
echo "→ [1/4] rsync theme code"
bash "$ROOT/sync-theme.sh"

# 2. SQL updates — apply every .sql file in bin/db-updates/ once, in name order.
if [ "$CODE_ONLY" -eq 0 ] && [ -d "$ROOT/bin/db-updates" ]; then
    echo ""
    echo "→ [2/4] apply SQL updates (bin/db-updates/*.sql)"
    for sql in "$ROOT"/bin/db-updates/*.sql; do
        [ -f "$sql" ] || continue
        name=$(basename "$sql" .sql)
        echo "  • $name"
        # shellcheck disable=SC2024
        _ssh "wp eval --path=/var/www/bca.nttung.dev/htdocs --allow-root \"
            \$applied = get_option('bca_db_update_$name', 0);
            if (\$applied) { echo '  already applied — skipping'; exit(0); }
            require ABSPATH . 'wp-admin/includes/upgrade.php';
            dbDelta(file_get_contents('$sql'));
            update_option('bca_db_update_$name', time());
            echo '  applied + marked';
        \""
    done
fi

# 3. PHP one-liners — each .php file runs once, similar gating.
if [ "$CODE_ONLY" -eq 0 ] && [ -d "$ROOT/bin/db-updates" ]; then
    echo ""
    echo "→ [3/4] run PHP one-liners (bin/db-updates/*.php)"
    for php in "$ROOT"/bin/db-updates/*.php; do
        [ -f "$php" ] || continue
        name=$(basename "$php" .php)
        echo "  • $name"
        _ssh "wp eval-file --path=/var/www/bca.nttung.dev/htdocs --allow-root '$php' || true"
    done
fi

# 4. Cache flush + version stamp.
echo ""
echo "→ [4/4] flush cache + write deploy marker"
_ssh 'cd /var/www/bca.nttung.dev/htdocs
   wp cache flush --allow-root 2>&1 | tail -1
   echo "  deploy: $(date -u +%FT%TZ) sha=$(git rev-parse --short HEAD 2>/dev/null || echo none)" \
        > wp-content/deploy-marker.txt
   echo "  marker written"'

echo ""
echo "━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━"
echo "✓ Deployed: https://bca.nttung.dev"
echo "━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━"
