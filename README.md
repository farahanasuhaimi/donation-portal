# Ramadan Donation Portal

Laravel-based donation portal for Ramadan campaigns with:
- Public campaign browsing and donation pledges
- Account auth (email/password + Google OAuth)
- Role-based access (`donor`, `organizer`, `admin`)
- Organizer-owned campaigns
- Campaign archive flow (soft-delete style)

## Stack
- PHP 8.2+
- Laravel 12
- MySQL
- Blade + Vite
- Socialite (Google login)

## Core Features
### Public
- View active campaigns
- View campaign progress and recent donations
- Submit donation pledge
- Optional `nama samaran` for public donor name masking

### Accounts
- Single auth page (`/auth`) with tabbed login/register
- Role selection (`donor` / `organizer`) during auth
- Google OAuth login callback: `/auth/google/callback`

### Organizer
- Create campaigns
- Edit only campaigns they own
- Archive campaigns they own
- Cannot see archived campaigns in organizer dashboard

### Admin
- View all campaigns (including archived)
- Edit all campaigns
- View users overview:
  - Registered organizers
  - Registered donors
  - Unregistered donors (derived from donation records)

## Database Notes
Main domain tables:
- `campaigns`
- `donations`
- `users`

Additional fields added by custom migrations:
- Campaign ownership: `campaigns.organizer_user_id`
- Campaign archive: `campaigns.archived_at`, `campaigns.archived_by_user_id`
- Donor linkage: `donations.donor_user_id`, `donor_real_name`, `donor_alias_name`
- User alias: `users.alias_name`

## Local Setup
```bash
composer install
cp .env.example .env
php artisan key:generate
php artisan migrate
npm install
npm run build
php artisan serve
```

## Production Deploy (Hostinger)
From project root on server:
```bash
git pull origin main
composer install --no-dev --optimize-autoloader
php artisan migrate --force
php artisan optimize:clear
php artisan config:cache
php artisan route:cache
php artisan view:cache
```

## Required `.env` (Production)
```env
APP_ENV=production
APP_DEBUG=false
APP_URL=https://ramadan.farahana.com

DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=...
DB_USERNAME=...
DB_PASSWORD=...

GOOGLE_CLIENT_ID=...
GOOGLE_CLIENT_SECRET=...
GOOGLE_REDIRECT_URI=https://ramadan.farahana.com/auth/google/callback

FILESYSTEM_DISK=public
```

## Google OAuth Setup
In Google Cloud Console:
1. Create OAuth client credentials (Web application)
2. Add authorized redirect URI exactly:
   - `https://ramadan.farahana.com/auth/google/callback`
3. Put credentials into `.env`
4. Clear/cache config:
```bash
php artisan optimize:clear
php artisan config:cache
```

## File Upload / QR Image
- QR images are stored on `public` disk (`storage/app/public/qr_images`)
- Ensure storage symlink exists in production:
  - If `php artisan storage:link` fails due disabled `exec()`, create symlink manually in shell.

## Campaign Status Rules
- `Achieved`: total collected >= target
- `Active`: not achieved, not archived, and before deadline
- `Closed`: deadline passed
- `Archived`: manually archived by organizer/admin; hidden from public and organizer views

## Next Step (Security Hardening)
Planned next implementation:
1. Route-level rate limiting for auth and donation actions
2. Security headers middleware (CSP, X-Frame-Options, etc.)
3. Organizer approval workflow (instead of open organizer self-selection)
4. Email verification requirement for email/password signups
5. Audit/security logging for repeated failed auth attempts

## Troubleshooting
### Google login 500 (`Socialite class not found`)
```bash
composer require laravel/socialite:^5.16 --no-interaction
composer dump-autoload -o
php artisan optimize:clear
```

### Migration says table already exists
- Table was likely created manually before migrations.
- Align migration state or use a clean new database and run migrations.

### Config changes not reflected
```bash
php artisan optimize:clear
php artisan config:cache
```
