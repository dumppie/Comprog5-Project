# La Petite Pâtisserie: Functional Requirements Documentation

## Table of Contents
1. User Account Management (FR1)
2. Product Management (FR2)
3. Authentication & Authorization (FR3)
4. Shopping Cart (FR4)
5. Checkout and Order Processing (FR5)
6. Inventory Management (FR6)
7. Search (FR7)
8. Filter (FR8)
9. Product Reviews (FR9)
10. Validation (FR10)
11. Charts & Reports (FR11)
12. Security (FR12)

---

## 1. User Account Management (FR1)

### FR1.1 User Registration
**Functionality**: Users can create accounts with personal information and profile photo upload.

**Key Files Involved**:
- `app/Http/Controllers/Auth/RegisterController.php` - Handles registration logic
- `app/Http/Requests/RegisterUserRequest.php` - Validates registration data
- `resources/views/auth/register.blade.php` - Registration form interface
- `app/Models/User.php` - User model with photo upload functionality
- `database/migrations/` - User table migration with photo storage

**Process Flow**:
1. User fills registration form with name, email, password, contact number
2. Profile photo uploaded to storage/app/public/profiles/
3. Validation checks for unique email and password strength
4. User account created with 'inactive' status
5. Verification email sent to user

### FR1.2 Email Verification
**Functionality**: Email verification required before users can log in.

**Key Files Involved**:
- `app/Http/Controllers/EmailVerificationController.php` - Handles verification
- `app/Notifications/VerifyEmail.php` - Email notification class
- `resources/views/auth/verify.blade.php` - Verification prompt page
- `app/Middleware/EnsureEmailIsVerified.php` - Verification middleware

**Process Flow**:
1. Registration triggers verification email
2. User clicks verification link in email
3. Token validated and user status changed to 'active'
4. User can now log in to the system

### FR1.3 Login/Logout
**Functionality**: Secure authentication system for user access.

**Key Files Involved**:
- `app/Http/Controllers/Auth/LoginController.php` - Login logic
- `app/Http/Controllers/Auth/AuthenticatedSessionController.php` - Session management
- `resources/views/auth/login.blade.php` - Login form interface
- `config/auth.php` - Authentication configuration

**Process Flow**:
1. User submits credentials through login form
2. Credentials validated against database
3. Session created upon successful authentication
4. User redirected to dashboard or requested page
5. Logout destroys session and redirects to login

### FR1.4 Profile Management
**Functionality**: Users can update personal information and profile photo.

**Key Files Involved**:
- `app/Http/Controllers/ProfileController.php` - Profile update logic
- `resources/views/profile/edit.blade.php` - Profile edit interface
- `app/Http/Requests/ProfileUpdateRequest.php` - Profile validation

**Process Flow**:
1. User accesses profile edit page
2. Updates personal information (name, address, etc.)
3. Can change password with current password verification
4. Can upload new profile photo
5. Changes saved to database

### FR1.5 Admin User Management
**Functionality**: Admin can view all users in searchable datatable.

**Key Files Involved**:
- `app/Http/Controllers/Admin/UserController.php` - User management logic
- `resources/views/admin/users/index.blade.php` - Users datatable
- `app/Models/User.php` - User model with relationships

**Features**:
- Datatable with columns: avatar, name, email, role, status, registered date
- Search functionality across all user fields
- Sort by any column
- Pagination for large user lists

### FR1.6 User Status Management
**Functionality**: Admin can activate/deactivate user accounts.

**Key Files Involved**:
- `app/Http/Controllers/Admin/UserController.php` - Status update method
- `app/Models/User.php` - Status attribute and methods
- `app/Middleware/CheckUserStatus.php` - Status validation middleware

**Process Flow**:
1. Admin clicks status toggle in users datatable
2. AJAX request sent to update user status
3. User status updated in database
4. Inactive users cannot log in (middleware check)

### FR1.7 Role Management
**Functionality**: Admin can change user roles (customer/admin).

