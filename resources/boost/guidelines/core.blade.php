## Liftoff Laravel

Companion scaffolding package for [liftoff-starterkit](https://github.com/hardimpactdev/liftoff-starterkit). Provides commands to set up authentication, dashboard, CMS (Filament), and multi-language support.

### Quick Decision Guide

Use this to determine which setup command to run:

| User Request | Command | Why |
|--------------|---------|-----|
| "Add authentication" | `php artisan liftoff:setup app` | `app` includes auth + dashboard + settings |
| "Add a dashboard" | `php artisan liftoff:setup app` | `app` includes auth + dashboard + settings |
| "Set up the app" | `php artisan liftoff:setup app` | Complete frontend application stack |
| "Add a CMS" | `php artisan liftoff:setup cms` | Filament admin panel with auth |
| "Add admin panel" | `php artisan liftoff:setup cms` | Filament admin panel with auth |
| "Set up everything" | Run both `app` then `cms` | Full stack: auth + dashboard + CMS |
| "Add translations" | `php artisan liftoff:setup multilanguage` | i18n support |

### Available Commands

| Command | What It Sets Up |
|---------|-----------------|
| `php artisan liftoff:setup app` | Auth + Dashboard + Settings (recommended for most apps) |
| `php artisan liftoff:setup cms` | Auth + Filament CMS admin panel |
| `php artisan liftoff:setup multilanguage` | Translation files and i18n support |
| `php artisan liftoff:setup auth` | Auth only (use `app` instead unless building custom dashboard) |
| `php artisan liftoff:setup dashboard` | Dashboard only (requires auth first, use `app` instead) |

### Complete Setup Workflows

#### Standard Application (Auth + Dashboard)

@verbatim
<code-snippet name="Setup standard application" lang="bash">
# Run the app scaffolder
php artisan liftoff:setup app

# Install dependencies and build
bun install
bun run build

# Run migrations
php artisan migrate
</code-snippet>
@endverbatim

#### Application with CMS Admin Panel

@verbatim
<code-snippet name="Setup application with CMS" lang="bash">
# Run the CMS scaffolder (includes auth)
php artisan liftoff:setup cms

# Install dependencies and build
bun install
bun run build

# Run migrations
php artisan migrate

# Create an admin user for Filament
php artisan make:filament-user
</code-snippet>
@endverbatim

#### Full Stack (Dashboard + CMS)

@verbatim
<code-snippet name="Setup complete stack with dashboard and CMS" lang="bash">
# Run app first (auth + dashboard)
php artisan liftoff:setup app

# Then add CMS (Filament admin panel)
php artisan liftoff:setup cms

# Install dependencies and build
bun install
bun run build

# Run migrations
php artisan migrate

# Create an admin user for Filament
php artisan make:filament-user
</code-snippet>
@endverbatim

#### Adding Multi-Language Support

@verbatim
<code-snippet name="Add i18n support to existing app" lang="bash">
php artisan liftoff:setup multilanguage
</code-snippet>
@endverbatim

### What Each Setup Creates

#### App Setup (Recommended)
- `app/Http/Controllers/Auth/*` - Authentication controllers
- `app/Http/Controllers/DashboardController.php` - Dashboard
- `app/Http/Controllers/Settings/*` - Profile, password, appearance settings
- `app/Http/Middleware/HandleInertiaRequests.php` - Inertia middleware
- `resources/js/pages/auth/*` - Login, register, password reset pages
- `resources/js/pages/dashboard/*` - Dashboard pages
- `resources/js/pages/settings/*` - Settings pages
- `tests/Feature/Auth/*` - Authentication tests
- `tests/Feature/Settings/*` - Settings tests
- Database migrations for users table

#### CMS Setup
- Everything from Auth setup (controllers, views, tests)
- `filament/filament:^3.3` Composer package
- `app/Filament/*` - Filament resources and pages
- `app/Providers/Filament/AdminPanelProvider.php` - Admin panel config
- `public/css/filament/*` - Filament theme
- NPM packages: `@tailwindcss/forms`, `@tailwindcss/typography`

#### MultiLanguage Setup
- `lang/*` - Translation files for multiple languages
- `resources/js/pages/TranslationExample.vue` - Example component

### Verification

After setup, verify everything works:

@verbatim
<code-snippet name="Verify setup" lang="bash">
# Run tests
php artisan test

# Start dev server and check manually
bun run dev
# Visit http://localhost:8000 in browser
</code-snippet>
@endverbatim

### Important Notes

1. **Always run migrations** after any setup that includes auth
2. **Always run `bun install && bun run build`** after scaffolding
3. **For CMS**, you must create an admin user with `php artisan make:filament-user`
4. **Routes are auto-generated** via Waymaker - no manual route configuration needed
5. **Setups are idempotent** - safe to run multiple times
