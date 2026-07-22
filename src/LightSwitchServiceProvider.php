<?php

declare(strict_types=1);

namespace Awcodes\LightSwitch;

use Illuminate\Support\Facades\Blade;
use Livewire\Livewire;
use Spatie\LaravelPackageTools\Package;
use Spatie\LaravelPackageTools\PackageServiceProvider;

class LightSwitchServiceProvider extends PackageServiceProvider
{
    public static string $name = 'light-switch';

    public static string $viewNamespace = 'light-switch';

    public function configurePackage(Package $package): void
    {
        $package
            ->name(static::$name)
            ->hasViews();
    }

    public function packageBooted(): void
    {
        // Blade anonymous components:
        // <x-light-switch::switcher />, <x-light-switch::inline-icon />
        Blade::anonymousComponentPath(
            __DIR__ . '/../resources/views/components',
            'light-switch'
        );

        if (class_exists(Livewire::class)) {
            Livewire::component('light-switcher', \Awcodes\LightSwitch\Livewire\LightSwitcher::class);
        }
    }
}
