#!/usr/bin/env python3
"""Verify that TechZone product pictures referenced by the original catalog exist locally.

This does not access the live website or MySQL database. The manifest contains
public image paths only; no personal records are included.
"""
from pathlib import Path
import sys

root = Path(__file__).resolve().parents[1]
manifest = root / 'database' / 'expected_product_images.txt'
paths = [x.strip() for x in manifest.read_text(encoding='utf-8').splitlines()
         if x.strip() and not x.lstrip().startswith('#')]
missing = [path for path in paths if not (root / path).is_file()]
print(f'Catalog product images: {len(paths) - len(missing)}/{len(paths)} found')
for name in missing:
    print(f'MISSING: {name}')
sys.exit(1 if missing else 0)
