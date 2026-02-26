# MySQL Database Schema (3NF)

Schema is in **Third Normal Form** with **no redundancy**. Uses **MySQL** (InnoDB, utf8mb4). Stored in `database/schema.sql`.

## How to run (MySQL)

```bash
# Create database
mysql -u root -p -e "CREATE DATABASE app_db CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;"

# Apply schema and seeds
mysql -u root -p app_db < database/schema.sql
```

Or in MySQL client:

```sql
CREATE DATABASE app_db CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE app_db;
SOURCE /path/to/database/schema.sql;
```

## Tables (3NF)

| Table | Purpose |
|-------|---------|
| `roles` | User roles (customer, admin) |
| `user_statuses` | active / inactive |
| `users` | Accounts; FK to roles, user_statuses |
| `categories` | Product categories |
| `brands` | Product brands (filter) |
| `products` | Master data; soft-delete via `deleted_at` |
| `product_photos` | Multiple photos per product |
| `payment_methods` | Cash on Delivery, GCash, Credit Card |
| `order_statuses` | Pending, Processing, Ready, Completed, Cancelled |
| `orders` | Transactions; shipping, payment, totals |
| `order_items` | Line items; `unit_price` = price at order time |
| `cart_items` | Cart; UNIQUE(user_id, product_id) |
| `reviews` | One per product per user; UNIQUE(product_id, user_id) |

## Normalization

- **Lookups**: roles, user_statuses, categories, brands, payment_methods, order_statuses — no repeated strings.
- **Order line price**: `order_items.unit_price` is the price at order time (historical fact), not copied from `products.price` to avoid transitive dependency and support price changes.
- **Reviews**: One row per (product_id, user_id); average rating is computed, not stored.

## Design decisions (intentional storage)

These look redundant but are deliberate and standard:

| What | Why it’s stored |
|------|------------------|
| **orders.subtotal, tax, total** | Derivable from `order_items` (quantity × unit_price), but storing them preserves the financial snapshot at purchase time. Standard practice for orders and auditing. |
| **order_items.unit_price** | Duplicates `products.price` at order time. Product prices change; the order must record the price that was charged. Intentional, not redundancy. |
| **orders.shipping_name, shipping_contact** | Can match `users.name` and `users.contact_number`, but shipping can differ per order (gift, different address, one-time contact). Keeping them on `orders` is correct. |
| **products.thumbnail_photo** | Could be derived from `product_photos` (e.g. row with lowest `sort_order`). Stored on `products` to avoid a join on every product listing — a common performance optimization. |

## Laravel

If using Laravel, run migrations instead of the raw SQL (migrations mirror this schema and use MySQL-compatible types).
