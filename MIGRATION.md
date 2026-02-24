# Migration Guide: Fix Auth Scaffold Namespace Issues

## Problem

Previous versions of the auth scaffold had hardcoded `App` namespace references in the following files:
- `resources/stubs/app/app/App.php` - Had hardcoded `namespace App;`
- `resources/stubs/auth/tests/Feature/Auth/RegistrationTest.php` - Had hardcoded `use App\App;`
- `resources/stubs/auth/tests/Feature/Auth/EmailVerificationTest.php` - Had hardcoded `use App\App;`

This caused issues when scaffolding into Laravel applications that use a custom namespace other than `App`. The generated files would reference `App\App` which didn't exist in those applications, causing errors like:
```
Class App\Facades\App not found
```

## Solution

The stub files now use the `{{namespace}}` placeholder which gets replaced during scaffolding with the actual application namespace (e.g., `App\`, `Custom\`, etc.).

## For Existing Consumers

If you have already scaffolded auth in your application and are experiencing issues:

### Option 1: Manual Fix (Recommended for existing projects)

If your application uses a custom namespace (not `App`), manually update the following files in your application:

1. **`app/App.php`**:
   ```php
   // Change from:
   namespace App;
   
   // To:
   namespace YourNamespace;
   ```

2. **`tests/Feature/Auth/RegistrationTest.php`**:
   ```php
   // Change from:
   use App\App;
   
   // To:
   use YourNamespace\App;
   ```

3. **`tests/Feature/Auth/EmailVerificationTest.php`**:
   ```php
   // Change from:
   use App\App;
   
   // To:
   use YourNamespace\App;
   ```

### Option 2: Re-scaffold (Recommended for new projects)

If you have a new project or can afford to lose any customizations:

1. Delete the generated auth files:
   ```bash
   rm -f app/App.php
   rm -rf app/Providers/FortifyServiceProvider.php
   rm -rf app/Actions/Fortify
   rm -rf app/Http/Controllers/Settings/TwoFactorAuthenticationController.php
   rm -rf app/Http/Requests/Settings/TwoFactorAuthenticationRequest.php
   rm -rf tests/Feature/Auth
   ```

2. Re-run the auth scaffold:
   ```bash
   php artisan craft:setup auth
   ```

## Verification

After fixing, verify the changes work correctly:

```bash
php artisan route:list | grep -E "login|register|logout"
php artisan test --filter=Auth
```

Both commands should complete without errors.