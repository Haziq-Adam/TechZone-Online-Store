# Product image setup and troubleshooting

Product records store a path in the `products.image_url` column. A matching image must exist inside the web root at that exact path. The database and the PHP page can work even when the image does not exist.

## Asset manifest

The reviewed TechZone source includes **all 14 product PNG images** referenced by the original private coursework database export. The image *filenames only* are listed in [`../database/expected_product_images.txt`](../database/expected_product_images.txt). No original user records are supplied.

Run the local check after extracting the repository:

```powershell
python tools/check_assets.py
```

It should report `Catalog product images: 14/14 found`.

## Fix missing images on InfinityFree

1. Open File Manager for the correct TechZone hosting account and open `htdocs/uploads/products/`.
2. Compare the filenames with `database/expected_product_images.txt`. File names (including capitalization and suffixes) must match exactly.
3. If any are missing, upload those `.png` files directly into `htdocs/uploads/products/`. **Do not** accidentally create `htdocs/uploads/products/products/`.
4. Open a specific URL such as `https://YOUR-TECHZONE-DOMAIN/uploads/products/FILENAME.png` to confirm the server serves it.
5. If the picture URL works but the website still has a blank image, look at that product's `image_url` in phpMyAdmin and inspect the generated `src` attribute.
6. Refresh the page without cache (Ctrl + F5).

**Important:** Having all the images in this GitHub ZIP does not prove the images are present in the separate InfinityFree account. Uploading or committing this repository does not update the live website automatically.
