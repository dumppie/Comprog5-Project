# User Account Management (FR1) – Setup

This project implements **User Account Management (CRUD)** using the MySQL database from `database/schema.sql`.

## Requirements

- PHP 8.2+
- Composer
- MySQL (database `app_db`)

## Setup

### 1. Install dependencies

```bash
composer install
```

### 2. Environment

```bash
copy .env.example .env
php artisan key:generate
```

Edit `.env`: set `DB_DATABASE`, `DB_USERNAME`, `DB_PASSWORD` for MySQL.

### 3. Database

**Option A – Use existing MySQL schema (recommended if you already ran `schema.sql`)**  
Ensure `app_db` exists and has tables from `database/schema.sql`. If your `users` table has no `address` column, add it:

```sql
ALTER TABLE users ADD COLUMN address TEXT DEFAULT NULL AFTER profile_photo;
```

**Option B – Use Laravel migrations only**  
Create the database, then run:

```bash
php artisan migrate
php artisan db:seed
```

This creates `roles`, `user_statuses`, `users`, and `sessions`, and seeds roles and user statuses.

### 4. Storage link (profile photos)

```bash
php artisan storage:link
```

### 5. Create first admin (optional)

After seeding, create an admin user and verify email so you can log in as admin:

```bash
php artisan tinker
```

Then in tinker:

```php
$roleAdmin = \App\Models\Role::where('name', 'admin')->first();
$statusActive = \App\Models\UserStatus::where('name', 'active')->first();
\App\Models\User::create([
    'name' => 'Admin',
    'email' => 'admin@example.com',
    'password' => bcrypt('Password1'),
    'role_id' => $roleAdmin->id,
    'user_status_id' => $statusActive->id,
    'email_verified_at' => now(),
]);
```

### 6. Run the app

```bash
php artisan serve
```

Open http://localhost:8000

---

## Implemented features (FR1)

| ID   | Requirement | Implementation |
|------|-------------|----------------|
| FR1.1 | Register with name, email, password, contact number, profile photo | `RegisterController`, `RegisterUserRequest`, `auth/register` view |
| FR1.2 | Email verification after registration; only verified users can log in | `User` implements `MustVerifyEmail`, `verified` middleware, `auth/verify-email` + resend |
| FR1.3 | Secure login and logout | `LoginController` (login + logout), `auth/login` view |
| FR1.4 | Update name, address, password, profile photo | `ProfileController`, `UpdateProfileRequest`, `profile/edit` view |
| FR1.5 | Admin: searchable, sortable datatable (avatar, name, email, role, status, registered date) | `Admin\UserController@index`, `admin/users/index` view |
| FR1.6 | Admin: set user status (active/inactive); inactive cannot log in | Status dropdown in datatable; `LoginController` checks `isActive()` |
| FR1.7 | Admin: update user role; change applies on next request | Role dropdown in datatable |
| FR1.8 | Admin cannot deactivate or demote own account | Guard in `updateStatus` and `updateRole`; “Current user” shown in table |

## Routes

- `/` – Home  
- `/register`, `/login`, `/logout`  
- `/email/verify` – verification notice; resend link  
- `/profile` – edit profile (auth + verified)  
- `/admin/users` – user management (auth + verified + admin); 403 if not admin  

Unauthenticated access to `/admin/*` or `/profile` redirects to `/login` (FR3.2).  
Authenticated non-admin access to `/admin/*` returns 403 (FR3.3).
