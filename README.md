# Mobile Bottom Navigation for Filament

[![Latest Version on Packagist](https://img.shields.io/packagist/v/hammadzafar05/mobile-bottom-nav.svg?style=flat-square)](https://packagist.org/packages/hammadzafar05/mobile-bottom-nav)
[![GitHub Tests Action Status](https://img.shields.io/github/actions/workflow/status/hammadzafar05/mobile-bottom-nav/run-tests.yml?label=tests&style=flat-square)](https://github.com/hammadzafar05/mobile-bottom-nav/actions?query=workflow%3Arun-tests+branch%3Amain)
[![Total Downloads](https://img.shields.io/packagist/dt/hammadzafar05/mobile-bottom-nav.svg?style=flat-square)](https://packagist.org/packages/hammadzafar05/mobile-bottom-nav)

A thumb-friendly mobile bottom navigation bar for Filament panels. Automatically extracts items from your Filament navigation and renders a fixed bottom bar on mobile viewports — with full support for dark mode, safe-area insets, badges, and sidebar integration.

**Supports Filament v4 and v5.**

## Screenshots

#### Light Mode
![Light Mode](art/screenshot-light.png)

#### Dark Mode
![Dark Mode](art/screenshot-dark.png)

## Installation

```bash
composer require hammadzafar05/mobile-bottom-nav
```

That's it. No custom theme or additional CSS configuration is required.

## Usage

Register the plugin in your panel provider:

```php
use Hammadzafar05\MobileBottomNav\MobileBottomNav;

public function panel(Panel $panel): Panel
{
    return $panel
        ->plugins([
            MobileBottomNav::make(),
        ]);
}
```

The plugin automatically extracts your top navigation items and displays them in a bottom bar on mobile screens. On desktop, it stays hidden.

## Configuration

All configuration is optional and done via a fluent API.

### Navigation Limit & More Button

By default, the plugin shows 2 navigation items + a "More" button that opens the sidebar. You can adjust the total number of slots:

```php
MobileBottomNav::make()
    ->fromNavigation(5)     // 4 nav items + 1 More button
```

To disable the "More" button entirely:

```php
MobileBottomNav::make()
    ->fromNavigation(4)     // 4 nav items, no More button
    ->moreButton(false)
```

### Custom Items

Provide your own items instead of extracting from the navigation registry:

```php
use Hammadzafar05\MobileBottomNav\MobileBottomNav;
use Hammadzafar05\MobileBottomNav\MobileBottomNavItem;

MobileBottomNav::make()
    ->items([
        MobileBottomNavItem::make('Home')
            ->icon('heroicon-o-home')
            ->activeIcon('heroicon-s-home')
            ->url('/admin')
            ->isActive(fn () => request()->is('admin')),
        MobileBottomNavItem::make('Inbox')
            ->icon('heroicon-o-inbox')
            ->url('/admin/inbox')
            ->badge(5, 'danger'),
        MobileBottomNavItem::make('Profile')
            ->icon('heroicon-o-user')
            ->url('/admin/profile'),
    ])
```

### Conditional Visibility

Items support conditional visibility:

```php
MobileBottomNavItem::make('Admin')
    ->icon('heroicon-o-shield-check')
    ->url('/admin/settings')
    ->visible(fn () => auth()->user()?->isAdmin())
```

### All Options

| Method | Default | Description |
|--------|---------|-------------|
| `fromNavigation(int $limit)` | `3` | Total number of bottom bar slots (includes the "More" button if enabled) |
| `items(array $items)` | `null` | Provide custom `MobileBottomNavItem` instances (disables auto-extraction) |
| `moreButton(bool $enabled)` | `true` | Show/hide the "More" button that opens the sidebar |
| `moreButtonLabel(string $label)` | `'More'` (translatable) | Customize the "More" button label |
| `renderHook(string $hook)` | `PanelsRenderHook::BODY_END` | Change which Filament render hook is used |

### Publishing Views

If you need to customize the Blade template:

```bash
php artisan vendor:publish --tag="mobile-bottom-nav-views"
```

## Testing

```bash
composer test
```

## Changelog

Please see [CHANGELOG](CHANGELOG.md) for more information on what has changed recently.

## Contributing

Please see [CONTRIBUTING](.github/CONTRIBUTING.md) for details.

## Security Vulnerabilities

Please review [our security policy](.github/SECURITY.md) on how to report security vulnerabilities.

## Credits

- [Hammad Zafar](https://github.com/hammadzafar05)
- [All Contributors](../../contributors)

## License

The MIT License (MIT). Please see [License File](LICENSE.md) for more information.
