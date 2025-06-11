# Livtoff Laravel Package

[![Latest Version on Packagist](https://img.shields.io/packagist/v/livtoff/laravel.svg?style=flat-square)](https://packagist.org/packages/livtoff/laravel)
[![GitHub Tests Action Status](https://img.shields.io/github/actions/workflow/status/livtoff/laravel/run-tests.yml?branch=main&label=tests&style=flat-square)](https://github.com/livtoff/laravel/actions?query=workflow%3Arun-tests+branch%3Amain)
[![GitHub Code Style Action Status](https://img.shields.io/github/actions/workflow/status/livtoff/laravel/fix-php-code-style-issues.yml?branch=main&label=code%20style&style=flat-square)](https://github.com/livtoff/laravel/actions?query=workflow%3A"Fix+PHP+code+style+issues"+branch%3Amain)
[![Total Downloads](https://img.shields.io/packagist/dt/livtoff/laravel.svg?style=flat-square)](https://packagist.org/packages/livtoff/laravel)

The Livtoff Laravel package provides scaffolding commands and utilities for rapidly setting up Laravel applications with Vue.js, Inertia.js, and optional Filament CMS integration. It includes pre-built authentication, dashboard, settings, and CMS functionality.

## Support us

[<img src="https://github-ads.s3.eu-central-1.amazonaws.com/Laravel.jpg?t=1" width="419px" />](https://spatie.be/github-ad-click/Laravel)

We invest a lot of resources into creating [best in class open source packages](https://spatie.be/open-source). You can support us by [buying one of our paid products](https://spatie.be/open-source/support-us).

We highly appreciate you sending us a postcard from your hometown, mentioning which of our package(s) you are using. You'll find our address on [our contact page](https://spatie.be/about-us). We publish all received postcards on [our virtual postcard wall](https://spatie.be/open-source/postcards).

## Requirements

- PHP 8.1 or higher
- Laravel 10.x or 11.x
- Node.js and npm/bun for frontend assets

## Installation

You can install the package via composer:

```bash
composer require livtoff/laravel
```

## Scaffolders

The package provides powerful scaffolding commands to quickly set up different aspects of your application. All scaffolders automatically generate routes using the route-maker package.

### Available Scaffolders

#### 1. App Scaffolder (Full Application Setup)
The most comprehensive scaffolder that sets up a complete application with authentication and CMS functionality.

```bash
php artisan livtoff:setup app
```

This scaffolder includes:
- ✅ App class with redirect configuration
- ✅ Dashboard controller and views
- ✅ Settings pages (profile, password, appearance)
- ✅ HandleInertiaRequests middleware
- ✅ TypeScript type definitions
- ✅ Feature tests
- ✅ Full authentication system (runs Auth scaffolder)
- ✅ Filament CMS integration (runs CMS scaffolder)
- ✅ Automatic route generation

#### 2. Auth Scaffolder
Sets up a complete authentication system with login, registration, password reset, and email verification.

```bash
php artisan livtoff:setup auth
```

This scaffolder includes:
- ✅ Authentication controllers with route attributes
- ✅ Login request validation
- ✅ Vue.js authentication pages
- ✅ Authentication tests
- ✅ User migration publishing

**Note:** The auth scaffolder requires the App class to be present. If running standalone, ensure you have an App class or run the app scaffolder instead.

#### 3. CMS Scaffolder
Sets up Filament CMS with user management and authentication.

```bash
php artisan livtoff:setup cms
```

This scaffolder includes:
- ✅ App class with redirect configuration
- ✅ Full authentication system (runs Auth scaffolder)
- ✅ Filament package installation
- ✅ User resource for managing users
- ✅ Admin panel configuration
- ✅ Filament CSS build process
- ✅ Automatic route generation

### Route Generation

All scaffolders use the route-maker package to automatically generate routes from controller attributes. Routes are generated at the end of each scaffolding process, eliminating the need to manually run `php artisan route-maker:make`.

### Files and Directories Created

#### App Scaffolder creates:
```
app/
├── App.php
├── Http/
│   ├── Controllers/
│   │   ├── DashboardController.php
│   │   └── Settings/
│   │       ├── AppearanceController.php
│   │       ├── PasswordController.php
│   │       └── ProfileController.php
│   ├── Middleware/
│   │   └── HandleInertiaRequests.php
│   └── Requests/
│       └── Settings/
│           └── ProfileUpdateRequest.php
resources/js/
├── pages/
│   ├── Dashboard.vue
│   └── settings/
│       ├── Appearance.vue
│       ├── Password.vue
│       └── Profile.vue
└── types/
    └── index.d.ts
tests/Feature/
├── DashboardTest.php
└── Settings/
    ├── PasswordUpdateTest.php
    └── ProfileUpdateTest.php
```

#### Auth Scaffolder creates:
```
app/Http/
├── Controllers/Auth/
│   ├── ConfirmablePasswordController.php
│   ├── EmailVerificationNotificationController.php
│   ├── EmailVerificationPromptController.php
│   ├── ForgotPasswordController.php
│   ├── LoginController.php
│   ├── NewPasswordController.php
│   ├── RegisterController.php
│   └── VerifyEmailController.php
└── Requests/Auth/
    └── LoginRequest.php
resources/js/
├── layouts/auth/
│   └── AuthLayout.vue
└── pages/auth/
    ├── ConfirmPassword.vue
    ├── ForgotPassword.vue
    ├── Login.vue
    ├── Register.vue
    ├── ResetPassword.vue
    └── VerifyEmail.vue
tests/Feature/Auth/
├── AuthenticationTest.php
├── EmailVerificationTest.php
├── PasswordConfirmationTest.php
├── PasswordResetTest.php
└── RegistrationTest.php
```

### Usage Examples

#### Quick Start - Full Application
```bash
# Install the package
composer require livtoff/laravel

# Run the app scaffolder for a complete setup
php artisan livtoff:setup app

# Install frontend dependencies
npm install # or bun install

# Run migrations
php artisan migrate

# Start development server
npm run dev # or bun dev
```

#### Authentication Only
```bash
# Run just the auth scaffolder
php artisan livtoff:setup auth

# Note: Requires App class to be present
```

#### CMS with Authentication
```bash
# Run the CMS scaffolder (includes auth)
php artisan livtoff:setup cms

# Create a Filament user
php artisan make:filament-user
```

### Important Notes

1. **Middleware Replacement**: The HandleInertiaRequests middleware will be replaced if it already exists in your application.

2. **Route Attributes**: All controllers use route attributes from the route-maker package, eliminating the need for manual route definitions.

3. **App Class**: The App class provides a centralized location for application configuration, including login redirect routes.

4. **File Merging**: When copying directories, existing files are preserved unless they have the same name as files being copied.

5. **Dependencies**: Make sure to install the route-maker package if not already installed:
   ```bash
   composer require nckrtl/route-maker
   ```

## Testing

```bash
composer test
```

## Changelog

Please see [CHANGELOG](CHANGELOG.md) for more information on what has changed recently.

## Contributing

Please see [CONTRIBUTING](CONTRIBUTING.md) for details.

## Security Vulnerabilities

Please review [our security policy](../../security/policy) on how to report security vulnerabilities.

## Credits

- [nckrtl](https://github.com/nckrtl)
- [All Contributors](../../contributors)

## License

The MIT License (MIT). Please see [License File](LICENSE.md) for more information.
