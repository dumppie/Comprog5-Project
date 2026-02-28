# Functional Requirements Checklist

This document verifies implementation status of **FR1 (User Account Management)**, **FR2 (Product Management)**, and **FR3 (Authentication & Authorization)**.

---

## 1. User Account Management (CRUD)

| ID | Requirement | Status | Implementation |
|----|-------------|--------|----------------|
| **FR1.1** | Users can register with name, email, password, contact number, and profile photo upload | ✅ Complete | `RegisterController@register`, `RegisterUserRequest` (validation), `auth/register.blade.php` (form with file input). Profile photo stored in `storage/app/public/profile-photos`. |
| **FR1.2** | Send email verification link after registration; only verified users may log in | ✅ Complete | `User` implements `MustVerifyEmail`. After registration, verification email sent. `LoginController` blocks unverified users and redirects to verify page (FR3.1). `/email/verify` shows notice; resend available. |
| **FR1.3** | Users can log in and log out securely | ✅ Complete | `LoginController@login` (session regenerate, credential check, verified/active checks). `LoginController@logout` (invalidate session, regenerate token). Routes: `POST /login`, `POST /logout`. |
| **FR1.4** | Users can update name, address, password, and profile photo | ✅ Complete | `ProfileController@edit` / `@update`, `UpdateProfileRequest`. View: `users/profile.blade.php`. Fields: name, email, contact_number, address, profile_photo, password (with current_password). |
| **FR1.5** | Admin sees all users in searchable, sortable datatable (avatar, name, email, role, status, registered date) | ✅ Complete | `Admin\UserController@index`, view `dashboard/users.blade.php`. Table columns: avatar, name, email, role, status, registered date. GET params: `search`, `sort`, `dir`. |
| **FR1.6** | Admin can set user status to active/inactive; inactive users cannot log in | ✅ Complete | Status dropdown in admin users table; `updateStatus`. `LoginController` checks `$user->isActive()` and rejects inactive users with error message. |
| **FR1.7** | Admin can update user role; change is immediate and applies on next request | ✅ Complete | Role dropdown in admin users table; `updateRole`. Role stored in DB; `isAdmin()` and middleware use current role. |
| **FR1.8** | Admin cannot deactivate or demote their own account | ✅ Complete | `UserController@updateStatus` and `@updateRole` check `$user->id === $request->user()->id` and return error; UI shows "Current user (cannot change self)". |

**FR1 summary:** All 8 items are implemented.

---

## 2. Product Management (CRUD) — Admin only

| ID | Requirement | Status | Implementation |
|----|-------------|--------|----------------|
| **FR2.1** | Admin can add products (name, category, description, price, stock quantity, multiple photos) | ✅ Complete | `ProductController@create`, `@store`, `ProductRequest`. View: `products/create.blade.php`. Thumbnail + multiple photos supported. |
| **FR2.2** | Admin can view and search products on a datatable | ✅ Complete | `ProductController@index`, view `products/index.blade.php` with search and datatable. |
| **FR2.3** | Admin can update product info (stock, price, photos) | ✅ Complete | `ProductController@edit`, `@update`, view `products/edit.blade.php`. |
| **FR2.4** | Admin can soft-delete products; restore from Trash; permanently delete from Trash | ✅ Complete | `Product` uses `SoftDeletes`. `destroy` (soft delete), `restore`, `forceDelete`. View `products/trash.blade.php` for Trash list, restore, and permanent delete. |
| **FR2.5** | Single thumbnail per product (JPEG/PNG, max 2 MB), in storage/app/public, shown in datatable | ✅ Complete | `ProductRequest` validates thumbnail; stored in `storage/app/public` (e.g. `products/thumbnails`). Thumbnail displayed in products datatable. |
| **FR2.6** | Multiple photos per product (upload and view) | ✅ Complete | `ProductPhoto` model; multiple photos on create/edit; stored in `storage/app/public`; view in product show/edit. |
| **FR2.7** | Bulk import via Excel (.xlsx), Laravel Excel, row-level validation, downloadable error report | ✅ Complete | `ProductController@import`, `ProductsImport`, route `admin.products.import`. Error report download: `admin.products.error-report.download`. |