**Key Files Involved**:
- `app/Http/Controllers/Admin/UserController.php` - Role update method
- `app/Models/User.php` - Role attribute and isAdmin() method
- `app/Middleware/EnsureUserIsAdmin.php` - Admin access middleware

**Process Flow**:
1. Admin selects new role from dropdown in users datatable
2. AJAX request updates user role
3. Permissions apply immediately on next request
4. Admin users gain access to admin panel

### FR1.8 Admin Self-Protection
**Functionality**: Admin cannot deactivate or demote their own account.

**Key Files Involved**:
- `app/Http/Controllers/Admin/UserController.php` - Self-protection logic
- `app/Middleware/EnsureUserIsAdmin.php` - Admin validation

**Process Flow**:
1. System checks if target user is current authenticated admin
2. Prevents status changes or role demotion for own account
3. Returns error message for self-modification attempts

---

## 2. Product Management (FR2)

### FR2.1 Product Creation
**Functionality**: Admin can add products with details and multiple photos.

**Key Files Involved**:
- `app/Http/Controllers/ProductController.php` - Product CRUD operations
- `app/Http/Requests/ProductRequest.php` - Product validation
- `resources/views/products/create.blade.php` - Product creation form
- `app/Models/Product.php` - Product model with relationships
- `app/Models/ProductPhoto.php` - Photo management model

**Process Flow**:
1. Admin fills product creation form
2. Uploads thumbnail photo (JPEG/PNG, max 2MB)
3. Uploads multiple additional photos
4. Validation checks all required fields
5. Product saved to database with photo associations

### FR2.2 Product Viewing & Search
**Functionality**: Admin can view and search products in datatable.

**Key Files Involved**:
- `app/Http/Controllers/ProductController.php` - Index method
- `resources/views/products/index.blade.php` - Products datatable
- `app/Models/Product.php` - Search scopes and relationships

**Features**:
- Datatable with product information and photos
- Real-time search across product fields
- Sort by name, price, category, stock
- Pagination for large product catalogs

### FR2.3 Product Updates
**Functionality**: Admin can modify product information and photos.

**Key Files Involved**:
- `app/Http/Controllers/ProductController.php` - Update method
- `resources/views/products/edit.blade.php` - Product edit form
- `app/Http/Requests/ProductRequest.php` - Update validation

**Process Flow**:
1. Admin accesses product edit page
2. Modifies product details (name, price, stock, etc.)
3. Can add/remove/change photos
4. Changes validated and saved to database

### FR2.4 Product Trash System
**Functionality**: Soft-delete products with restore and permanent delete options.

**Key Files Involved**:
- `app/Http/Controllers/Admin/TrashController.php` - Trash management
- `resources/views/admin/trash/index.blade.php` - Trash view
- `app/Models/Product.php` - SoftDeletes trait
- `database/migrations/` - Soft delete column in products table

**Process Flow**:
1. Admin clicks trash icon to soft-delete product
2. Product marked as deleted but retained in database
3. Trash page shows all deleted products
4. Admin can restore products or permanently delete
5. Permanent deletion removes all associated photos

### FR2.5 Thumbnail Photo Upload
**Functionality**: Single thumbnail photo per product with size constraints.

**Key Files Involved**:
- `app/Http/Controllers/ProductController.php` - Photo upload logic
- `app/Models/ProductPhoto.php` - Photo model with thumbnail flag
- `storage/app/public/products/` - Photo storage location

**Features**:
- JPEG/PNG format validation
- Maximum 2MB file size limit
- Automatic resizing and optimization
- Storage in Laravel's public storage system

### FR2.6 Multiple Photo Support
**Functionality**: Upload and manage multiple photos per product.

**Key Files Involved**:
- `app/Models/ProductPhoto.php` - Photo relationship model
- `resources/views/products/*` - Photo gallery interfaces
- `storage/app/public/products/` - Photo storage

**Features**:
- Unlimited photos per product
- Thumbnail designation system
- Photo gallery display on product pages
- Bulk photo upload capability

