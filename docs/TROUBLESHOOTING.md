# Problems we encountered during deployment

These were observed while moving TechZone from a local XAMPP setup to InfinityFree. GitHub stores the source code; it does **not** automatically deploy changes to the live website.

## "Please complete the database configuration"

This means `db.php` found `config.local.php` but one or more values still contain `REPLACE_WITH_...`. Open that **private** configuration file on the hosting account, replace each placeholder, and save. Do not paste the password into GitHub or a screenshot.

## "The database is not available"

The config file was read, but PHP failed to connect to MySQL. In InfinityFree open **MySQL Databases** for the TechZone hosting account and copy the hostname, username and *complete* database name. Use the MySQL password for **that hosting account**, which can differ from the password for another InfinityFree account or the dashboard login. The MySQL hostname generally is **not `localhost`** on InfinityFree.

Do not delete tables or reimport SQL to fix a database *connection* error. Use private server error logs to investigate further if the values appear correct; never show detailed connection errors to public visitors.

## Foreign key error during SQL import

Use an **empty** database and import `database/schema.sql`, which defines `users` and `categories` before dependent tables such as `products`, `cart` and `stores`. Do not import the original private database export into GitHub. The optional sample catalog is imported **after** the schema.

The dependency order has been inspected statically; a successful live import is still a separate check.

## Product cards show prices but pictures are blank

A successful database query does not prove the image file exists on the server. TechZone's original catalog references 14 images under `uploads/products/`. All 14 are included here, including eight that did not appear in the InfinityFree upload screenshots.

Run `python tools/check_assets.py` to inspect your local copy; then upload the files missing from the server under the **same directory**, with no extra nested folder. See [ASSETS.md](ASSETS.md). Always inspect a direct image URL in the browser when troubleshooting.

## Security / production warning

This is a college practical assignment, not a production shopping platform. The bundled checkout does not charge cards or finalize orders. Authentication, seller uploads and every state-changing form have not completed a comprehensive security audit. Use test accounts only until you can verify all access controls and site behavior.
