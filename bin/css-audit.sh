#!/usr/bin/env bash
# CSS audit guard — catches the two bugs that silently drop rules in
# the browser CSS parser:
#
#   1. Orphan `}` (closing brace with no matching open) — the parser
#      stops at the error and the rest of the stylesheet is lost.
#      The home services/leadership 3-col/4-col grid bug of 2026-07-16
#      was caused by exactly this.
#
#   2. Indented rule at top level (no matching @media) — same root
#      cause: a mobile-only rule was de-indented without the @media
#      wrapper, so the parser treats it as orphan.
#
# Also reports any final non-zero brace depth at end of file (file is
# structurally broken).
#
# Exit codes:
#   0  — clean
#   1  — at least one issue found
#
# Wired into bin/git-commit-push.sh and the git pre-commit hook so
# broken CSS can never be pushed.

set -euo pipefail

ROOT="$(cd "$(dirname "${BASH_SOURCE[0]}")/.." && pwd)"
CSS_DIR="$ROOT/wp/wp-content/themes/underscores-child-bca/assets/css"

python3 - "$CSS_DIR" <<'PYEOF'
import re
import sys
from pathlib import Path

css_root = Path(sys.argv[1])

# Source files only — skip the generated dist bundle and the parent
# theme's child-theme.css (we don't own it).
sources = sorted(
    f for f in css_root.rglob('*.css')
    if '/dist/' not in str(f) and f.name != 'child-theme.css'
)

errors: list[str] = []
warnings: list[str] = []

for f in sources:
    text = f.read_text(encoding='utf-8')
    lines = text.split('\n')
    depth = 0
    in_comment = False

    for i, raw in enumerate(lines, 1):
        # Strip block comments so braces inside /* */ don't count.
        cleaned = ''
        j = 0
        while j < len(raw):
            if in_comment:
                end = raw.find('*/', j)
                if end == -1:
                    j = len(raw)
                    break
                j = end + 2
                in_comment = False
            elif raw[j:j+2] == '/*':
                in_comment = True
                j += 2
            else:
                cleaned += raw[j]
                j += 1

        opens = cleaned.count('{')
        closes = cleaned.count('}')
        new_depth = depth + opens - closes

        if new_depth < 0:
            errors.append(
                f'{f.relative_to(f.parents[2])}:{i}: orphan `}}` '
                f'(depth went {depth} → {new_depth})'
            )

        if (
            raw.startswith('  ')                  # indented (not top-level)
            and '{' in cleaned                    # and it's a rule
            and depth == 0                         # but we're at top level
            and not cleaned.lstrip().startswith('@media')
            and not cleaned.lstrip().startswith('@')
        ):
            # Looks like a mobile-only rule missing its @media wrapper
            sel = cleaned.split('{', 1)[0].strip()[:50]
            errors.append(
                f'{f.relative_to(f.parents[2])}:{i}: INDENTED RULE at depth 0 '
                f'(missing @media wrapper?): {sel}'
            )

        depth = max(0, new_depth)

    if depth != 0:
        errors.append(
            f'{f.relative_to(f.parents[2])}: final depth {depth} '
            f'(unclosed brace somewhere — file is broken)'
        )

if errors:
    print('❌ CSS audit FAILED:', file=sys.stderr)
    for e in errors:
        print(f'  {e}', file=sys.stderr)
    print(f'\nChecked {len(sources)} file(s).', file=sys.stderr)
    sys.exit(1)

print(f'✅ CSS audit passed ({len(sources)} source files checked)')
PYEOF