### FR2.7 Bulk Excel Import
**Functionality**: Import products from Excel file with validation and error reporting.

**Key Files Involved**:
- `app/Imports/ProductsImport.php` - Excel import class
- `app/Http/Controllers/ProductController.php` - Import method
- `product_import_template.csv` - Template file
- `PRODUCT_IMPORT_GUIDE.md` - Import documentation

**Process Flow**:
1. Admin downloads Excel template
2. Fills product data including image file paths
3. Uploads Excel file through import interface
4. System validates each row and creates products
5. Error report generated for failed imports
6. Images copied from specified paths to storage

---

## 3. Authentication & Authorization (FR3)

### FR3.1 Verified User Login
**Functionality**: Only email-verified users can log in.

**Key Files Involved**:
- `app/Middleware/EnsureEmailIsVerified.php` - Verification middleware
- `app/Http/Controllers/Auth/EmailVerificationNotificationController.php` - Resend verification
- `resources/views/auth/verify.blade.php` - Verification prompt

**Process Flow**:
1. User attempts to login
2. Middleware checks email verification status
3. Unverified users redirected to verification page
4. Option to resend verification email
5. Verified users proceed to login

### FR3.2 Admin Route Protection
**Functionality**: All admin routes require authentication.

**Key Files Involved**:
- `routes/web.php` - Admin route groups with auth middleware
- `app/Http/Middleware/Authenticate.php` - Authentication middleware
- `app/Providers/RouteServiceProvider.php` - Route protection

