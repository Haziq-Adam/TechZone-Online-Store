# File guide

The PHP files stay in the project root to preserve the existing relative links. Moving them into folders would require changing the application paths.

| Files | What they do |
| --- | --- |
| `index.php`, `products.php`, `product.php` | Home page, product listings and details |
| `login.php`, `register.php`, `logout.php` | Account sessions |
| `cart.php`, `add_cart.php` | Shopping cart operations |
| `open_store.php`, `seller_dashboard.php` | Create and manage a seller store |
| `seller_product_form.php`, `seller_delete_product.php` | Product editing and deletion |
| `seller_settings.php` | Store details and upload fields |
| `admin.php` | Admin overview and management |
| `header.php`, `footer.php` | Shared layout |
| `db.php` | Database connection code; loads private `config.local.php` |
| `style.css`, `main.js` | Page styling and interactive behaviour |
| `uploads/products/` | Bundled product illustrations / photos |
| `uploads/stores/` | Folder for seller images uploaded at runtime |
| `database/` | SQL schema and optional catalog data |
| `.github/workflows/` | Automated PHP and JS syntax checks |

The project also includes informational pages such as About, FAQ, Contact and Blog. These are part of the original source.
