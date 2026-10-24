# Checks and test status

This documents what was checked in the prepared GitHub archive. It is not a claim that live hosting has passed a security audit.

## Checked on the source package

- PHP source lint using the locally available PHP CLI.
- JavaScript syntax using Node.js.
- Database schema table creation and foreign key dependency order, inspected statically.
- Separate empty schema and optional fabricated demo catalog, with no old users or passwords.
- Main site assets and **14/14** original catalog image filenames, including eight previously missing on InfinityFree. See [ASSETS.md](ASSETS.md).
- Missing `config.local.php` and original private SQL dump in the GitHub archive.
- README links to local files and GitHub Actions syntax workflow.
- Adjusted shopping cart changes to redirect before rendering HTML; remove/quantity actions now use POST with a form token.
- Removed misleading prefilled demo email from the login form.

## Not verified

- Live MySQL import into a running database and all foreign key operations.
- Complete browser-based login, catalog, cart, seller and admin workflows on InfinityFree.
- Live image URLs on the user's hosting account after repair upload.
- Full security testing, file upload content validation, CSRF across every form, access-control auditing, rate limiting, or payment processing.

The project is coursework. **Do not advertise it as production-ready.**
