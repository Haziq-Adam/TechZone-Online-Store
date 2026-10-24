# Running TechZone locally

These steps use XAMPP on Windows. You will need PHP 8.0 or later (the code uses `match` and `str_starts_with`) and MySQL or MariaDB.

1. Download and extract the repository. Put its contents in `C:\xampp\htdocs\techzone`.
2. Start **Apache** and **MySQL** in the XAMPP Control Panel.
3. Open `http://localhost/phpmyadmin`. Create a blank database named `techzone_demo` (use `utf8mb4` if available).
4. Select the database and use **Import** to upload [`../database/schema.sql`](../database/schema.sql). **Do not** import into a database that already holds important data.
5. If you want sample categories and products on screen, import [`../database/demo_catalog.sql`](../database/demo_catalog.sql) into the same database. This step is optional. Do it only once on a fresh database.
6. Copy `config.local.example.php` to `config.local.php` in the root of the project. For a default XAMPP installation, the hostname is typically `localhost`, username `root`, and the local MySQL password may be blank. Match your actual setup.
7. Open `http://localhost/techzone/` to test the home page. Register a fresh test user if you need to test the cart and seller pages.

There is no default administrator account in the repository. Admin access requires a user with the `admin` role, which should only be assigned by a trusted database administrator in a **local test database**. Do not publish or reuse a shared admin password.

## When moving to hosting

- Use the same application code but a separate `config.local.php` containing that host's MySQL settings.
- The MySQL hostname may be different from `localhost`. For InfinityFree use the hostname shown in its MySQL Databases section.
- First create the database through the hosting control panel, then import the schema into the **selected** database. Do not add `CREATE DATABASE` or `USE` statements into the SQL.
- Upload the PHP files and `uploads/` assets to the correct `htdocs` folder, not a second nested `htdocs` folder.
- Keep `config.local.php` out of GitHub. Prefer keeping secrets outside the web-accessible root if hosting supports it, and prevent HTTP access to configuration.
- Do not import your original live database dump into a Public repository. This repository supplies an empty schema and synthetic example data only.

**Note:** A local XAMPP test and a GitHub source-code repository are not the same as a working online deployment. GitHub Pages cannot run this PHP + MySQL project.

## Checking product images

Run `python tools/check_assets.py` from the project root, or see [ASSETS.md](ASSETS.md). The same images must be uploaded to `htdocs/uploads/products/` on live hosting.
