# Changelog

All notable changes to `Laravel` will be documented in this file.

## v0.1.5 - 2026-01-15

### Maintenance

- Converted CLAUDE.md to AGENTS.md with symlink for OpenCode compatibility
- Updated dependabot/fetch-metadata from 2.4.0 to 2.5.0

**Full Changelog**: https://github.com/hardimpactdev/craft-laravel/compare/v0.1.4...v0.1.5

## v0.1.4 - 2026-01-08

### New Features

- Added release skill for standardized GitHub releases via GitHub CLI

### Documentation

- Document versioning workflow and CHANGELOG format
- Include troubleshooting guide for releases

**Full Changelog**: https://github.com/hardimpactdev/craft-laravel/compare/v0.1.3...v0.1.4

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

First tagged release of craft-laravel, the scaffolding companion package for craft-starterkit.

#### Features

- **Modular Setup System**: Run individual setups as needed
  - `craft:setup auth` - Add authentication
  - `craft:setup dashboard` - Add dashboard + settings pages
  - `craft:setup app` - Full app (auth + dashboard combined)
  - `craft:setup cms` - Add Filament CMS
  - `craft:setup multilanguage` - Add language files
  

#### Bug Fixes

- Fixed namespace resolution in SetupCommand
- Fixed `scaffold()` → `setup()` method call in CMS auth task
- Renamed task file to match class name (RunSetupAuthTask)

#### Compatibility

- Aligned with craft-starterkit's current state
- Removed redundant vite i18n config tasks (already in starterkit)
- Added `language` field to User type stub
- SetupApp no longer forces CMS installation

#### Breaking Changes

- `craft:setup app` no longer includes CMS - run `craft:setup cms` separately if needed
