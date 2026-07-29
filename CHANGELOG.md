# Changelog

All notable changes to `mobile-bottom-nav` will be documented in this file.

## v1.4.1 - 2026-07-29

### What's Changed

* Docs/link mobile preset by @hammadzafar05 in https://github.com/hammadzafar05/mobile-bottom-nav/pull/9
* fix: stop enforcing a Filament floor, recommend it instead by @hammadzafar05 in https://github.com/hammadzafar05/mobile-bottom-nav/pull/10

**Full Changelog**: https://github.com/hammadzafar05/mobile-bottom-nav/compare/v1.4.0...v1.4.1

## Unreleased

- Added: a note in the readme recommending Filament 4.11.5+ or 5.6.5+. Earlier releases in both
  majors carry four published advisories, the most serious an unauthenticated temporary file upload
  on auth pages. The version constraint is deliberately left at `^4.0 || ^5.0`: this plugin is
  compatible with all of them, and which patch level of the framework you run is your application's
  call, not a bottom navigation bar's.
- Added: a pointer to
  [`filament-mobile-preset`](https://github.com/hammadzafar05/filament-mobile-preset), which
  bundles this plugin with mobile-first defaults for the rest of the panel.
- Fixed: `* text=auto eol=lf` in `.gitattributes`, so Windows clones stop reporting Pint
  `line_ending` failures on files that are already clean in the repository.

## v1.4.0 - 2026-07-28

- Added: `->hideSidebarToggle()`, which hides Filament's redundant topbar hamburger while the
  bottom navigation bar is on screen, since the bar's own "More" button already opens the sidebar.
  Only applied when the bar actually rendered and the "More" button is enabled, so the sidebar
  always stays reachable. Opt out with `->hideSidebarToggle(false)`, which is needed if you run
  `->moreButton(false)`.
- Bumped `actions/checkout` from 7.0.0 to 7.0.1
  ([#7](https://github.com/hammadzafar05/mobile-bottom-nav/pull/7)).

**Full Changelog**: https://github.com/hammadzafar05/mobile-bottom-nav/compare/v1.3.1...v1.4.0

## v1.3.1 - 2026-07-16

**Full Changelog**: https://github.com/hammadzafar05/mobile-bottom-nav/compare/v1.3...v1.3.1

## 1.3.0 - 2026-07-11

- Fixed: auto-discovery mode (`extractFromNavigation()`, used when `->items()` is not called)
  previously re-sorted all navigation items globally by their raw `navigationSort` value, ignoring
  which navigation group each item belonged to. Since `navigationSort` is only meaningful *within* a
  group, this could surface an unrelated page ahead of a consuming app's actual primary navigation.
  The bottom nav now follows the exact order Filament's own sidebar renders (group order, then
  sort order within each group), matching what a user would see scanning the real sidebar top to
  bottom.

## 1.0.2 - 2026-02-24

- Rewrote README for clarity — removed unnecessary custom theme and `@source` setup instructions (plugin uses inline CSS, not Tailwind utility classes)
- Added light and dark mode screenshots
- Updated description to reflect Filament v4 and v5 support

## 1.0.1 - 2025-XX-XX

- Added support for Filament v4

## 1.0.0 - 2025-XX-XX

- Initial release
