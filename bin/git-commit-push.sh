#!/usr/bin/env bash
# Auto-commit + push script — chạy sau mỗi task.
# Usage: ./bin/git-commit-push.sh [message]
#   Nếu không truyền message, dùng timestamp.

set -e

REPO_ROOT="$(cd "$(dirname "$0")/.." && pwd)"
cd "$REPO_ROOT"

# 1. Determine message
MSG="${1:-chore: task commit $(date '+%Y-%m-%d %H:%M:%S')}"

# 2. Stage work-product paths (theme code, ACF JSON, mu-plugins, scripts, docs, design assets)
STAGED=0
for path in \
  "wp/wp-content/themes/underscores-child-bca/" \
  "wp/wp-content/themes/underscores/" \
  "wp/wp-content/mu-plugins/" \
  "wp/wp-content/themes/underscores-child-bca/acf-json/" \
  "bin/" \
  "docs/" \
  "assets/" \
  ".gitignore"
do
  if [ -e "$path" ] && [ -n "$(git status --porcelain -- "$path" 2>/dev/null)" ]; then
    git add -- "$path"
    STAGED=1
  fi
done

# 3. Fallback: nếu không có path nào khớp, stage tất cả tracked
if [ "$STAGED" -eq 0 ]; then
  echo "[git-commit-push] No tracked-path changes; using git add -A"
  git add -A
fi

# 4. Bail nếu không có gì để commit
if git diff --cached --quiet; then
  echo "[git-commit-push] No changes to commit. Skipping push."
  exit 0
fi

# 5. Commit
git commit -m "$MSG"
echo "[git-commit-push] Committed: $MSG"

# 6. Push (chỉ khi remote tồn tại và không offline)
if git remote get-url origin >/dev/null 2>&1; then
  BRANCH="$(git rev-parse --abbrev-ref HEAD)"
  if git push origin "$BRANCH" 2>&1; then
    echo "[git-commit-push] Pushed to origin/$BRANCH"
  else
    echo "[git-commit-push] ⚠️  Push failed. Run 'git push' manually when network is up."
  fi
else
  echo "[git-commit-push] No 'origin' remote — commit saved locally."
fi
