<?php

declare(strict_types=1);

use Awcodes\LightSwitch\Enums\Alignment;
use Awcodes\LightSwitch\Enums\SwitcherStyle;
use Awcodes\LightSwitch\LightSwitchPlugin;
use Filament\Facades\Filament;

beforeEach(function () {
    $this->panel = Filament::getCurrentOrDefaultPanel();
});

it('displays the light switch', function () {
    $this->panel->plugins([
        LightSwitchPlugin::make(),
    ]);

    $this->get('/admin/login')
        ->assertOk()
        // stable marker from Alpine code
        ->assertSee("localStorage.setItem('theme'", false);
});

it('hides the light switch when not enabled on the route', function () {
    $this->panel->plugins([
        LightSwitchPlugin::make()
            ->enabledOn(['auth.email']),
    ]);

    $this->get('/admin/login')
        ->assertOk()
        ->assertDontSee("localStorage.setItem('theme'", false);
});

it('displays in correct position', function () {
    $this->panel->plugins([
        LightSwitchPlugin::make()
            ->position(Alignment::BottomLeft),
    ]);

    $this->get('/admin/login')
        ->assertOk()
        ->assertSee('bottom-0', false)
        ->assertSee('justify-start', false)
        ->assertDontSee('top-0', false)
        ->assertDontSee('justify-end', false);
});

it('uses fixed positioning by default', function () {
    $this->panel->plugins([
        LightSwitchPlugin::make(),
    ]);

    $this->get('/admin/login')
        ->assertOk()
        ->assertSee('fixed w-full flex', false)
        ->assertDontSee('absolute w-full flex', false);
});

it('can scroll with the page when isFixed(false)', function () {
    $this->panel->plugins([
        LightSwitchPlugin::make()
            ->isFixed(false),
    ]);

    $this->get('/admin/login')
        ->assertOk()
        ->assertSee('absolute w-full flex', false)
        ->assertDontSee('fixed w-full flex', false);
});

it('renders dropdown style and can hide system option', function () {
    $this->panel->plugins([
        LightSwitchPlugin::make()
            ->style(SwitcherStyle::Dropdown)
            ->includeSystem(false),
    ]);

    $this->get('/admin/login')
        ->assertOk()
        ->assertSee('<select', false)
        ->assertSee('option value="light"', false)
        ->assertSee('option value="dark"', false)
        ->assertDontSee('option value="system"', false);
});

it('renders toggle style and includes system button when enabled', function () {
    $this->panel->plugins([
        LightSwitchPlugin::make()
            ->style(SwitcherStyle::Toggle)
            ->includeSystem(true),
    ]);

    $this->get('/admin/login')
        ->assertOk()
        // toggle uses JS calls like setTheme('system')
        ->assertSee("setTheme('light')", false)
        ->assertSee("setTheme('dark')", false)
        ->assertSee("setTheme('system')", false);
});