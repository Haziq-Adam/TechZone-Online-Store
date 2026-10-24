# Security and privacy notes

This is an academic project and has not had a full security review. Treat it as a learning project, not a payment platform or production marketplace.

- Never commit `config.local.php`, database backups, user exports, API keys or login credentials.
- The published `database/schema.sql` contains **no records**. The optional `demo_catalog.sql` contains **no accounts or personal information**.
- Passwords for registered users are hashed in the PHP code. No ready-made admin password is supplied.
- Upload folders include `.htaccess` rules intended to prevent PHP execution, but a production deployment should also enforce upload validation and server-level access controls.
- Cart quantity and removal actions now use POST with a CSRF token. Other forms and add-to-cart actions still need a complete CSRF and request-method review before public production use. Upload handling should be reviewed for MIME/content validation, file limits and unique naming.
- `login.php` and add-to-cart navigation should be reviewed for redirect validation. Login attempts need rate limiting before open public use.
- `cart.php` displays a checkout button, but checkout does not capture payment or persist an order. The `admin.php` cart totals are not paid revenue.
- Some storefront statistics, testimonials, promises and contact information are static demo content in the original UI. Do not present them as verified business claims.
- Turn off verbose error messages on public deployments, configure HTTPS, keep PHP patched, and test permissions for all admin/seller routes.

**Recommended:** Keep the repository Private until the source and any included media have been reviewed for public release. A file passing PHP syntax checks is not a security guarantee.