**FR2 summary:** All 7 items are implemented (product routes and controller exist; ensure categories align with your schema if using `categories` table).

---

## 3. Authentication & Authorization

| ID | Requirement | Status | Implementation |
|----|-------------|--------|----------------|
| **FR3.1** | Restrict login to verified users only; resend option if link expired | ✅ Complete | `LoginController`: if `!$user->hasVerifiedEmail()`, user is kept in session and redirected to `verification.notice` so they can use **Resend verification email** on `/email/verify`. View `auth/verify-email.blade.php` has resend form to `verification.send`. |
| **FR3.2** | Protect all admin routes (`/admin/*`); unauthenticated → redirect to `/login` | ✅ Complete | `bootstrap/app.php`: `$middleware->redirectGuestsTo(fn () => route('login'))`. All admin routes use `middleware(['auth', 'verified', 'admin'])`; unauthenticated users hit `auth` and are redirected to login. |
| **FR3.3** | Restrict admin routes to admin role; non-admin authenticated users get 403 | ✅ Complete | `EnsureUserIsAdmin` middleware: `abort(403, 'Forbidden. Admin access required.')` when `!$request->user()?->isAdmin()`. Registered as `admin` alias and applied to `Route::prefix('admin')`. |
| **FR3.4** | CSRF protection on all state-changing routes (POST/PUT/PATCH/DELETE); invalid/missing token → 419 | ✅ Complete | Laravel’s `web` middleware group (used by `routes/web.php`) includes `VerifyCsrfToken`. All forms use `@csrf`; AJAX uses `X-CSRF-TOKEN` header from `meta[name="csrf-token"]`. Requests without valid token receive **419** (Page Expired). |

**FR3 summary:** All 4 items are implemented.

---

## 4. Shopping Cart

| ID | Requirement | Status | Implementation |
|----|-------------|--------|----------------|
| **FR4.1** | Authenticated customers can add products to cart | ✅ Complete | `ShopController@index` lists products; `CartController@store` adds by product_id + quantity. Shop at `/shop`; add-to-cart form on each product (auth only). |
| **FR4.2** | Display cart items: product name, price, quantity, total amount | ✅ Complete | `CartController@index`, view `cart/index.blade.php`. Table: product name, unit price, quantity, line total; footer shows grand total. |
| **FR4.3** | Update quantity or remove items | ✅ Complete | Update: `PUT /cart/{cart_item}` with quantity; form per row. Remove: `DELETE /cart/{cart_item}` with confirm. |
| **FR4.4** | Recalculate total after any cart change | ✅ Complete | Total is computed in controller from current items: `$items->sum(fn (CartItem $item) => $item->line_total)`. Line total uses current product price × quantity. No stored total; always recalculated on view/after update/remove. |

**FR4 summary:** All 4 items are implemented.

---

## 5. Checkout and Order Processing

