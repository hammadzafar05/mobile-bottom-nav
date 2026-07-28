<?php

use Filament\Navigation\NavigationGroup;
use Filament\Navigation\NavigationItem;
use Hammadzafar05\MobileBottomNav\MobileBottomNav;
use Hammadzafar05\MobileBottomNav\MobileBottomNavItem;

// --- Arch Tests ---

arch('it will not use debugging functions')
    ->expect(['dd', 'dump', 'ray'])
    ->each->not->toBeUsed();

// --- MobileBottomNavItem Unit Tests ---

it('can be created with make()', function () {
    $item = MobileBottomNavItem::make('Dashboard');

    expect($item)->toBeInstanceOf(MobileBottomNavItem::class)
        ->and($item->getLabel())->toBe('Dashboard');
});

it('supports fluent builder pattern', function () {
    $item = MobileBottomNavItem::make('Dashboard')
        ->icon('heroicon-o-home')
        ->activeIcon('heroicon-s-home')
        ->url('/dashboard')
        ->sort(1)
        ->isActive(true);

    expect($item)->toBeInstanceOf(MobileBottomNavItem::class)
        ->and($item->getIcon())->toBe('heroicon-o-home')
        ->and($item->getActiveIcon())->toBe('heroicon-s-home')
        ->and($item->getUrl())->toBe('/dashboard')
        ->and($item->getSort())->toBe(1)
        ->and($item->isActiveState())->toBeTrue();
});

it('evaluates closure for url', function () {
    $item = MobileBottomNavItem::make('Dashboard')
        ->url(fn() => '/dynamic-url');

    expect($item->getUrl())->toBe('/dynamic-url');
});

it('evaluates closure for isActive', function () {
    $item = MobileBottomNavItem::make('Dashboard')
        ->isActive(fn() => true);

    expect($item->isActiveState())->toBeTrue();
});

it('evaluates closure for visibility', function () {
    $item = MobileBottomNavItem::make('Dashboard')
        ->visible(fn() => false);

    expect($item->isVisible())->toBeFalse();
});

it('defaults to visible', function () {
    $item = MobileBottomNavItem::make('Dashboard');

    expect($item->isVisible())->toBeTrue();
});

it('defaults to inactive', function () {
    $item = MobileBottomNavItem::make('Dashboard');

    expect($item->isActiveState())->toBeFalse();
});

it('supports numeric badge with color', function () {
    $item = MobileBottomNavItem::make('Inbox')
        ->badge(5, 'danger');

    expect($item->getBadge())->toBe(5)
        ->and($item->getBadgeColor())->toBe('danger');
});

it('supports string badge', function () {
    $item = MobileBottomNavItem::make('Updates')
        ->badge('new');

    expect($item->getBadge())->toBe('new')
        ->and($item->getBadgeColor())->toBeNull();
});

it('evaluates closures for badge and badge color via badge()', function () {
    $item = MobileBottomNavItem::make('Inbox')
        ->badge(fn() => 12, fn() => 'success');

    expect($item->getBadge())->toBe(12)
        ->and($item->getBadgeColor())->toBe('success');
});

it('supports setting badge color separately', function () {
    $item = MobileBottomNavItem::make('Updates')
        ->badge(3)
        ->badgeColor('warning');

    expect($item->getBadge())->toBe(3)
        ->and($item->getBadgeColor())->toBe('warning');
});

it('evaluates closure for badgeColor()', function () {
    $item = MobileBottomNavItem::make('Updates')
        ->badge(3)
        ->badgeColor(fn() => 'warning');

    expect($item->getBadge())->toBe(3)
        ->and($item->getBadgeColor())->toBe('warning');
});

it('sorts by default to 0', function () {
    $item = MobileBottomNavItem::make('Dashboard');

    expect($item->getSort())->toBe(0);
});

it('returns null for url when not set', function () {
    $item = MobileBottomNavItem::make('Dashboard');

    expect($item->getUrl())->toBeNull();
});

it('returns null for icon when not set', function () {
    $item = MobileBottomNavItem::make('Dashboard');

    expect($item->getIcon())->toBeNull();
});

