<?php

declare(strict_types=1);

namespace Awcodes\LightSwitch;

use Awcodes\LightSwitch\Enums\Alignment;
use Awcodes\LightSwitch\Enums\SwitcherStyle;
use Closure;
use Filament\Contracts\Plugin;
use Filament\Panel;
use Filament\View\PanelsRenderHook;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Str;

class LightSwitchPlugin implements Plugin
{
    protected ?Alignment $position = null;

    protected ?SwitcherStyle $style = null;

    protected bool $includeSystem = true;

    protected string $systemIconMode = 'resolved'; // resolved|system

    protected string $renderHook = PanelsRenderHook::BODY_END;

    protected ?array $enabledOn = null;

    protected bool | Closure $isFixed = true;

    public static function make(): static
    {
        return app(static::class);
    }

    public function getId(): string
    {
        return 'awcodes/light-switch';
    }

    public function register(Panel $panel): void
    {
        $panel->renderHook(
            name: $this->renderHook,
            hook: fn (): View => view('light-switch::switcher', [
                'alignment' => $this->getPosition()->value,
                'style' => $this->getStyle()->value,
                'includeSystem' => $this->includeSystem,
                'systemIconMode' => $this->systemIconMode,
                'show' => $this->shouldShowSwitcher(),

                // ✅ pass to view
                'isFixed' => $this->isFixedEnabled(),
            ]),
        );
    }

    public function boot(Panel $panel): void {}

    public function position(Alignment $position): static
    {
        $this->position = $position;

        return $this;
    }

    public function style(SwitcherStyle $style): static
    {
        $this->style = $style;

        return $this;
    }

    public function includeSystem(bool $value = true): static
    {
        $this->includeSystem = $value;

        return $this;
    }

    public function systemIconMode(string $mode): static
    {
        $mode = strtolower($mode);
        $this->systemIconMode = in_array($mode, ['resolved', 'system'], true) ? $mode : 'resolved';

        return $this;
    }

    public function renderHook(string $hook): static
    {
        $this->renderHook = $hook;

        return $this;
    }

    public function enabledOn(array $routes): static
    {
        $this->enabledOn = $routes;

        return $this;
    }
    
    public function isFixed(bool | Closure $condition = true): static
    {
        $this->isFixed = $condition;

        return $this;
    }
    
    public function isFixedEnabled(): bool
    {
        return $this->isFixed instanceof Closure
            ? (bool) ($this->isFixed)()
            : (bool) $this->isFixed;
    }

    public function getPosition(): Alignment
    {
        return $this->position ?? Alignment::TopRight;
    }

    public function getStyle(): SwitcherStyle
    {
        return $this->style ?? SwitcherStyle::Icon;
    }

    public function shouldShowSwitcher(): bool
    {
        $routeName = request()->route()?->getName();

        if (! is_string($routeName) || $routeName === '') {
            return false;
        }

        return Str::of($routeName)->contains($this->enabledOn ?? [
            'auth.login',
            'auth.password',
            'auth.register',
        ]);
    }
}
