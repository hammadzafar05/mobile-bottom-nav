# Changelog

All notable changes to `mobile-bottom-nav` will be documented in this file.

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
