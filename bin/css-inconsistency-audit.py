#!/usr/bin/env python3
"""
CSS inconsistency audit — find REAL bugs only.

Detects:
  1. CONFLICT — Same selector+property has 2+ different values in the
     same state (base / :hover / @media).
  2. SCALE DRIFT — Border-radius / min-height / aspect-ratio values
     that should come from a consistent scale (4px or 8px) but use
     arbitrary numbers.
  3. MAGIC PX — Px values that aren't in the common spacing/font scale.

Run as a one-off: ./bin/css-inconsistency-audit.py
"""

import re
import sys
import json
from pathlib import Path
from collections import defaultdict

CSS_DIR = Path('wp/wp-content/themes/underscores-child-bca/assets/css')

DECL_RE = re.compile(r'([a-z\-]+)\s*:\s*([^;{}]+)\s*(?:!important)?\s*;?')
RULE_RE = re.compile(r'([^{}]+?)\{([^{}]*)\}')

# Allowed spacing scale (4px-based)
ALLOWED_SPACING = {0, 4, 8, 12, 16, 20, 24, 28, 32, 40, 48, 56, 64, 80, 96, 100, 112, 120, 128, 144, 160, 200, 240, 280, 300, 320, 400, 480, 560, 600, 720, 800, 900, 1024, 1120, 1200, 1440}
# Allowed font scale (matches tokens/typography.css)
ALLOWED_FONTS = {11, 12, 13, 14, 15, 16, 17, 18, 20, 24, 28, 30, 32, 40, 44, 48, 52, 59, 60}


def parse_css(path: Path):
    """Parse CSS, tracking which @media wrapper (if any) each rule is inside.
    Returns list of (file, selectors, decls, line, media_key) tuples."""
    text = path.read_text(encoding='utf-8')
    rules = []
    media_stack = []  # list of @media conditions in scope
    pos = 0
    while pos < len(text):
        # Find next @media, @ rule, or normal rule
        at_media_m = re.search(r'@media\s+([^{]+)\{', text[pos:])
        rule_m = RULE_RE.search(text[pos:])
        if not rule_m:
            break
        if at_media_m and at_media_m.start() < rule_m.start():
            cond = at_media_m.group(1).strip()
            media_stack.append(cond)
            pos = pos + at_media_m.end()
            continue
        selector_text = rule_m.group(1).strip()
        body = rule_m.group(2)
        line_no = text.count('\n', 0, pos + rule_m.start()) + 1
        if selector_text.startswith('@'):
            pos = pos + rule_m.end()
            continue
        if selector_text == '' and body == '':
            # Empty rule (likely @media closer)
            pos = pos + rule_m.end()
            media_stack.pop() if media_stack else None
            continue
        selectors = [s.strip() for s in selector_text.split(',') if s.strip()]
        body_clean = re.sub(r'/\*.*?\*/', '', body, flags=re.S)
        decls = {}
        for d in DECL_RE.finditer(body_clean):
            prop = d.group(1).lower()
            val = d.group(2).strip()
            decls[prop] = val
        media_key = ' | '.join(media_stack) if media_stack else ''
        rules.append((str(path.relative_to(path.parents[2])), selectors, decls, line_no, media_key))
        pos = pos + rule_m.end()
        # Heuristic: if body has more closes than opens, an @media ended
        opens = body.count('{')
        closes = body.count('}')
        for _ in range(closes - opens):
            if media_stack:
                media_stack.pop()
    return rules