it('returns null for active icon when not set', function () {
    $item = MobileBottomNavItem::make('Dashboard');

    expect($item->getActiveIcon())->toBeNull();
});

// --- MobileBottomNav Plugin Unit Tests ---

it('returns the correct plugin id', function () {
    $plugin = MobileBottomNav::make();

    expect($plugin->getId())->toBe('mobile-bottom-nav');
});

it('disables navigation extraction when items are set manually', function () {
    $plugin = MobileBottomNav::make();

    $items = [
        MobileBottomNavItem::make('Home')->icon('heroicon-o-home')->url('/'),
        MobileBottomNavItem::make('Settings')->icon('heroicon-o-cog')->url('/settings'),
    ];

    $plugin->items($items);

    $reflection = new ReflectionClass($plugin);
    $prop = $reflection->getProperty('useNavigationExtraction');

    expect($prop->getValue($plugin))->toBeFalse();
});

it('enables navigation extraction with fromNavigation()', function () {
    $plugin = MobileBottomNav::make();
    $plugin->fromNavigation(5);

    $reflection = new ReflectionClass($plugin);

    $extractionProp = $reflection->getProperty('useNavigationExtraction');
    $limitProp = $reflection->getProperty('navigationLimit');

    expect($extractionProp->getValue($plugin))->toBeTrue()
        ->and($limitProp->getValue($plugin))->toBe(5);
});

it('disables more button', function () {
    $plugin = MobileBottomNav::make();
    $plugin->moreButton(false);

    $reflection = new ReflectionClass($plugin);
    $prop = $reflection->getProperty('moreButtonEnabled');

    expect($prop->getValue($plugin))->toBeFalse();
});

it('sets custom more button label', function () {
    $plugin = MobileBottomNav::make();
    $plugin->moreButtonLabel('Menu');

    $reflection = new ReflectionClass($plugin);
    $prop = $reflection->getProperty('moreButtonLabel');

    expect($prop->getValue($plugin))->toBe('Menu');
});

it('sets custom render hook', function () {
    $plugin = MobileBottomNav::make();
    $plugin->renderHook('panels::body.start');

    $reflection = new ReflectionClass($plugin);
    $prop = $reflection->getProperty('renderHook');

    expect($prop->getValue($plugin))->toBe('panels::body.start');
});

it('resolveItems filters invisible items', function () {
    $plugin = MobileBottomNav::make();

    $items = [
        MobileBottomNavItem::make('Home')->icon('heroicon-o-home')->url('/'),
        MobileBottomNavItem::make('Hidden')->icon('heroicon-o-eye-slash')->url('/hidden')->visible(false),
        MobileBottomNavItem::make('Settings')->icon('heroicon-o-cog')->url('/settings'),
    ];

    $plugin->items($items);

    $reflection = new ReflectionClass($plugin);
    $method = $reflection->getMethod('resolveItems');

    $resolved = $method->invoke($plugin);

    expect($resolved)->toHaveCount(2)
        ->and($resolved[0]->getLabel())->toBe('Home')
        ->and($resolved[1]->getLabel())->toBe('Settings');
});

it('extractFromNavigation preserves natural group/item order instead of globally re-sorting', function () {
    // Group A first, ascending sort within group; Group B second, ascending sort within group.
    // Group B's items intentionally use LOWER raw sort values than Group A's — this is what the
    // old usort() got wrong, since it compared sort values across unrelated groups.
    $groupA = NavigationGroup::make('Group A')->items([
        NavigationItem::make('Dashboard')->icon('heroicon-o-home')->sort(10),
        NavigationItem::make('Reports')->icon('heroicon-o-chart-bar')->sort(20),
    ]);

    $groupB = NavigationGroup::make('Group B')->items([
        NavigationItem::make('Users')->icon('heroicon-o-users')->sort(1),
        NavigationItem::make('Settings')->icon('heroicon-o-cog')->sort(2),
    ]);

    $fakeManager = Mockery::mock(\Filament\FilamentManager::class);
    $fakeManager->shouldReceive('getNavigation')->andReturn([$groupA, $groupB]);
    app()->instance('filament', $fakeManager);

    $plugin = MobileBottomNav::make()->fromNavigation(4)->moreButton(false);

    $reflection = new ReflectionClass($plugin);
    $method = $reflection->getMethod('resolveItems');

    $resolved = $method->invoke($plugin);

    expect($resolved)->toHaveCount(4)
        ->and($resolved[0]->getLabel())->toBe('Dashboard')
        ->and($resolved[1]->getLabel())->toBe('Reports')
        ->and($resolved[2]->getLabel())->toBe('Users')
        ->and($resolved[3]->getLabel())->toBe('Settings');
});

