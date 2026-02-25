# Strict Defaults for LLM-Friendly Code

## What changed

### 1. Runtime defaults (`config/laravel.php` + `LaravelServiceProvider`)

Added 9 strict runtime defaults, all enabled by default. The service provider applies them in `packageBooted()`, gated by config key and environment.

| Config key | What it does | Environment |
|---|---|---|
| `strict_models` | `Model::shouldBeStrict()` — no lazy loading, no silently discarded attrs, no missing attr access | All |
| `auto_eager_load` | `Model::automaticallyEagerLoadRelationships()` — auto N+1 fix | All (Laravel 12.8+ only, `method_exists` guard) |
| `immutable_dates` | `Date::use(CarbonImmutable::class)` — prevents date mutation bugs | All |
| `aggressive_prefetching` | `Vite::useAggressivePrefetching()` — prefetches all assets | All |
| `force_https` | `URL::forceHttps()` | Production |
| `prohibit_destructive_commands` | `DB::prohibitDestructiveCommands()` — blocks `migrate:fresh` etc. | Production |
| `default_password_rules` | `Password::defaults(...)` — min 12, mixed case, numbers, symbols, uncompromised | Production |
| `prevent_stray_requests` | `Http::preventStrayRequests()` — unfaked HTTP calls throw | Test |
| `fake_sleep` | `Sleep::fake()` — `Sleep::for()` is instant in tests | Test |

Users can disable any default individually:

```php
// config/laravel.php
'defaults' => [
    'strict_models' => false,
],
```

### 2. `declare(strict_types=1)` in all stubs (36 files)

Added to every PHP class stub, test stub, and factory across `app`, `auth`, `cms`, and `task-tracking`. Skipped config files, translation arrays, and migrations.

This means every scaffolded file will have strict types from the start, and LLMs seeing the codebase will follow the pattern.

### 3. Boost guidelines updated

Added a "Strict Defaults" section to `resources/boost/guidelines/core.blade.php` documenting all active defaults with compatible code patterns. LLMs using Boost will know to:

- Never mutate dates (assign return values from `CarbonImmutable`)
- Always fake HTTP calls in tests
- Always add `declare(strict_types=1)` to new files
- How to disable specific defaults

### 4. PHPStan baseline

Added a baseline entry for the `method_exists(Model::class, 'automaticallyEagerLoadRelationships')` guard. PHPStan flags it as "always true" against the installed Laravel 12.x, but it's intentional for backwards compat with older versions.

## Starterkit implications

- **No starterkit changes needed** for the runtime defaults — they activate automatically via the package service provider
- **Existing scaffolded projects** get the defaults on `composer update` (config file publishes on install)
- **New projects** get `declare(strict_types=1)` in all scaffolded files automatically
- **Tests**: `Http::preventStrayRequests()` is now active — any test making real HTTP calls without `Http::fake()` will fail. Check existing starterkit tests for unfaked HTTP calls
- **Dates**: All date casts now return `CarbonImmutable`. Code that mutates dates in place (e.g. `$date->addDay()` without capturing the return) will silently stop working
