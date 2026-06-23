#!/usr/bin/env python3
"""Gera logo horizontal: mascote + Guru do Desconto + economia de verdade."""

from PIL import Image, ImageDraw, ImageFont
import os

BASE = os.path.dirname(os.path.dirname(os.path.abspath(__file__)))
SRC = os.path.join(BASE, "images/Guru_sem_fundo.png")
OUTS = [
    os.path.join(BASE, "images/guru_logo_horizontal.png"),
    os.path.join(BASE, "wp-content/themes/guru-do-desconto/assets/images/guru_logo_horizontal.png"),
]

ORANGE = (242, 116, 5, 255)
GRAY = (107, 114, 128, 255)


def load_font(size: int, bold: bool = False):
    candidates = [
        "/System/Library/Fonts/Supplemental/Arial Bold.ttf" if bold else "/System/Library/Fonts/Supplemental/Arial.ttf",
        "/System/Library/Fonts/Helvetica.ttc",
    ]
    for path in candidates:
        if os.path.exists(path):
            return ImageFont.truetype(path, size)
    return ImageFont.load_default()


def remove_black_bg(img: Image.Image) -> Image.Image:
    img = img.convert("RGBA")
    px = img.load()
    w, h = img.size
    for y in range(h):
        for x in range(w):
            r, g, b, a = px[x, y]
            if r < 35 and g < 35 and b < 35:
                px[x, y] = (0, 0, 0, 0)
    return img


def main():
    mascot = remove_black_bg(Image.open(SRC))
    target_h = 120
    ratio = target_h / mascot.height
    target_w = int(mascot.width * ratio)
    mascot = mascot.resize((target_w, target_h), Image.Resampling.LANCZOS)

    pad_x, pad_y, gap = 24, 20, 20
    canvas_w = pad_x + target_w + gap + 430 + pad_x
    canvas_h = target_h + pad_y * 2
    canvas = Image.new("RGBA", (canvas_w, canvas_h), (255, 255, 255, 255))
    canvas.paste(mascot, (pad_x, pad_y), mascot)

    draw = ImageDraw.Draw(canvas)
    font_title = load_font(40, bold=True)
    font_sub = load_font(21, bold=False)

    text_x = pad_x + target_w + gap
    text_y = pad_y + 20
    draw.text((text_x, text_y), "Guru do Desconto", fill=ORANGE, font=font_title)
    draw.text((text_x, text_y + 50), "economia de verdade", fill=GRAY, font=font_sub)

    final = canvas.convert("RGB")
    for out in OUTS:
        os.makedirs(os.path.dirname(out), exist_ok=True)
        final.save(out, "PNG", optimize=True)
        print(f"OK {out} ({final.size[0]}x{final.size[1]})")


if __name__ == "__main__":
    main()
