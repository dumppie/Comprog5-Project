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

## 7. Search

| ID | Requirement | Status | Implementation |
|----|-------------|--------|----------------|
| **FR7.1** | Allow customers to search for products by name or keyword on the home page via SQL LIKE query | ✅ Complete | `HomeController@__invoke` handles search parameter; `Product::scopeSearch()` implements LIKE query on name, description, category. Search form on home page with AJAX loading. |
| **FR7.2** | Refactor search to use an Eloquent model scope for cleaner, reusable query logic | ✅ Complete | `Product::scopeSearch($searchTerm)` method encapsulates search logic, used in HomeController and ShopController. Reusable across controllers with clean separation of concerns. |
| **FR7.3** | Upgrade to Laravel Scout (Meilisearch/Algolia) with paginated results for faster, typo-tolerant searching | ✅ Complete | Laravel Scout added to composer.json; `Product` uses `Searchable` trait with `toSearchableArray()` and `shouldBeSearchable()`. HomeController uses Scout when available, falls back to LIKE query. Config: `config/scout.php`. |

**FR7 summary:** All 3 items are implemented.

---

## 8. Filter

| ID | Requirement | Status | Implementation |
|----|-------------|--------|----------------|
| **FR8.1** | Allow customers to filter products by price range (min/max) via AJAX without a full page reload | ✅ Complete | `Product::scopePriceRange($min, $max)` method; HomeController handles min_price/max_price parameters. AJAX-powered filtering with loading overlay. Price inputs in filter section. |
| **FR8.2** | Allow customers to combine the price filter with a category/brand/type filter; active filters shall be visually indicated and individually clearable | ✅ Complete | `Product::scopeCategory($category)` method; Combined search + price + category filtering. Visual filter badges show active filters with × to remove individually. "Clear All" button. AJAX updates without page reload. |

**FR8 summary:** All 2 items are implemented.

---

## 9. Product Reviews

| ID | Requirement | Status | Implementation |
|----|-------------|--------|----------------|
| **FR9.1** | Only customers who have purchased a product may post one review (comment + star rating 1–5) per product. The review form is hidden from non-buyers; direct POST requests return 403. | ✅ Complete | `ReviewController@store` checks `hasPurchasedProduct()` via `User::hasPurchasedProduct()`. `ReviewRequest` authorization prevents non-buyers. Form hidden in views using `hasPurchasedProduct()` check. |
| **FR9.2** | Customers who have purchased a product may post and update their own review and rating. The Edit button is visible only to the review author. | ✅ Complete | `ReviewController@edit/@update` with `isAuthor()` authorization. Edit button only shown when `$review->isAuthor(auth()->user())`. One review per product enforced via database unique constraint. |
| **FR9.3** | The system shall display all reviews in a searchable, sortable datatable accessible to the admin (columns: product, reviewer, rating, comment, date). | ✅ Complete | `Admin\ReviewController@index` with search/sort functionality. View: `admin/reviews/index.blade.php` with datatable, search by product/reviewer/comment, sort by date/rating/product/reviewer. |
| **FR9.4** | The administrator shall be able to permanently delete any user review; the product's average rating shall be recalculated automatically. | ✅ Complete | `Admin\ReviewController@destroy` deletes reviews. `Product::averageRating()` recalculates automatically via `reviews()->avg('rating')`. Database unique constraint on (product_id, user_id). |

**FR9 summary:** All 4 items are implemented.

---

## 10. Validation

| ID | Requirement | Status | Implementation |
|----|-------------|--------|----------------|
| **FR10.1** | The system shall validate all input fields in the add/edit product form (e.g., price must be numeric, required fields must not be empty, image type/size constraints) using Laravel Form Requests; errors are shown inline. | ✅ Complete | `ProductRequest` validation: required fields, numeric price, integer stock, file type/size (JPEG/PNG, 2MB max). Inline errors shown via `@error` directives in product forms. |
| **FR10.2** | The system shall validate all input fields in the user registration form (e.g., unique email, password strength min 8 chars/1 uppercase/1 number, password confirmation). | ✅ Complete | `RegisterUserRequest` with `Password::min(8)->letters()->mixedCase()->numbers()`. Unique email validation, password confirmation, profile photo constraints. |
| **FR10.3** | Client-side (JavaScript) validation shall mirror server-side rules for immediate feedback; forms cannot be submitted while invalid. | ✅ Complete | `public/js/validation.js` with `FormValidator` class. Real-time validation on input/blur, prevents invalid submissions. Auto-initializes for product and registration forms. |

**FR10 summary:** All 3 items are implemented.

---

## 11. Charts & Reports

| ID | Requirement | Status | Implementation |
|----|-------------|--------|----------------|
| **FR11.1** | The system shall display a chart showing yearly sales data. | ✅ Complete | `Admin\ReportController@yearlySales` with Chart.js bar chart. View: `admin/reports/yearly-sales.blade.php`. Groups by YEAR(created_at), shows total sales and order count. |
| **FR11.2** | The system shall display a chart showing monthly sales data. | ✅ Complete | `Admin\ReportController@monthlySales` with Chart.js line chart. View: `admin/reports/monthly-sales.blade.php`. Year selector, fills missing months with zero values. |
| **FR11.3** | The system shall display a sales bar chart with a date range filter using a date picker (start date – end date). | ✅ Complete | `Admin\ReportController@dateRangeSales` with date range filtering. View: `admin/reports/date-range-sales.blade.php`. Date pickers for start/end dates, daily sales bar chart. |
| **FR11.4** | The system shall display a pie chart showing the percentage of total sales contributed by each product. | ✅ Complete | `Admin\ReportController@productSales` with Chart.js pie chart. View: `admin/reports/product-sales.blade.php`. Calculates percentages, shows product distribution with progress bars. |

