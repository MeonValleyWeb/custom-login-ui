# CHANGELOG_PROGRESS - custom-login-ui

**Cloned:** 2026-04-02  
**Status:** Complete WordPress MU-plugin

## Project Overview
Branded, split-screen WordPress login page. Left branding panel, right login form. Hides default WP branding.

## Tech
- **Type:** WordPress MU-plugin (or standard plugin)
- **PHP:** 8.x
- **Requirements:** WordPress 6.x+

## Features
- Split layout (branding left, form right)
- Custom logo with multiple fallbacks
- CSS variables for theming
- Fully responsive
- Hides default WP branding/links

## Logo Source Order
1. Attachment ID (default: 16752)
2. Theme asset: `assets/images/sdsa-login-logo.svg`
3. Uploads path
4. MU-plugin asset: `mu-plugins/assets/sdsa-login-logo.svg`

## Installation
**MU-plugin:** `wp-content/mu-plugins/custom-login-ui.php`
**Standard:** `wp-content/plugins/custom-login-ui/custom-login-ui.php`

## Pending Tasks
- [ ] Review if logo paths need customization
- [ ] Check responsive behavior on mobile
- [ ] Monitor for WordPress version compatibility

## Notes
- Author: Andrew Wilkinson / MeonValleyWeb
- Screenshot included for documentation

---
*This file is managed by Jarvis. Last updated: 2026-04-02*