// --- render() Output Tests ---

/**
 * @param  array<NavigationGroup>  $groups
 */
function fakeAuthedPanel(array $groups, bool $isAuthed = true): void
{
    $guard = Mockery::mock(\Illuminate\Contracts\Auth\Guard::class);
    $guard->shouldReceive('check')->andReturn($isAuthed);

    $manager = Mockery::mock(\Filament\FilamentManager::class);
    $manager->shouldReceive('auth')->andReturn($guard);
    $manager->shouldReceive('hasTenancy')->andReturn(false);
    $manager->shouldReceive('getNavigation')->andReturn($groups);

    app()->instance('filament', $manager);
}

function navGroupWithIcons(): NavigationGroup
{
    return NavigationGroup::make('Main')->items([
        NavigationItem::make('Dashboard')->icon('heroicon-o-home'),
        NavigationItem::make('Users')->icon('heroicon-o-users'),
    ]);
}

function renderPlugin(MobileBottomNav $plugin): string
{
    return (new ReflectionClass($plugin))->getMethod('render')->invoke($plugin);
}

it('hides the sidebar toggle when the bar renders and the More button is enabled', function () {
    fakeAuthedPanel([navGroupWithIcons()]);

    $html = renderPlugin(MobileBottomNav::make());

    expect($html)->toContain('.fi-topbar-open-sidebar-btn')
        ->and($html)->toContain('.fi-layout-sidebar-toggle-btn-ctn');
});

it('leaves the sidebar toggle alone when the More button is disabled', function () {
    fakeAuthedPanel([navGroupWithIcons()]);

    $html = renderPlugin(MobileBottomNav::make()->moreButton(false));

    // The bar itself still renders — only the toggle-hiding CSS is withheld.
    expect($html)->toContain('fi-bottom-nav')
        ->and($html)->not->toContain('.fi-topbar-open-sidebar-btn')
        ->and($html)->not->toContain('.fi-layout-sidebar-toggle-btn-ctn');
});

it('leaves the sidebar toggle alone when opted out', function () {
    fakeAuthedPanel([navGroupWithIcons()]);

    $html = renderPlugin(MobileBottomNav::make()->hideSidebarToggle(false));

    expect($html)->toContain('fi-bottom-nav')
        ->and($html)->not->toContain('.fi-topbar-open-sidebar-btn')
        ->and($html)->not->toContain('.fi-layout-sidebar-toggle-btn-ctn');
});

it('emits nothing for a guest', function () {
    fakeAuthedPanel([navGroupWithIcons()], isAuthed: false);

    expect(renderPlugin(MobileBottomNav::make()))->toBe('');
});

it('emits nothing when no items resolve', function () {
    // No icon anywhere, so extractFromNavigation() drops every item.
    fakeAuthedPanel([
        NavigationGroup::make('Main')->items([
            NavigationItem::make('Dashboard'),
        ]),
    ]);

    expect(renderPlugin(MobileBottomNav::make()))->toBe('');
});

it('resolveItems returns all visible items when using manual items', function () {
    $plugin = MobileBottomNav::make();

    $items = [
        MobileBottomNavItem::make('Home')->icon('heroicon-o-home')->url('/'),
        MobileBottomNavItem::make('Search')->icon('heroicon-o-magnifying-glass')->url('/search'),
        MobileBottomNavItem::make('Profile')->icon('heroicon-o-user')->url('/profile'),
    ];

    $plugin->items($items);

    $reflection = new ReflectionClass($plugin);
    $method = $reflection->getMethod('resolveItems');

    $resolved = $method->invoke($plugin);

    expect($resolved)->toHaveCount(3);
});
