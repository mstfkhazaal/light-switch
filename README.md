![light switch screenshots](https://res.cloudinary.com/aw-codes/image/upload/w_1200,f_auto,q_auto/plugins/light-switch/awcodes-light-switch.jpg)
[![Latest Version on Packagist](https://img.shields.io/packagist/v/awcodes/light-switch.svg?style=flat-square)](https://packagist.org/packages/awcodes/light-switch)
[![Total Downloads](https://img.shields.io/packagist/dt/awcodes/light-switch.svg?style=flat-square)](https://packagist.org/packages/awcodes/light-switch)

# Light Switch (Extended)

A Filament Panels plugin to add **theme switching (light / dark / system)** to auth pages.

This extended version adds:

- ✅ **Multiple styles**: `icon`, `button`, `dropdown`, `toggle` (3 buttons)
- ✅ **Inline icon component** (embed next to other UI like Language Switch)
- ✅ **Livewire component** usage (`<livewire:light-switcher ... />`)
- ✅ **Optional fixed behavior**: `isFixed(true|false)` (pinned vs scroll)
- ✅ Control System mode:
  - `includeSystem(true|false)`
  - `systemIconMode('resolved'|'system')`

> Filament stores the theme mode in `localStorage` under key `theme` and reacts to a `theme-changed` event. This package follows the same pattern so the selected mode persists. (See Filament discussions/docs.)

---

## Compatibility

| Package Version | Filament Version |
|-----------------|------------------|
| 1.x             | 3.x              |
| 2.x             | 4.x              |
| 3.x             | 5.x              |

---

## Installation

Install via composer:

```bash
composer require awcodes/light-switch
````

> [!IMPORTANT]
> If you have not set up a custom theme and are using Filament Panels follow the instructions in the Filament Docs first.

Add the plugin's views to your `theme.css` (Tailwind source scan):

```css
@source '../../../../vendor/awcodes/light-switch/resources/views/**/*.blade.php';
```

Then rebuild your theme:

```bash
npm run build
```

### Recommended (optional) Alpine flash prevention

If you use `x-cloak`, ensure this exists in your CSS once:

```css
[x-cloak] { display: none !important; }
```

---

## Usage (Plugin)

```php
use Awcodes\LightSwitch\LightSwitchPlugin;

public function panel(Panel $panel): Panel
{
    return $panel->plugins([
        LightSwitchPlugin::make(),
    ]);
}
```

---

## Configuration

### 1) Position (auth overlay)

Default is `TopRight`.

```php
use Awcodes\LightSwitch\Enums\Alignment;
use Awcodes\LightSwitch\LightSwitchPlugin;

LightSwitchPlugin::make()
    ->position(Alignment::BottomCenter);
```

---

### 2) Styles

Supported styles:

* `SwitcherStyle::Icon`
* `SwitcherStyle::Button`
* `SwitcherStyle::Dropdown`
* `SwitcherStyle::Toggle` (Light/Dark/System buttons)

```php
use Awcodes\LightSwitch\Enums\SwitcherStyle;

LightSwitchPlugin::make()
    ->style(SwitcherStyle::Toggle);
```

---

### 3) Include / Exclude system mode

```php
LightSwitchPlugin::make()
    ->includeSystem(false);
```

---

### 4) System icon mode (when theme = system)

* `resolved`: show sun/moon depending on OS preference
* `system`: show the computer icon

```php
LightSwitchPlugin::make()
    ->systemIconMode('resolved'); // or 'system'
```

---

### 5) Fixed vs Scroll

Controls whether the overlay stays pinned while scrolling.

* `isFixed(true)`  → `position: fixed`  (default)
* `isFixed(false)` → `position: absolute` (scrolls with page)

```php
LightSwitchPlugin::make()
    ->isFixed(true);   // pinned (default)

LightSwitchPlugin::make()
    ->isFixed(false);  // scrolls with page
```

---

### 6) Render hook (Advanced)

By default the plugin renders on `PanelsRenderHook::BODY_END`. You may override:

```php
use Filament\View\PanelsRenderHook;

LightSwitchPlugin::make()
    ->renderHook(PanelsRenderHook::BODY_START);
```

---

### 7) Disabling on specific pages

You can enable on specific routes by passing partial route names. Matching uses `Str::contains()`.

```php
LightSwitchPlugin::make()
    ->enabledOn([
        'auth.email',
        'auth.login',
        'auth.password',
        'auth.profile',
        'auth.register',
    ]);
```

---

## Usage without registering the plugin

### A) Inline icon Blade component

Use this to place the theme icon next to any UI (example: next to Language Switch via content injection).

```blade
<x-light-switch::inline-icon
    :include-system="true"
    system-icon-mode="resolved"
/>
```

### B) Full switcher Blade component

```blade
<x-light-switch::switcher
    alignment="top-right"
    style="dropdown"
    :include-system="false"
    system-icon-mode="system"
    :is-fixed="true"
/>
```

---

## Livewire usage

If Livewire is installed, you can use:

```blade
<livewire:light-switcher
    alignment="top-right"
    style="toggle"
    :include-system="true"
    system-icon-mode="resolved"
    :inline="false"
    :is-fixed="true"
/>
```

---

## Changelog

Please see [CHANGELOG](CHANGELOG.md) for more information.

## Contributing

Please see [CONTRIBUTING](.github/CONTRIBUTING.md) for details.

## Security Vulnerabilities

Please review the security policy to report vulnerabilities.

## Credits

* [Adam Weston](https://github.com/awcodes)
* [All Contributors](../../contributors)

## License

MIT. See [License File](LICENSE.md).

