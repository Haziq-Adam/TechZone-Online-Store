# TechZone Online Store

I worked on TechZone for my Information Technology practical assignment at Kolej Vokasional Kangar.

It's a PHP and MySQL online store project. I built pages for browsing products, signing in, managing a cart and adding products through a seller account. There's also an admin page for managing users and products.

**Hosted website:** The live link will be added after the TechZone deployment has been checked. The hosted website uses a separate database from the one shared in this repository.

![PHP](https://img.shields.io/badge/PHP-Backend-777BB4?style=flat-square)
![MySQL](https://img.shields.io/badge/MySQL-Database-4479A1?style=flat-square)
![JavaScript](https://img.shields.io/badge/JavaScript-Frontend-F7DF1E?style=flat-square)
![HTML5](https://img.shields.io/badge/HTML5-Markup-E34F26?style=flat-square)
![CSS3](https://img.shields.io/badge/CSS3-Styling-1572B6?style=flat-square)
![Full Stack](https://img.shields.io/badge/Full--Stack-Web%20Project-0A66C2?style=flat-square)
![Admin](https://img.shields.io/badge/Admin-Dashboard-20B2AA?style=flat-square)
![Coursework](https://img.shields.io/badge/Project-Practical%20Assignment-8A2BE2?style=flat-square)

## Contents

- [What the system does](#what-the-system-does)
- [Tools I used](#tools-i-used)
- [Run it on your computer](#run-it-on-your-computer)
- [Database configuration](#database-configuration)
- [What I learned](#what-i-learned)
- [Project files](#project-files)
- [Project disclaimer](#project-disclaimer)

## What the system does

- Browse products, search for products and view their details
- Register, sign in and add products to a shopping cart
- Update quantities and remove products from the cart
- Create a seller store and manage product listings
- Manage users and products through the admin page

**Note:** Checkout is only a demonstration. This project does not accept payments or create completed orders.

## Tools I used

**PHP, MySQL, HTML, CSS and JavaScript**  
VS Code, XAMPP and phpMyAdmin for local development and testing. The project also has a Spline asset for its visual design.

## Run it on your computer

1. Install XAMPP and start **Apache** and **MySQL**.
2. Put the project files in `C:\xampp\htdocs\TechZone-Online-Store`.
3. Open phpMyAdmin and create a new empty database named `techzone_demo`.
4. Import [`database/schema.sql`](database/schema.sql) into that database. The tables are ordered so their foreign key references exist before they are used.
5. To load a few made-up products, optionally import [`database/demo_catalog.sql`](database/demo_catalog.sql) **after** the schema.
6. Copy `config.local.example.php` to `config.local.php`. Change the database settings, including **your own MySQL password**. See [Database configuration](#database-configuration).
7. Open `http://localhost/TechZone-Online-Store/` in your browser.

The shared SQL files do not contain real users, passwords, shopping carts or seller contact information. No administrator account is created automatically. You can register your own test account to try the basic pages.

For more information, see the [installation guide](docs/INSTALLATION.md) and [database notes](docs/DATABASE.md).

## Database configuration

The database settings are different for each computer and hosting account. **You must edit `config.local.php` yourself before running the site.** The file `config.local.example.php` is only an example.

For **XAMPP on your own computer**, use the hostname, username, password and database name from your local MySQL setup. A default XAMPP installation often uses `localhost`, `root` and a blank password, but check your own settings.

For **InfinityFree or another PHP host**, copy the file to `config.local.php` and replace **all four placeholders** below with the values shown in *your own hosting account's MySQL Databases page*:

```php
<?php
return [
    'host' => 'YOUR_MYSQL_HOSTNAME',
    'username' => 'YOUR_MYSQL_USERNAME',
    'password' => 'YOUR_MYSQL_PASSWORD',
    'database' => 'YOUR_FULL_DATABASE_NAME',
];
```

In particular, **`YOUR_MYSQL_PASSWORD` is not a working password**. Replace it with the **MySQL password for your own hosting account**. Do not reuse someone else's password or database settings. Make sure the full database name includes any prefix assigned by your host.

**Keep `config.local.php` private.** It is excluded by `.gitignore`, so do not force-add it to GitHub or paste your password into the README, screenshots or issues. Upload the configured file only to the appropriate server and make sure visitors cannot download it. See [deployment notes](docs/DEPLOYMENT.md).

## What I learned

When I moved this project from XAMPP to InfinityFree, the first challenge was getting PHP to connect to the hosting database. InfinityFree uses its own MySQL hostname, username and database name, so the local settings could not be copied across unchanged.

Some product images also failed to appear after upload, even though the files existed on my computer. I had to compare the image paths stored in MySQL with the actual files in `uploads/products/` and upload the missing images. The repository includes the full product image set and an [asset check](docs/ASSETS.md) for anyone setting it up.

I also checked the database table order to avoid foreign key errors during import, which we ran into on an earlier coursework project.

## Project files

| Section | Files |
| --- | --- |
| Homepage and catalogue | [`index.php`](index.php), [`products.php`](products.php), [`product.php`](product.php) |
| Login and registration | [`login.php`](login.php), [`register.php`](register.php) |
| Cart | [`cart.php`](cart.php), [`add_cart.php`](add_cart.php) |
| Seller pages | [`open_store.php`](open_store.php), [`seller_dashboard.php`](seller_dashboard.php), [`seller_product_form.php`](seller_product_form.php) |
| Admin | [`admin.php`](admin.php) |
| Database | [`database/schema.sql`](database/schema.sql), [`database/demo_catalog.sql`](database/demo_catalog.sql) |
| Tech stack | [`TECHSTACK.md`](TECHSTACK.md) |
| More documentation | [Setup](docs/INSTALLATION.md), [Database](docs/DATABASE.md), [Assets](docs/ASSETS.md), [Security](docs/SECURITY.md), [Checks](docs/CHECKS.md), [Troubleshooting](docs/TROUBLESHOOTING.md), [Project map](docs/PROJECT_MAP.md) |

## Project disclaimer

This project was developed as part of my Information Technology practical coursework at Kolej Vokasional Kangar. It reflects my skills and understanding during that stage of my studies. Some features are incomplete, and there are areas of the code, user experience and security that can still be improved.

I'm sharing it as an example of the work I completed while learning web development, **not as a production-ready online store or a commercial service**. Any product listings and promotional figures shown in the interface are for demonstration unless independently verified.

The GitHub repository does not include the original hosting password or personal data from the live database. This project has not completed a full security review or end-to-end production test.
