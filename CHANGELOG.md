# Changelog

All notable changes to `Laravel` will be documented in this file.

## v0.1.3 - 2026-01-08

### New Features

- Added Laravel Boost integration with AI guidelines for scaffolding commands

### Documentation

- Improved README with clearer setup instructions
- Added documentation for Dashboard and Multilanguage scaffolders
- Added AI-assisted development section

## v0.1.2 - 2026-01-08

### Bug Fixes

- Fixed HandleInertiaRequests middleware using route() helper which caused 500 errors when routes weren't registered
- Changed navigation URLs to use direct paths instead of route names for reliability

## v0.1.1 - 2026-01-08

### Bug Fixes

- Fixed HandleInertiaRequests middleware stub referencing non-existent HomeController route
- Changed default navigation route to use DashboardController.show
- Fixed typo in footer navigation: 'GitHewqub' → 'GitHub'

## v0.1.0 - Initial Release - 2026-01-07

### Initial Release

First tagged release of liftoff-laravel, the scaffolding companion package for liftoff-starterkit.

#### Features

- **Modular Setup System**: Run individual setups as needed
  - `liftoff:setup auth` - Add authentication
  - `liftoff:setup dashboard` - Add dashboard + settings pages
  - `liftoff:setup app` - Full app (auth + dashboard combined)
  - `liftoff:setup cms` - Add Filament CMS
  - `liftoff:setup multilanguage` - Add language files
  

#### Bug Fixes

- Fixed namespace resolution in SetupCommand
- Fixed `scaffold()` → `setup()` method call in CMS auth task
- Renamed task file to match class name (RunSetupAuthTask)

#### Compatibility

- Aligned with liftoff-starterkit's current state
- Removed redundant vite i18n config tasks (already in starterkit)
- Added `language` field to User type stub
- SetupApp no longer forces CMS installation

#### Breaking Changes

- `liftoff:setup app` no longer includes CMS - run `liftoff:setup cms` separately if needed
