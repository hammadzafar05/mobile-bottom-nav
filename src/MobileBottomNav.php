<?php

namespace Hammadzafar05\MobileBottomNav;

use Filament\Contracts\Plugin;
use Filament\Navigation\NavigationGroup;
use Filament\Panel;
use Filament\View\PanelsRenderHook;

class MobileBottomNav implements Plugin
{
    /** @var array<MobileBottomNavItem>|null */
    protected ?array $items = null;

    protected int $navigationLimit = 3;

    protected bool $moreButtonEnabled = true;

    protected string $moreButtonLabel = 'mobile-bottom-nav::mobile-bottom-nav.more';

    protected string $renderHook = PanelsRenderHook::BODY_END;

    protected bool $useNavigationExtraction = true;

    public static function make(): static
    {
        return app(static::class);
    }

    public static function get(): static
    {
        /** @var static $plugin */
        $plugin = filament(app(static::class)->getId());

        return $plugin;
    }

    public function getId(): string
    {
        return 'mobile-bottom-nav';
    }

    /**
     * @param  array<MobileBottomNavItem>  $items
     */
    public function items(array $items): static
    {
        $this->items = $items;
        $this->useNavigationExtraction = false;

        return $this;
    }

    public function fromNavigation(int $limit = 3): static
    {
        $this->navigationLimit = $limit;
        $this->useNavigationExtraction = true;

        return $this;
    }

    public function moreButton(bool $enabled = true): static
    {
        $this->moreButtonEnabled = $enabled;

        return $this;
    }

    public function moreButtonLabel(string $label): static
    {
        $this->moreButtonLabel = $label;

        return $this;
    }

    public function renderHook(string $hook): static
    {
        $this->renderHook = $hook;

        return $this;
    }

    public function register(Panel $panel): void
    {
        $panel->renderHook(
            $this->renderHook,
            fn (): string => $this->render(),
        );
    }

    public function boot(Panel $panel): void {}

    protected function render(): string
    {
        if (! filament()->auth()->check()) {
            return '';
        }

        if (filament()->hasTenancy() && ! filament()->getTenant()) {
            return '';
        }

        $items = $this->resolveItems();

        if ($items === []) {
            return '';
        }

        return view('mobile-bottom-nav::bottom-navigation', [
            'items' => $items,
            'moreButtonEnabled' => $this->moreButtonEnabled,
            'moreButtonLabel' => __($this->moreButtonLabel),
        ])->render();
    }

    /**
     * @return array<MobileBottomNavItem>
     */
    protected function resolveItems(): array
    {
        if (! $this->useNavigationExtraction && $this->items !== null) {
            return array_values(array_filter(
                $this->items,
                fn (MobileBottomNavItem $item): bool => $item->isVisible(),
            ));
        }

        return $this->extractFromNavigation();
    }

    /**
     * @return array<MobileBottomNavItem>
     */
    protected function extractFromNavigation(): array
    {
        /** @var array<NavigationGroup> $groups */
        $groups = filament()->getNavigation();

        $allItems = [];

        foreach ($groups as $group) {
            $groupIcon = $group->getIcon();

            foreach ($group->getItems() as $navItem) {
                if ($navItem->isHidden()) {
                    continue;
                }

                $icon = $navItem->getIcon() ?? $groupIcon;

                if ($icon === null) {
                    continue;
                }

                $bottomItem = MobileBottomNavItem::make($navItem->getLabel())
                    ->icon($icon)
                    ->url($navItem->getUrl() ?? '#')
                    ->sort($navItem->getSort())
                    ->isActive($navItem->isActive());

                $activeIcon = $navItem->getActiveIcon();
                if ($activeIcon !== null) {
                    $bottomItem->activeIcon($activeIcon);
                }

                $badge = $navItem->getBadge();
                if ($badge !== null) {
                    $bottomItem->badge($badge, $navItem->getBadgeColor());
                }

                $allItems[] = $bottomItem;
            }
        }

        $limit = $this->moreButtonEnabled
            ? $this->navigationLimit - 1
            : $this->navigationLimit;

        return array_slice($allItems, 0, max(1, $limit));
    }
}
