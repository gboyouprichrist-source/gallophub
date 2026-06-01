#!/usr/bin/env python3
"""
Generate screenshot.png for the GallopHub WordPress theme.
Requires: Pillow  (pip install Pillow)
Output:   screenshot.png  (1200x900 px — WP recommends 1200x900)
Run from the theme root: python3 generate-screenshot.py
"""

import os
import sys

try:
    from PIL import Image, ImageDraw, ImageFont
except ImportError:
    sys.exit("Pillow is required. Install it with:  pip install Pillow")

# ── Colours matching the design system ──
NAVY   = (26,  60,  94)   # --navy
GOLD   = (200, 169, 81)   # --gold
WHITE  = (255, 255, 255)
LIGHT  = (248, 249, 250)  # --muted
BORDER = (229, 231, 235)  # --border
SUB    = (107, 114, 128)  # --sub

W, H = 1200, 900

img  = Image.new("RGB", (W, H), color=LIGHT)
draw = ImageDraw.Draw(img)

# ── Top bar ──
draw.rectangle([0, 0, W, 36], fill=NAVY)
draw.text((W // 2, 18), "Venta directa · 30+ caballos disponibles · España · Entrega Europa",
          fill=WHITE, anchor="mm")

# ── Navbar ──
draw.rectangle([0, 36, W, 96], fill=WHITE)
draw.line([0, 96, W, 96], fill=BORDER, width=1)

# Logo
logo_x = 40
draw.rectangle([logo_x, 50, logo_x + 36, 86], fill=NAVY, outline=NAVY)
draw.text((logo_x + 18, 68), "GH", fill=GOLD, anchor="mm")
draw.text((logo_x + 55, 60), "Gallop", fill=NAVY, anchor="lm")
draw.text((logo_x + 100, 60), "Hub", fill=GOLD, anchor="lm")

# Nav links
nav_labels = ["Todos los caballos", "Dressage", "Saut", "Western", "Loisirs"]
nx = 260
for lbl in nav_labels:
    draw.text((nx, 68), lbl, fill=SUB, anchor="lm")
    nx += len(lbl) * 7 + 24

# Right: WhatsApp + CTA
draw.rectangle([W - 140, 54, W - 46, 82], fill=GOLD)
draw.text((W - 93, 68), "Contactar", fill=NAVY, anchor="mm")

# ── Hero section ──
draw.rectangle([0, 96, W, 360], fill=NAVY)

# Hero text
draw.text((W // 2, 190), "GallopHub", fill=WHITE, anchor="mm")
draw.text((W // 2, 240), "Vente de chevaux de sport · Espagne", fill=GOLD, anchor="mm")
draw.text((W // 2, 278), "30+ caballos disponibles · Dressage · Jumping · Western", fill=(*WHITE, 180), anchor="mm")

# CTA buttons in hero
draw.rectangle([W // 2 - 130, 302, W // 2 - 10, 338], fill=GOLD)
draw.text((W // 2 - 70, 320), "Ver caballos", fill=NAVY, anchor="mm")
draw.rectangle([W // 2 + 10, 302, W // 2 + 140, 338], fill=(*NAVY,), outline=GOLD, width=2)
draw.text((W // 2 + 75, 320), "Contactar", fill=WHITE, anchor="mm")

# ── Stats bar ──
draw.rectangle([0, 360, W, 410], fill=WHITE)
draw.line([0, 410, W, 410], fill=BORDER, width=1)
stats = [("30+", "Caballos disponibles"), ("3", "Países cubiertos"), ("100%", "Entrega Europa"), ("5★", "Satisfacción")]
sw = W // len(stats)
for i, (num, label) in enumerate(stats):
    cx = sw * i + sw // 2
    draw.text((cx, 376), num, fill=NAVY, anchor="mm")
    draw.text((cx, 395), label, fill=SUB, anchor="mm")

# ── Horse cards grid ──
card_y = 430
card_w = 340
card_h = 220
card_gap = 30
cards_start_x = (W - (3 * card_w + 2 * card_gap)) // 2

horse_names = [
    ("Andaluza Bella", "Yegua · 8 ans · 15.500 €"),
    ("Don Quijote",    "Étalon · 6 ans · 22.000 €"),
    ("Lola PRE",       "Jument · 10 ans · 9.800 €"),
]

for i, (name, sub) in enumerate(horse_names):
    cx = cards_start_x + i * (card_w + card_gap)

    # Card background
    draw.rounded_rectangle([cx, card_y, cx + card_w, card_y + card_h],
                            radius=10, fill=WHITE, outline=BORDER)

    # Image placeholder
    draw.rectangle([cx + 1, card_y + 1, cx + card_w - 1, card_y + 130],
                   fill=(200, 210, 220))
    draw.text((cx + card_w // 2, card_y + 65), "[ Photo ]", fill=SUB, anchor="mm")

    # Status badge
    draw.rounded_rectangle([cx + 12, card_y + 10, cx + 90, card_y + 30],
                            radius=4, fill=(16, 185, 129))
    draw.text((cx + 51, card_y + 20), "Disponible", fill=WHITE, anchor="mm")

    # Name + meta
    draw.text((cx + 16, card_y + 145), name, fill=NAVY, anchor="lm")
    draw.text((cx + 16, card_y + 165), sub, fill=SUB, anchor="lm")

    # Price
    price = sub.split("·")[-1].strip()
    draw.text((cx + card_w - 16, card_y + 145), price, fill=GOLD, anchor="rm")

    # CTA
    draw.rounded_rectangle([cx + 16, card_y + 185, cx + card_w - 16, card_y + 210],
                            radius=6, fill=NAVY)
    draw.text((cx + card_w // 2, card_y + 198), "Voir le cheval", fill=WHITE, anchor="mm")

# ── Footer strip ──
draw.rectangle([0, H - 60, W, H], fill=NAVY)
draw.text((W // 2, H - 30), "© 2024 GallopHub — contact@gallophub.es", fill=(*WHITE, 160), anchor="mm")

# ── Save ──
out_path = os.path.join(os.path.dirname(__file__), "screenshot.png")
img.save(out_path, "PNG", optimize=True)
print(f"screenshot.png saved → {out_path}  ({W}×{H})")
