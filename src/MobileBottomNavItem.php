<?php

namespace Hammadzafar05\MobileBottomNav;

use BackedEnum;
use Closure;
use Illuminate\Contracts\Support\Htmlable;

class MobileBottomNavItem
{
    protected string | BackedEnum | Htmlable | null $icon = null;

    protected string | BackedEnum | Htmlable | null $activeIcon = null;

    protected string | Closure | null $url = null;

    protected bool | Closure $isActive = false;

    protected string | int | null | Closure $badge = null;

    /**
     * @var string | array<string> | null | Closure
     */
    protected string | array | null | Closure $badgeColor = null;

    protected int $sort = 0;

    protected bool | Closure $isVisible = true;

    protected string | Closure | null $labelOverride = null;

    final public function __construct(protected string $label) {}

    public static function make(string $label): static
    {
        return new static($label);
    }

    public function label(string | Closure $label): static
    {
        $this->labelOverride = $label;

        return $this;
    }

    public function icon(string | BackedEnum | Htmlable $icon): static
    {
        $this->icon = $icon;

        return $this;
    }

    public function activeIcon(string | BackedEnum | Htmlable $activeIcon): static
    {
        $this->activeIcon = $activeIcon;

        return $this;
    }

    public function url(string | Closure $url): static
    {
        $this->url = $url;

        return $this;
    }

    public function isActive(bool | Closure $isActive = true): static
    {
        $this->isActive = $isActive;

        return $this;
    }

    /**
     * @param  string | array<string> | null | Closure  $color
     */
    public function badge(string | int | null | Closure $badge, string | array | null | Closure $color = null): static
    {
        $this->badge = $badge;
        $this->badgeColor = $color;

        return $this;
    }

    public function badgeColor(string | array | null | Closure $color): static
    {
        $this->badgeColor = $color;

        return $this;
    }

    public function sort(int $sort): static
    {
        $this->sort = $sort;

        return $this;
    }

    public function visible(bool | Closure $condition = true): static
    {
        $this->isVisible = $condition;

        return $this;
    }

    public function getLabel(): string
    {
        if ($this->labelOverride instanceof Closure) {
            return ($this->labelOverride)();
        }

        if ($this->labelOverride !== null) {
            return $this->labelOverride;
        }

        return $this->label;
    }

    public function getIcon(): string | BackedEnum | Htmlable | null
    {
        return $this->icon;
    }

    public function getActiveIcon(): string | BackedEnum | Htmlable | null
    {
        return $this->activeIcon;
    }

    public function getUrl(): ?string
    {
        if ($this->url instanceof Closure) {
            return ($this->url)();
        }

        return $this->url;
    }

    public function isActiveState(): bool
    {
        if ($this->isActive instanceof Closure) {
            return (bool) ($this->isActive)();
        }

        return $this->isActive;
    }

    public function getBadge(): string | int | null
    {
        if ($this->badge instanceof Closure) {
            return ($this->badge)();
        }
        return $this->badge;
    }

    /**
     * @return string | array<string> | null
     */
    public function getBadgeColor(): string | array | null
    {
        if ($this->badgeColor instanceof Closure) {
            return ($this->badgeColor)();
        }

        return $this->badgeColor;
    }

    public function getSort(): int
    {
        return $this->sort;
    }

    public function isVisible(): bool
    {
        if ($this->isVisible instanceof Closure) {
            return (bool) ($this->isVisible)();
        }

        return $this->isVisible;
    }
}
