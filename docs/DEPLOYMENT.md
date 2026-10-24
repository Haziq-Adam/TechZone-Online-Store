# Deployment notes

This GitHub repository is a **source code** copy without user accounts or host passwords. It is not itself the running website.

For a live InfinityFree setup, use the same application code and fill a local `config.local.php` with that hosting account's actual MySQL values. The MySQL hostname is supplied by InfinityFree and is not usually `localhost`. Import the SQL with phpMyAdmin into a newly created empty database. **Do not upload SQL or hosting backups into `htdocs`.**

For a public showcase, prefer the no-user demo database. Imported original users, password hashes, shopping carts and seller information belong in a protected database and require permission to host. Do not publish personal data. Before sharing a live URL, test access control, upload validation, login rate limits, CSRF protection and HTTPS.

The code has had syntax checks and file inspections, **not** a complete live functional test or security audit.
