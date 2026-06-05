@props([
    'alignment' => 'top-right',
    'style' => 'icon', // icon|button|dropdown|toggle
    'includeSystem' => true,
    'systemIconMode' => 'resolved', // resolved|system
    'inline' => false,
    'isFixed' => true,
])

@php
    $defaultTheme = filament()->getDefaultThemeMode()->value;

    $icons = [
        'light' => trim(\Illuminate\Support\Facades\Blade::render('<x-filament::icon icon="heroicon-m-sun" class="mt-1 h-5 w-5" />')),
        'dark' => trim(\Illuminate\Support\Facades\Blade::render('<x-filament::icon icon="heroicon-m-moon" class="mt-1 h-5 w-5" />')),
        'system' => trim(\Illuminate\Support\Facades\Blade::render('<x-filament::icon icon="heroicon-m-computer-desktop" class="mt-1 h-5 w-5" />')),
    ];

    $alignment = (string) $alignment;
    
    $positionClass = $inline
        ? 'relative'
        : ((bool) $isFixed ? 'fixed' : 'absolute'); // fixed vs absolute :contentReference[oaicite:4]{index=4}

    $containerClass = $inline
        ? 'inline-flex items-center'
        : ($positionClass . ' w-full flex p-4 z-30 pointer-events-none');
@endphp

@if (filament()->hasDarkMode() && (! filament()->hasDarkModeForced()))
    <div @class([
        $containerClass,
        'top-0' => ! $inline && str_contains($alignment, 'top'),
        'bottom-0' => ! $inline && str_contains($alignment, 'bottom'),
        'justify-start' => ! $inline && str_contains($alignment, 'left'),
        'justify-end' => ! $inline && str_contains($alignment, 'right'),
        'justify-center' => ! $inline && str_contains($alignment, 'center'),
    ])>
        {{-- only this box is clickable --}}
        <div class="pointer-events-auto">
            <div
                    wire:ignore
                    x-cloak
                    x-data="{
                    ready: false,
                    theme: null,
                    resolved: 'light',
                    mq: null,

                    includeSystem: @js((bool) $includeSystem),
                    systemIconMode: @js((string) $systemIconMode),
                    icons: @js($icons),

                    init() {
                        this.mq = window.matchMedia ? window.matchMedia('(prefers-color-scheme: dark)') : null;

                        const stored = localStorage.getItem('theme');
                        const fallback = @js($defaultTheme);

                        let initial = stored || fallback;
                        if (!['light','dark','system'].includes(initial)) initial = 'light';
                        if (!this.includeSystem && initial === 'system') initial = (this.mq && this.mq.matches) ? 'dark' : 'light';

                        this.theme = initial;
                        this.updateResolved();
                        this.commit();

                        if (this.$refs.icon) this.renderIcon();

                        const onChange = () => {
                            this.updateResolved();
                            if (this.theme === 'system') this.commit();
                            if (this.$refs.icon) this.renderIcon();
                        };

                        if (this.mq?.addEventListener) this.mq.addEventListener('change', onChange);
                        else if (this.mq?.addListener) this.mq.addListener(onChange);

                        this.ready = true;
                    },

                    updateResolved() {
                        this.resolved = this.theme === 'system'
                            ? ((this.mq && this.mq.matches) ? 'dark' : 'light')
                            : this.theme;
                    },

                    displayKey() {
                        if (this.theme === 'system' && this.systemIconMode === 'resolved') return this.resolved;
                        return this.theme;
                    },

                    commit() {
                        localStorage.setItem('theme', this.theme);
                        $dispatch('theme-changed', this.theme);
                    },

                    cycle() {
                        if (this.includeSystem) {
                            this.theme = this.theme === 'light' ? 'dark' : (this.theme === 'dark' ? 'system' : 'light');
                        } else {
                            this.theme = this.theme === 'light' ? 'dark' : 'light';
                        }

                        this.updateResolved();
                        this.commit();
                        if (this.$refs.icon) this.renderIcon();
                    },

                    setTheme(v) {
                        if (!['light','dark','system'].includes(v)) return;
                        if (!this.includeSystem && v === 'system') return;

                        this.theme = v;
                        this.updateResolved();
                        this.commit();
                        if (this.$refs.icon) this.renderIcon();
                    },

                    renderIcon() {
                        this.$refs.icon.innerHTML = this.icons[this.displayKey()] ?? '';
                    },
                }"
                    x-show="ready"
                    class="flex items-center gap-1 p-1"
            >
                @if ($style === 'dropdown')
                    <select
                            class="rounded-lg border border-gray-200 bg-white px-3 py-2 text-sm dark:border-white/10 dark:bg-gray-950"
                            x-on:change="setTheme($event.target.value)"
                            :value="theme"
                            aria-label="Theme"
                    >
                        <option value="light">Light</option>
                        <option value="dark">Dark</option>
                        @if ($includeSystem)
                            <option value="system">System</option>
                        @endif
                    </select>

                @elseif ($style === 'toggle')
                    <div class="flex items-center gap-1">
                        <button type="button" class="p-2 rounded-lg" x-on:click="setTheme('light')">{!! $icons['light'] !!}</button>
                        <button type="button" class="p-2 rounded-lg" x-on:click="setTheme('dark')">{!! $icons['dark'] !!}</button>
                        @if ($includeSystem)
                            <button type="button" class="p-2 rounded-lg" x-on:click="setTheme('system')">{!! $icons['system'] !!}</button>
                        @endif
                    </div>

                @elseif ($style === 'button')
                    <button
                            type="button"
                            class="px-3 py-2 rounded-lg border border-gray-200 bg-white text-sm dark:border-white/10 dark:bg-white/5"
                            x-on:click="cycle()"
                    >
                        <span class="inline-flex items-center gap-2">
                            <span x-ref="icon"></span>
                            <span x-text="theme"></span>
                        </span>
                    </button>

                @else
                    {{-- icon --}}
                    <button
                            type="button"
                            class="appearance-none bg-transparent border-0 p-0 m-1 rounded-none shadow-none ring-0 outline-none focus:outline-none focus:ring-0"
                            x-on:click="cycle()"
                            aria-label="Theme"
                    >
                        <span x-ref="icon" class="leading-none"></span>
                    </button>
                @endif
            </div>
        </div>
    </div>
@endif