**Features**:
- All /admin/* routes protected
- Unauthenticated users redirected to /login
- Session-based authentication
- Automatic redirect to intended page after login

### FR3.3 Admin Role Restriction
**Functionality**: Admin routes restricted to admin role users.

**Key Files Involved**:
- `app/Http/Middleware/EnsureUserIsAdmin.php` - Admin role middleware
- `app/Models/User.php` - isAdmin() method
- `routes/web.php` - Admin middleware application

**Process Flow**:
1. Authenticated user accesses admin route
2. Middleware checks user role
3. Non-admin users receive 403 Forbidden error
4. Admin users proceed to requested admin page

### FR3.4 CSRF Protection
**Functionality**: CSRF tokens protect against cross-site request forgery.

**Key Files Involved**:
- `app/Http/Middleware/VerifyCsrfToken.php` - CSRF validation
- `resources/views/layouts/*` - CSRF meta tags in all forms
- `bootstrap/app.php` - CSRF middleware registration

**Features**:
- All POST/PUT/PATCH/DELETE routes protected
- Automatic CSRF token generation
- HTTP 419 response for invalid tokens
- Blade templates automatically include CSRF tokens

---

## 4. Shopping Cart (FR4)

### FR4.1 Add to Cart
**Functionality**: Authenticated customers can add products to cart.

**Key Files Involved**:
- `app/Http/Controllers/CartController.php` - Cart management
- `resources/views/shop/index.blade.php` - Add to cart buttons
- `app/Models/CartItem.php` - Cart item model
- `database/migrations/` - Cart items table

**Process Flow**:
1. Customer clicks "Add to Cart" on product page
2. AJAX request sent to cart controller
3. Cart item created or updated in database
4. Cart count updated in navigation
5. Success message displayed to user

### FR4.2 Cart Display
**Functionality**: Show all cart items with details and totals.

**Key Files Involved**:
- `app/Http/Controllers/CartController.php` - Cart display logic
- `resources/views/cart/index.blade.php` - Cart page
- `app/Models/CartItem.php` - Cart item relationships

**Features**:
- Product name, price, quantity display
- Item subtotal calculations
- Cart total amount
- Product images in cart
- Stock availability indicators

### FR4.3 Cart Management
**Functionality**: Update quantities and remove items from cart.

**Key Files Involved**:
- `app/Http/Controllers/CartController.php` - Update/remove methods
- `resources/views/cart/index.blade.php` - Cart management interface
- JavaScript AJAX handlers - Real-time updates

**Process Flow**:
1. User changes quantity or removes item
2. AJAX request sent to cart controller
3. Cart item updated or removed from database
4. Cart totals recalculated
5. Interface updated without page reload

### FR4.4 Automatic Total Calculation
**Functionality**: Cart totals update automatically with changes.

**Key Files Involved**:
- `app/Models/CartItem.php` - Total calculation methods
- `app/Http/Controllers/CartController.php` - Cart logic
- JavaScript functions - Real-time calculations

**Features**:
- Item subtotal (price × quantity)
- Cart grand total
- Tax calculations
- Shipping cost calculations
- Real-time updates without page refresh

---

## 5. Checkout and Order Processing (FR5)

### FR5.1 Checkout Process
**Functionality**: Customers can proceed from cart to checkout.

**Key Files Involved**:
- `app/Http/Controllers/CheckoutController.php` - Checkout logic
- `resources/views/checkout/index.blade.php` - Checkout form
- `app/Models/Order.php` - Order model

**Process Flow**:
1. Customer clicks "Proceed to Checkout" from cart
2. Cart items transferred to order
3. Customer directed to checkout form
4. Order created with "pending" status
5. Customer enters shipping and payment details

### FR5.2 Shipping Details
**Functionality**: Collect customer shipping information.

**Key Files Involved**:
- `app/Http/Controllers/CheckoutController.php` - Shipping logic
- `resources/views/checkout/index.blade.php` - Shipping form
- `app/Http/Requests/CheckoutRequest.php` - Shipping validation

**Features**:
- Name, address, contact number fields
- Address validation and formatting
- Saved shipping addresses for returning customers
- Default address selection

### FR5.3 Payment Methods
**Functionality**: Multiple payment method options.

**Key Files Involved**:
- `resources/views/checkout/index.blade.php` - Payment selection
- `app/Models/PaymentMethod.php` - Payment method model
- `config/payment.php` - Payment configuration

**Available Methods**:
- Cash on Delivery (COD)
- GCash
- Credit Card
- Bank Transfer

### FR5.4 Order Generation
**Functionality**: Create unique transaction ID and store order details.

**Key Files Involved**:
- `app/Http/Controllers/CheckoutController.php` - Order creation
- `app/Models/Order.php` - Order model and relationships
- `app/Models/OrderItem.php` - Order item model
- `database/migrations/` - Orders and order items tables

**Process Flow**:
1. Unique transaction ID generated
2. Order record created with customer details
3. Order items created from cart items
4. Payment method and status recorded
5. Stock quantities updated

### FR5.5 Order Confirmation Email
**Functionality**: Send email confirmation with order details.

**Key Files Involved**:
- `app/Notifications/OrderConfirmation.php` - Email notification
- `resources/views/emails/order-confirmation.blade.php` - Email template
- `config/mail.php` - Email configuration

**Email Contents**:
- Order ID and transaction details
- List of ordered items with quantities
- Total amount and payment method
- Delivery information
- Estimated delivery time

### FR5.6 Order History
**Functionality**: Customers can view order history and status.

**Key Files Involved**:
- `app/Http/Controllers/OrderController.php` - Order display
- `resources/views/orders/index.blade.php` - Order history
- `resources/views/orders/show.blade.php` - Order details

**Features**:
- List of all customer orders
- Order status tracking (Pending, Processing, Ready, Completed, Cancelled)
- Order details and item information
- Tracking information (if available)

### FR5.7 Admin Order Management
**Functionality**: Admin can update order status from datatable.

**Key Files Involved**:
- `app/Http/Controllers/Admin/OrderController.php` - Order management
- `resources/views/admin/orders/index.blade.php` - Orders datatable
- `app/Models/Order.php` - Status update methods

**Features**:
- Datatable with all orders
- Status dropdown for each order
- AJAX status updates
- Order filtering and searching
- Bulk status updates

### FR5.8 Order Status Notifications
**Functionality**: Email notifications with PDF receipts for status changes.

**Key Files Involved**:
- `app/Notifications/OrderStatusUpdate.php` - Status notification
- `app/Services/PdfReceiptService.php` - PDF generation
- `resources/views/emails/order-status-update.blade.php` - Email template

**PDF Receipt Contents**:
- Customer name and address
- Order items with quantities and prices
- Subtotals, tax, and grand total
- Order status and tracking information
- Company branding and contact details

---

## 6. Inventory Management (FR6)

### FR6.1 Automatic Stock Deduction
**Functionality**: Stock quantity decreases after successful checkout.

**Key Files Involved**:
- `app/Http/Controllers/CheckoutController.php` - Stock update logic
- `app/Models/Product.php` - Stock management methods
- `app/Models/OrderItem.php` - Order item processing

**Process Flow**:
1. Order successfully completed
2. System iterates through order items
3. Product stock quantity decreased by item quantity
4. Stock update logged for inventory tracking
5. Low stock notifications triggered if needed

### FR6.2 Low Stock Notifications
**Functionality**: Admin notified when stock is low or out of stock.

**Key Files Involved**:
- `app/Services/InventoryService.php` - Stock monitoring
- `app/Notifications/LowStockAlert.php` - Stock notification
- `app/Http/Controllers/Admin/DashboardController.php` - Stock alerts

**Notification Triggers**:
- Stock quantity ≤ 5 (low stock)
- Stock quantity = 0 (out of stock)
- Dashboard displays low stock products
- Email notifications to admin
- Visual indicators in product management

---

## 7. Search (FR7)

### FR7.1 Basic Product Search
**Functionality**: Search products by name/keyword on Shop page.

**Key Files Involved**:
- `app/Http/Controllers/ShopController.php` - Search logic
- `resources/views/shop/index.blade.php` - Search interface
- `app/Models/Product.php` - Search scopes

**Features**:
- SQL LIKE query for name matching
- Real-time search results
- Search result highlighting
- Pagination for search results

### FR7.2 Eloquent Search Scopes
**Functionality**: Clean, reusable search query logic.

**Key Files Involved**:
- `app/Models/Product.php` - Search scope methods
- `app/Http/Controllers/ShopController.php` - Scope usage
- `app/Http/Controllers/ProductController.php` - Admin search

**Scope Methods**:
- `scopeSearch()` - Basic name search
- `scopeSearchByKeyword()` - Advanced keyword search
- `scopeWithPhotos()` - Include photo relationships
- `scopeActive()` - Only active products

### FR7.3 Laravel Scout Integration
**Functionality**: Advanced search with typo tolerance and pagination.

**Key Files Involved**:
- `app/Models/Product.php` - Searchable trait
- `config/scout.php` - Scout configuration
- `app/Services/SearchService.php` - Search management

**Features**:
- Full-text search capabilities
- Typo-tolerant searching
- Fast search performance
- Paginated search results
- Fallback to basic search if Scout unavailable

---

## 8. Filter (FR8)

### FR8.1 Price Range Filtering
**Functionality**: Filter products by min/max price via AJAX.

**Key Files Involved**:
- `app/Http/Controllers/ShopController.php` - Filter logic
- `resources/views/shop/index.blade.php` - Filter interface
- `public/js/filter.js` - AJAX filtering

**Features**:
- Price range slider inputs
- AJAX requests without page reload
- Real-time filter application
- Filter result counts
- Clear filter options

### FR8.2 Combined Filters
**Functionality**: Multiple filters with visual indicators.

**Key Files Involved**:
- `app/Http/Controllers/ShopController.php` - Combined filter logic
- `resources/views/shop/index.blade.php` - Filter UI
- `app/Models/Product.php` - Filter scopes

**Filter Options**:
- Price range (min/max)
- Product category
- Brand/type filters
- Active filter display
- Individual filter clearing
- Clear all filters option

---

## 9. Product Reviews (FR9)

### FR9.1 Purchase-Verified Reviews
**Functionality**: Only customers who purchased products can review.

**Key Files Involved**:
- `app/Http/Controllers/ReviewController.php` - Review validation
- `app/Models/Review.php` - Review model
- `app/Models/Order.php` - Purchase verification
- `app/Middleware/VerifyPurchase.php` - Purchase check middleware

**Process Flow**:
1. Customer attempts to review product
2. System verifies customer purchased the product
3. Review form displayed only to verified purchasers
4. Direct POST requests from non-purchasers return 403
5. One review per product per customer

### FR9.2 Review Editing
**Functionality**: Customers can edit their own reviews.

**Key Files Involved**:
- `app/Http/Controllers/ReviewController.php` - Edit methods
- `resources/views/reviews/edit.blade.php` - Review edit form
- `app/Models/Review.php` - Ownership verification

**Features**:
- Edit button visible only to review author
- Review ownership verification
- Rating and comment updates
- Edit history tracking
- Review deletion by author

### FR9.3 Admin Review Management
**Functionality**: Admin can view all reviews in searchable datatable.

**Key Files Involved**:
- `app/Http/Controllers/Admin/ReviewController.php` - Review management
- `resources/views/admin/reviews/index.blade.php` - Reviews datatable
- `app/Models/Review.php` - Review relationships

**Datatable Columns**:
- Product name
- Reviewer name
- Star rating (1-5)
- Review comment
- Review date
- Action buttons

### FR9.4 Review Deletion & Rating Recalculation
**Functionality**: Admin can delete reviews and update product ratings.

**Key Files Involved**:
- `app/Http/Controllers/Admin/ReviewController.php` - Delete method
- `app/Models/Product.php` - Rating calculation methods
- `app/Models/Review.php` - Review deletion

**Process Flow**:
1. Admin deletes review from datatable
2. Review removed from database
3. Product average rating recalculated
4. Rating updated in product record
5. Cache cleared for updated ratings

---

## 10. Validation (FR10)

### FR10.1 Product Form Validation
**Functionality**: Comprehensive validation for product forms.

**Key Files Involved**:
- `app/Http/Requests/ProductRequest.php` - Product validation rules
- `resources/views/products/*` - Inline error display
- `public/js/validation.js` - Client-side validation

**Validation Rules**:
- Required field validation
- Numeric price validation
- Image type/size constraints
- Category validation
- Description length limits
- Stock quantity validation

### FR10.2 User Registration Validation
**Functionality**: User registration form validation.

**Key Files Involved**:
- `app/Http/Requests/RegisterUserRequest.php` - Registration validation
- `resources/views/auth/register.blade.php` - Error display
- `public/js/validation.js` - Client-side validation

**Validation Rules**:
- Unique email validation
- Password strength (8+ chars, 1 uppercase, 1 number)
- Password confirmation matching
- Contact number format
- Profile photo constraints

### FR10.3 Client-Side Validation
**Functionality**: JavaScript validation mirrors server-side rules.

**Key Files Involved**:
- `public/js/validation.js` - Validation library
- `resources/views/layouts/*` - Validation script inclusion
- Form validation classes and methods

**Features**:
- Real-time validation feedback
- Form submission prevention while invalid
- Matching server-side validation rules
- User-friendly error messages
- Visual error indicators

---

## 11. Charts & Reports (FR11)

### FR11.1 Yearly Sales Chart
**Functionality**: Display sales data by year.

**Key Files Involved**:
- `app/Http/Controllers/Admin/ReportController.php` - Yearly sales method
- `resources/views/admin/reports/yearly-sales.blade.php` - Chart display
- `database/migrations/` - Orders table for data

**Chart Features**:
- Bar chart with yearly totals
- Interactive tooltips
- Year selection options
- Data export capabilities
- Responsive design

### FR11.2 Monthly Sales Chart
**Functionality**: Display sales data by month with year selection.

**Key Files Involved**:
- `app/Http/Controllers/Admin/ReportController.php` - Monthly sales method
- `resources/views/admin/reports/monthly-sales.blade.php` - Chart interface
- SQLite-compatible date functions

**Chart Features**:
- Monthly sales bars
- Year dropdown selector
- All months displayed (including zeros)
- Month name labels
- Total sales summary

### FR11.3 Date Range Sales Chart
**Functionality**: Sales chart with customizable date range.

**Key Files Involved**:
- `app/Http/Controllers/Admin/ReportController.php` - Date range method
- `resources/views/admin/reports/date-range-sales.blade.php` - Date picker interface
- JavaScript date picker library

**Features**:
- Start and end date pickers
- Dynamic chart generation
- Date range validation
- Sales trend visualization
- Export functionality

### FR11.4 Product Sales Pie Chart
**Functionality**: Show sales percentage by product.

**Key Files Involved**:
- `app/Http/Controllers/Admin/ReportController.php` - Product sales method
- `resources/views/admin/reports/product-sales.blade.php` - Pie chart display
- Chart.js library for visualization

**Chart Features**:
- Pie chart with product segments
- Percentage labels
- Color-coded products
- Interactive tooltips
- Top products highlighting

---

## 12. Security (FR12)

### FR12.1 Password Hashing
**Functionality**: All passwords securely hashed before storage.

**Key Files Involved**:
- `app/Models/User.php` - Password hashing attribute
- `config/hashing.php` - Hashing configuration
- `app/Providers/AuthServiceProvider.php` - Authentication setup

**Security Features**:
- Laravel's built-in bcrypt hashing
- Automatic password hashing on save
- Secure password verification
- Hash algorithm configuration
- Password reset token security

### FR12.2 Input Sanitization & SQL Injection Prevention
**Functionality**: All inputs sanitized and SQL injection prevented.

**Key Files Involved**:
- `app/Http/Requests/` - Form request validation
- `app/Models/*.php` - Eloquent ORM usage
- `resources/views/*.blade.php` - XSS prevention
- `config/database.php` - Database security settings

**Security Measures**:
- Eloquent ORM parameter binding
- Form request validation
- Blade template auto-escaping
- Input trimming and cleaning
- SQL query parameterization

### FR12.3 Admin Functionality Restriction
**Functionality**: Admin features restricted to authorized users.

**Key Files Involved**:
- `app/Http/Middleware/EnsureUserIsAdmin.php` - Admin middleware
- `app/Models/User.php` - Role verification methods
- `routes/web.php` - Route protection
- `app/Providers/AuthServiceProvider.php` - Authorization policies

**Security Features**:
- Role-based access control
- Middleware protection for admin routes
- Permission checks in controllers
- Unauthorized access logging
- Session-based authentication

---

## System Architecture Overview

### Database Structure
- **Users Table**: User accounts, roles, status, profile photos
- **Products Table**: Product information, pricing, stock
- **Product_Photos Table**: Product image management
- **Orders Table**: Order details, status, customer info
- **Order_Items Table**: Line items for each order
- **Reviews Table**: Product reviews and ratings
- **Cart_Items Table**: Shopping cart contents

### Key Directories
- **app/Http/Controllers/**: Request handling logic
- **app/Models/**: Database models and relationships
- **resources/views/**: Blade templates for UI
- **database/migrations/**: Database schema definitions
- **app/Http/Requests/**: Form validation classes
- **app/Middleware/**: Request processing middleware
- **public/**: Static assets and uploaded files
- **storage/app/public/**: File storage location

### Authentication Flow
1. User registration → Email verification → Login
2. Session creation → Role verification → Access control
3. Middleware checks → Permission validation → Route access

### Data Flow
1. User input → Validation → Processing → Database storage
2. Database queries → Model relationships → View rendering
3. AJAX requests → Controller responses → UI updates

This documentation provides a comprehensive overview of how each functional requirement is implemented and which files are involved in the La Petite Pâtisserie pastry shop management system.