| ID | Requirement | Status | Implementation |
|----|-------------|--------|----------------|
| **FR5.1** | Customers can proceed to checkout from cart | ✅ Complete | "Proceed to checkout" on cart page → `GET /checkout`. `CheckoutController@index` shows shipping + payment form. |
| **FR5.2** | Customers enter shipping details (name, address, contact number) | ✅ Complete | Checkout form: `shipping_name`, `shipping_address`, `shipping_contact`; pre-filled from profile. `PlaceOrderRequest` validation. |
| **FR5.3** | Customers select payment method (COD, GCash, Credit Card) | ✅ Complete | `PaymentMethod` seeder: Cash on Delivery, GCash, Credit Card. Radio buttons on checkout; `payment_method_id` in `PlaceOrderRequest`. |
| **FR5.4** | Unique transaction ID and store order details | ✅ Complete | `transaction_id`: `TXN-` + random(8) + timestamp. `Order` + `OrderItem` stored; subtotal, tax, total; shipping and payment_method_id. |
| **FR5.5** | Email confirmation after successful order | ✅ Complete | `OrderConfirmationMail` sent after place order: order ID, items, total, delivery info. View: `emails/order-confirmation.blade.php`. |
| **FR5.6** | Customers view order history and status | ✅ Complete | `OrderController@index` (orders list), `@show` (order detail). Status: Pending, Processing, Ready, Completed, Cancelled. Routes: `orders.index`, `orders.show`. |
| **FR5.7** | Admin updates order status from datatable | ✅ Complete | `Admin\OrderController@index`: orders table with search, status filter; dropdown to change status → `updateStatus`. |
| **FR5.8** | Email + PDF receipt when admin updates status | ✅ Complete | `OrderStatusUpdatedMail` with status change text; PDF receipt attached (customer name/address, items, quantities, prices, subtotals, tax, grand total) via `pdf/receipt.blade.php` and Dompdf. |

**FR5 summary:** All 8 items are implemented.

---

## 6. Inventory Management

| ID | Requirement | Status | Implementation |
|----|-------------|--------|----------------|
| **FR6.1** | Auto decrease product stock after checkout | ✅ Complete | In `CheckoutController@store`, after creating order items: `$cartItem->product->decrement('stock_quantity', $cartItem->quantity)`. Stock validated before placing order. |
| **FR6.2** | Notify admin when stock is low or out of stock | ✅ Complete | Admin Orders page (`admin/orders/index`) shows alert: "Out of stock" and "Low stock (≤ threshold)" product lists. Threshold from `config('shop.low_stock_threshold')` (default 5). Link to Manage products. |

**FR6 summary:** All 2 items are implemented.

---

## Quick reference – Where to look

- **FR1:** `app/Http/Controllers/Auth/RegisterController.php`, `LoginController.php`, `ProfileController.php`, `Admin/UserController.php`; `resources/views/auth/`, `users/profile.blade.php`, `dashboard/users.blade.php`.
- **FR2:** `app/Http/Controllers/ProductController.php`, `app/Http/Requests/ProductRequest.php`, `app/Models/Product.php`, `app/Models/ProductPhoto.php`, `app/Imports/ProductsImport.php`; `resources/views/products/`.
- **FR3:** `app/Http/Controllers/Auth/LoginController.php` (verified check + redirect to verify page), `app/Http/Middleware/EnsureUserIsAdmin.php`, `bootstrap/app.php` (redirectGuestsTo), `resources/views/auth/verify-email.blade.php` (resend). CSRF: Laravel default for `web` routes.
- **FR4:** `app/Http/Controllers/CartController.php`, `ShopController.php`; `app/Models/CartItem.php`; `resources/views/cart/index.blade.php`, `shop/index.blade.php`. Routes: `cart.index`, `cart.store`, `cart.update`, `cart.destroy`, `shop.index`.
- **FR5:** `app/Http/Controllers/CheckoutController.php`, `OrderController.php`, `Admin/OrderController.php`; `app/Models/Order.php`, `OrderItem.php`, `OrderStatus.php`, `PaymentMethod.php`; `app/Mail/OrderConfirmationMail.php`, `OrderStatusUpdatedMail.php`; `resources/views/checkout/index.blade.php`, `orders/`, `admin/orders/`, `emails/order-confirmation.blade.php`, `emails/order-status-updated.blade.php`, `pdf/receipt.blade.php`. Routes: `checkout.index`, `checkout.store`, `orders.index`, `orders.show`, `admin.orders.index`, `admin.orders.update-status`.
- **FR6:** Stock decrease in `CheckoutController@store`; low/out of stock alert on `admin/orders/index` (config: `config/shop.php`, `LOW_STOCK_THRESHOLD`).
