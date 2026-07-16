#!/usr/bin/env bash
# Build a single concatenated CSS bundle for production.
#
# Concatenates all design-system CSS files in dependency order (tokens →
# base → site → sections → pages) into a single file. Reduces ~11 HTTP
# requests to 1, drops FCP by ~200-300ms.
#
# Source files stay readable for development; this script is also wired
# into bin/git-commit-push.sh so the bundle stays in sync with edits.
#
# Usage:
#   ./bin/build-css.sh           # build with auto-generated ver hash
#   ./bin/build-css.sh --watch   # rebuild on every change (uses fswatch if installed)

set -euo pipefail

ROOT="$(cd "$(dirname "${BASH_SOURCE[0]}")/.." && pwd)"
CSS_DIR="$ROOT/wp/wp-content/themes/underscores-child-bca/assets/css"
DIST_DIR="$ROOT/wp/wp-content/themes/underscores-child-bca/assets/dist"
OUT="$DIST_DIR/bca.bundle.css"

mkdir -p "$DIST_DIR"

build() {
    {
        # Header banner — DO NOT MOVE (used as cache-bust marker)
        printf '/* BCA Partners — production CSS bundle. DO NOT EDIT.\n   Source: assets/css/{tokens,base,site,sections,pages}/*.css\n   Build: bin/build-css.sh — run after editing any source file. */\n\n'

        # 1) Tokens (CSS custom properties). Order independent, but keep
        #    alphabetical for stable diffs.
        for f in "$CSS_DIR"/tokens/*.css; do
            [ -f "$f" ] || continue
            printf '/* ===== %s ===== */\n' "$(basename "$f")"
            cat "$f"
            printf '\n'
        done

        # 2) Base layer — reset + typography (no component styles).
        local base="$CSS_DIR/base.css"
        if [ -f "$base" ]; then
            printf '/* ===== base.css ===== */\n'
            cat "$base"
            printf '\n'
        fi

        # 3) Site layout — header, footer, mobile menu.
        local site="$CSS_DIR/site.css"
        if [ -f "$site" ]; then
            printf '/* ===== site.css ===== */\n'
            cat "$site"
            printf '\n'
        fi

        # 4) Sections — hero, contact-band, services, projects, leaders, etc.
        local sections="$CSS_DIR/sections.css"
        if [ -f "$sections" ]; then
            printf '/* ===== sections.css ===== */\n'
            cat "$sections"
            printf '\n'
        fi

        # 5) Page-specific overrides (currently near-empty stubs).
        if [ -d "$CSS_DIR/pages" ]; then
            for f in "$CSS_DIR"/pages/*.css; do
                [ -f "$f" ] || continue
                # Skip files that are 100% comments / empty
                if [ "$(grep -cvE '^\s*(/\*|\*|\*/|//|$)' "$f")" = "0" ]; then
                    continue
                fi
                printf '/* ===== pages/%s ===== */\n' "$(basename "$f")"
                cat "$f"
                printf '\n'
            done
        fi
    } > "$OUT"

    local size
    size=$(wc -c < "$OUT" | tr -d ' ')
    local hash
    hash=$(md5sum "$OUT" | cut -c1-8)
    printf 'Built %s (%s bytes, ver=%s)\n' "${OUT#$ROOT/}" "$size" "$hash"
}

build

# --watch mode (optional, requires fswatch)
if [ "${1:-}" = "--watch" ]; then
    if ! command -v fswatch >/dev/null 2>&1; then
        echo "fswatch not installed — brew install fswatch" >&2
        exit 1
    fi
    echo "Watching $CSS_DIR for changes... (Ctrl+C to stop)"
    fswatch -o "$CSS_DIR" | while read -r; do
        build
    done
fi
