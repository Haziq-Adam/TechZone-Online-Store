# TechZone Online Store

An online store website I worked on for my Information Technology practical assignment at Kolej Vokasional Kangar.

I built this project with PHP and MySQL. Visitors can browse products, view product details and add items to a shopping cart. There are also pages for user accounts, sellers and administrators.

**Hosted website:** The TechZone link will be added once the deployment has been checked. The hosted website uses a separate database from the sample database shared here.

![PHP](https://img.shields.io/badge/PHP-Backend-777BB4?style=flat-square)
![MySQL](https://img.shields.io/badge/MySQL-Database-4479A1?style=flat-square)
![JavaScript](https://img.shields.io/badge/JavaScript-Frontend-F7DF1E?style=flat-square)
![HTML5](https://img.shields.io/badge/HTML5-Markup-E34F26?style=flat-square)
![CSS3](https://img.shields.io/badge/CSS3-Styling-1572B6?style=flat-square)
![Full Stack](https://img.shields.io/badge/Full--Stack-Web%20Project-0A66C2?style=flat-square)
![Dashboard](https://img.shields.io/badge/Dashboard-Seller%20%26%20Admin-20B2AA?style=flat-square)
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

- Browse and search products
- View product details and add items to a cart
- Register, sign in and manage items in the cart
- Create a seller store and manage product listings
- Manage users and products through the admin page

The checkout feature is a demonstration. It does **not** process real payments or create completed orders.

## Tools I used

**PHP, MySQL, HTML, CSS and JavaScript**  
VS Code, XAMPP and phpMyAdmin for development and testing. The project also includes a Spline visual asset.

## Run it on your computer

1. Install XAMPP and start **Apache** and **MySQL**.
2. Put the project files inside `C:\xampp\htdocs\TechZone-Online-Store`.
3. Open phpMyAdmin and create an empty database named `techzone_demo`.
4. Import [`database/schema.sql`](database/schema.sql) to create the six tables.
5. To try a sample catalogue, optionally import [`database/demo_catalog.sql`](database/demo_catalog.sql) **after** the schema.
6. Make a copy of `config.local.example.php` named `config.local.php` and enter **your own MySQL settings, including your password if one is required**.
7. Open `http://localhost/TechZone-Online-Store/`.

The sample database does not include existing user accounts, passwords, carts or private seller data. It does not create an administrator account automatically.

For more details, see the [installation guide](docs/INSTALLATION.md).

## Database configuration

**You must configure the database connection yourself.** The values used for my hosting account will not work for another user.

Copy `config.local.example.php` to `config.local.php` and update these values:

```php
<?php
return [
    'host' => 'YOUR_MYSQL_HOSTNAME',
    'username' => 'YOUR_MYSQL_USERNAME',
    'password' => 'YOUR_MYSQL_PASSWORD',
    'database' => 'YOUR_FULL_DATABASE_NAME',
];
```

- **On XAMPP:** Use your local MySQL details. A default installation often uses `localhost`, `root` and an empty password, but check your own configuration. Use `techzone_demo` if you followed the setup steps above.
- **On InfinityFree:** Open **MySQL Databases** in your *own* hosting account. Copy the hostname, username, **MySQL password** and complete database name (including the assigned prefix). Do not use `localhost` for the InfinityFree database hostname.
- **Password:** Replace `YOUR_MYSQL_PASSWORD` with your own password; the text shown above is only a placeholder. Your MySQL password is not necessarily the same as your website login password.

**Do not commit `config.local.php` to GitHub.** It contains private credentials and is excluded in `.gitignore`. Only the example configuration belongs in the repository. Do not include passwords in screenshots, README files or GitHub issues.

The SQL in this repository is intended for a **new, empty database**. Do not import the separate live database backup with real account details into a public demo. See [database notes](docs/DATABASE.md) and [deployment guide](docs/DEPLOYMENT.md).

## What I learned

When I moved the project from XAMPP to InfinityFree, I found that the database connection needed different settings. The MySQL host, username, password and database name on hosting are not the same as my local setup.

Some product images also failed to appear after upload. I compared the filenames stored in the database with the files in `uploads/products/` and found that some images had not reached the hosting folder. The GitHub project includes the product image set and an [asset-check guide](docs/ASSETS.md).

I also checked the order of the SQL tables so tables referenced by foreign keys are created first.

## Project files

| Section | Files |
| --- | --- |
| Homepage and catalogue | [`index.php`](index.php), [`products.php`](products.php), [`product.php`](product.php) |
| Accounts | [`login.php`](login.php), [`register.php`](register.php) |
| Shopping cart | [`cart.php`](cart.php), [`add_cart.php`](add_cart.php) |
| Seller pages | [`open_store.php`](open_store.php), [`seller_dashboard.php`](seller_dashboard.php), [`seller_product_form.php`](seller_product_form.php) |
| Admin page | [`admin.php`](admin.php) |
| Database | [`database/schema.sql`](database/schema.sql), [`database/demo_catalog.sql`](database/demo_catalog.sql) |
| Technology | [`TECHSTACK.md`](TECHSTACK.md) |
| Documentation | [Installation](docs/INSTALLATION.md), [Database](docs/DATABASE.md), [Assets](docs/ASSETS.md), [Deployment](docs/DEPLOYMENT.md), [Security](docs/SECURITY.md), [Checks](docs/CHECKS.md), [Troubleshooting](docs/TROUBLESHOOTING.md) |

## Project disclaimer

TechZone was developed during my Information Technology practical coursework at Kolej Vokasional Kangar. It represents the work I completed while learning full-stack web development, so some parts are still incomplete and there are areas that need further testing and improvement.

**This repository is shared as an academic project and portfolio example, not as a production-ready online store or commercial service.** The checkout does not accept real payments. Product listings, prices and promotional figures are for demonstration purposes unless independently verified.

The project has not undergone a full security assessment or end-to-end production test. This repository does not include the password or personal data from the hosted database.