**FR11 summary:** All 4 items are implemented.

---

## 12. Security

| ID | Requirement | Status | Implementation |
|----|-------------|--------|----------------|
| **FR12.1** | The system shall hash all passwords before storing them in the database. | ✅ Complete | `User` model uses `'password' => 'hashed'` cast (Laravel 11). `RegisterController` uses `Hash::make()`. Passwords never stored in plain text. |
| **FR12.2** | All user inputs shall be sanitized and validated. SQL injection shall be prevented using Eloquent ORM or prepared statements. XSS shall be mitigated via proper Blade templating discipline. | ✅ Complete | Laravel Form Requests (`ProductRequest`, `RegisterUserRequest`, `ReviewRequest`) validate all inputs. Eloquent ORM prevents SQL injection. Blade auto-escapes output (`{{ }}`). Added `InputSanitization` middleware for extra protection. |
| **FR12.3** | The system shall restrict all admin functionalities to authorized admin users only. | ✅ Complete | `EnsureUserIsAdmin` middleware applied to all `/admin/*` routes. Checks `$user->isAdmin()`. Returns 403 for non-admin users. Admin routes protected by `['auth', 'verified', 'admin']` middleware group. |

**FR12 summary:** All 3 items are implemented.

---

## Quick reference – Where to look

- **FR1:** `app/Http/Controllers/Auth/RegisterController.php`, `LoginController.php`, `ProfileController.php`, `Admin/UserController.php`; `resources/views/auth/`, `users/profile.blade.php`, `dashboard/users.blade.php`.
- **FR2:** `app/Http/Controllers/ProductController.php`, `app/Http/Requests/ProductRequest.php`, `app/Models/Product.php`, `app/Models/ProductPhoto.php`, `app/Imports/ProductsImport.php`; `resources/views/products/`.
- **FR3:** `app/Http/Controllers/Auth/LoginController.php` (verified check + redirect to verify page), `app/Http/Middleware/EnsureUserIsAdmin.php`, `bootstrap/app.php` (redirectGuestsTo), `resources/views/auth/verify-email.blade.php` (resend). CSRF: Laravel default for `web` routes.
- **FR4:** `app/Http/Controllers/CartController.php`, `ShopController.php`; `app/Models/CartItem.php`; `resources/views/cart/index.blade.php`, `shop/index.blade.php`. Routes: `cart.index`, `cart.store`, `cart.update`, `cart.destroy`, `shop.index`.
- **FR5:** `app/Http/Controllers/CheckoutController.php`, `OrderController.php`, `Admin/OrderController.php`; `app/Models/Order.php`, `OrderItem.php`, `OrderStatus.php`, `PaymentMethod.php`; `app/Mail/OrderConfirmationMail.php`, `OrderStatusUpdatedMail.php`; `resources/views/checkout/index.blade.php`, `orders/`, `admin/orders/`, `emails/order-confirmation.blade.php`, `emails/order-status-updated.blade.php`, `pdf/receipt.blade.php`. Routes: `checkout.index`, `checkout.store`, `orders.index`, `orders.show`, `admin.orders.index`, `admin.orders.update-status`.
- **FR6:** Stock decrease in `CheckoutController@store`; low/out of stock alert on `admin/orders/index` (config: `config/shop.php`, `LOW_STOCK_THRESHOLD`).
- **FR7:** `app/Models/Product.php` (scopeSearch, Searchable trait), `app/Http/Controllers/HomeController.php` (search handling), `config/scout.php`, `resources/views/home.blade.php` (search form, AJAX). Routes: `home`.
- **FR8:** `app/Models/Product.php` (scopePriceRange, scopeCategory), `app/Http/Controllers/HomeController.php` (filter handling), `resources/views/home.blade.php` (filter section, AJAX, visual indicators).
- **FR9:** `app/Models/Review.php`, `app/Models/Product.php` (reviews relationship), `app/Models/User.php` (hasPurchasedProduct, hasReviewedProduct), `app/Http/Controllers/ReviewController.php`, `app/Http/Controllers/Admin/ReviewController.php`, `app/Http/Requests/ReviewRequest.php`; `resources/views/reviews/edit.blade.php`, `admin/reviews/index.blade.php`. Routes: `reviews.store`, `reviews.edit`, `reviews.update`, `admin.reviews.index`, `admin.reviews.destroy`.
- **FR10:** `app/Http/Requests/ProductRequest.php`, `app/Http/Requests/RegisterUserRequest.php`, `public/js/validation.js` (FormValidator class). Enhanced validation with real-time feedback, password strength, file constraints.
- **FR11:** `app/Http/Controllers/Admin/ReportController.php` (yearly, monthly, date-range, product sales), `resources/views/admin/reports/` (dashboard, yearly-sales, monthly-sales, date-range-sales, product-sales). Chart.js integration for data visualization. Routes: `admin.reports.*`.
- **FR12:** `app/Models/User.php` (password hashing), `app/Http/Middleware/EnsureUserIsAdmin.php` (admin restriction), `app/Http/Middleware/InputSanitization.php`, `app/Http/Middleware/SecurityHeaders.php`. Laravel's built-in CSRF, Eloquent ORM, Blade escaping for comprehensive security.
