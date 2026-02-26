# Comprog5-Project

**La Petite Pâtisserie: Pastry Shop** — E-commerce &amp; User Management (Laravel + MySQL).

Repository: **https://github.com/dumppie/Comprog5-Project.git**

---

## Setup

1. **Clone / use this repo**
   ```bash
   git clone https://github.com/dumppie/Comprog5-Project.git
   cd Comprog5-Project
   ```

2. **Install dependencies**
   ```bash
   composer install
   cp .env.example .env
   php artisan key:generate
   ```
   Edit `.env`: set `DB_DATABASE`, `DB_USERNAME`, `DB_PASSWORD`.

3. **Database**  
   Create MySQL database `app_db`, then either:
   - Run `database/schema.sql` in MySQL, or  
   - Run `php artisan migrate` and `php artisan db:seed`

4. **Storage link**
   ```bash
   php artisan storage:link
   ```

5. **Run**
   ```bash
   php artisan serve
   ```
   Open http://localhost:8000

See **USER_ACCOUNT_SETUP.md** for user account (FR1) details and creating the first admin.

---

## Pushing to GitHub

Remote is set to:

```text
https://github.com/dumppie/Comprog5-Project.git
```

If `git push -u origin main` fails with **"Could not resolve host: github.com"**:

- Your machine cannot reach GitHub (DNS or network).
- Try: check internet, VPN, firewall; flush DNS (`ipconfig /flushdns` on Windows); try again when connection is working.
- Ensure the remote is correct: `git remote -v`  
  If needed: `git remote set-url origin https://github.com/dumppie/Comprog5-Project.git`

After fixing connectivity, commit and push as usual:

```bash
git add .
git commit -m "Your message"
git push -u origin main
```
