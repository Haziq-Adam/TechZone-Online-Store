# Tech Stack

TechZone is a PHP and MySQL / MariaDB coursework project. This file lists technologies used in the supplied source code, not tools claimed from a live server.

## Frontend

- HTML and CSS (`style.css` and inline page styles)
- JavaScript (`main.js`)
- Spline viewer asset (`robot.splinecode`)
- Google Fonts loaded through the browser

## Backend

- PHP (uses `mysqli` for database access)
- Sessions for account and role-based pages
- Password hashing and verification provided by PHP

## Data

- MySQL / MariaDB
- Six tables: `users`, `categories`, `products`, `cart`, `stores`, `nfts`
- `database/schema.sql` for a clean installation; optional `database/demo_catalog.sql` for sample listings

## Development and deployment

- XAMPP and phpMyAdmin for local development
- PHP-compatible hosting (InfinityFree is the separate hosting target)
- GitHub Actions syntax checks in `.github/workflows/php-check.yml`

## Not implemented

- Checkout does not process real payments or generate completed orders.
- This project has not passed a production security audit.

## Included image checks

- `tools/check_assets.py` verifies 14 bundled product-image paths listed in `database/expected_product_images.txt`.