def find_conflicts(rules):
    """Find cases where the SAME selector+property has 2+ different values
    in the same state (base / :hover / @media).

    - @media wrappers are state (responsive override = intentional)
    - :hover, :focus, etc. are state (interaction override = intentional)
    """
    by_key = defaultdict(list)  # (selector, prop, state) -> [(val, file, line)]
    for file, selectors, decls, line, media_key in rules:
        for sel in selectors:
            # Extract stateful pseudo
            pseudo = ''
            pseudo_m = re.search(r':(hover|focus|focus-visible|active|visited|disabled)$', sel)
            if pseudo_m:
                pseudo = pseudo_m.group(0)
                base = sel[:-len(pseudo)]
            else:
                base = sel
            state = (media_key, pseudo)
            for prop, val in decls.items():
                by_key[(base, prop, state)].append((val, file, line))

    conflicts = []
    for (base, prop, state), entries in by_key.items():
        if len(entries) < 2:
            continue
        distinct = {v for v, _, _ in entries}
        if len(distinct) > 1:
            media, pseudo = state
            display = f"@media ({media}){pseudo}" if media or pseudo else "(base)"
            conflicts.append({
                'selector': base,
                'state': display,
                'property': prop,
                'values': [(v, f, ln) for v, f, ln in entries],
            })
    return conflicts


def find_scale_drift(rules):
    """Find px values that fall outside the allowed scale."""
    findings = []
    for file, selectors, decls, line, _ in rules:
        for prop, val in decls.items():
            for m in re.finditer(r'(?<!\()([\d.]+)px(?!\s*[)])', val):
                num_str = m.group(1)
                try:
                    num = int(float(num_str))
                except (ValueError, OverflowError):
                    continue
                if num == 0:
                    continue
                scale = ALLOWED_FONTS if prop == 'font-size' else ALLOWED_SPACING
                if num not in scale:
                    findings.append({
                        'file': file, 'line': line,
                        'selector': ', '.join(selectors),
                        'prop': prop, 'value': val, 'magic': f"{num}px",
                    })
    return findings


def find_magic_colors(rules):
    """Find raw hex colors that don't use token variables."""
    findings = []
    for file, selectors, decls, line, _ in rules:
        for prop, val in decls.items():
            for m in re.finditer(r'#([0-9a-fA-F]{3,8})\b', val):
                findings.append({
                    'file': file, 'line': line,
                    'selector': ', '.join(selectors),
                    'prop': prop, 'value': val, 'hex': f"#{m.group(1)}",
                })
    return findings


def main():
    json_mode = '--json' in sys.argv
    rules = []
    for f in sorted(CSS_DIR.rglob('*.css')):
        if '/dist/' in str(f) or f.name == 'child-theme.css':
            continue
        rules.extend(parse_css(f))

    conflicts = find_conflicts(rules)
    scale_drift = find_scale_drift(rules)
    magic_colors = find_magic_colors(rules)

    if json_mode:
        print(json.dumps({
            'rules': len(rules),
            'conflicts': conflicts,
            'scale_drift': scale_drift,
            'magic_colors': magic_colors,
        }, indent=2, default=str))
        return

    print(f"=== CSS Inconsistency Audit ===")
    print(f"Scanned {len(rules)} rules across {CSS_DIR}/")
    print(f"Filters: @media + :hover/:focus treated as STATE (intentional).")
    print()

    if conflicts:
        print(f"--- {len(conflicts)} CONFLICTS (same selector+prop, different values, same state) ---")
        for c in conflicts:
            print(f"\n  {c['selector']}  {{ {c['property']} }}  [{c['state']}]")
            for v, f, ln in c['values']:
                print(f"    = {v!r:50}  ({f}:{ln})")
    else:
        print("✓ No selector+property conflicts")
    print()

    if scale_drift:
        print(f"--- {len(scale_drift)} scale-drift values (px outside design scale) ---")
        for d in scale_drift:
            print(f"  {d['file']}:{d['line']}  {d['selector'][:30]:30}  {d['prop']}: {d['value']}  (magic: {d['magic']})")
    else:
        print("✓ No scale drift")
    print()

    if magic_colors:
        print(f"--- {len(magic_colors)} raw hex colors (consider tokenizing) ---")
        for c in magic_colors[:10]:
            print(f"  {c['file']}:{c['line']}  {c['selector'][:30]:30}  {c['prop']}: {c['value'][:50]}")
        if len(magic_colors) > 10:
            print(f"  ... and {len(magic_colors) - 10} more")
    else:
        print("✓ No raw colors")


if __name__ == '__main__':
    main()
