# Database notes

The application uses the `nft_db` schema in the original coursework export, but the name on your machine or hosting account can be different. Set the matching name in `config.local.php`.

| Table | Purpose | References |
| --- | --- | --- |
| `users` | Registered accounts and roles | Primary key `id` |
| `categories` | Product categories | Primary key `id` |
| `products` | Catalog, prices, stock, pictures | Foreign key `category_id` → `categories.id` |
| `cart` | Items saved by registered users | `user_id` → `users.id`; `product_id` → `products.id` |
| `stores` | Seller profile and contact info | `user_id` → `users.id` |
| `nfts` | Additional table from the original coursework schema | No declared foreign keys |

`products.store_id` is a field used by the seller pages, but the provided database schema does **not** declare a foreign key against `stores`. This document describes the schema as it actually exists.

## Import order

The tables must be created in this order:

1. `users`
2. `categories`
3. `products` (depends on categories)
4. `cart` (depends on users and products)
5. `stores` (depends on users)
6. `nfts`

This avoids the missing-parent-table error we encountered when importing an earlier library database. [`../database/schema.sql`](../database/schema.sql) follows this order.

The schema is empty, without accounts, emails, password hashes, carts or seller contacts. [`../database/demo_catalog.sql`](../database/demo_catalog.sql) is optional and contains only fabricated catalog entries for testing.

For real data imports, use a private backup and only after you have permission to retain it. Never place the backup in the public repository.
