@props([
    'includeSystem' => true,
    'systemIconMode' => 'resolved', // resolved|system
])

@php
    $defaultTheme = filament()->getDefaultThemeMode()->value;

    $icons = [
        'light' => trim(\Illuminate\Support\Facades\Blade::render('<x-filament::icon icon="heroicon-m-sun" class="mt-1 h-5 w-5" />')),
        'dark' => trim(\Illuminate\Support\Facades\Blade::render('<x-filament::icon icon="heroicon-m-moon" class="mt-1 h-5 w-5" />')),
        'system' => trim(\Illuminate\Support\Facades\Blade::render('<x-filament::icon icon="heroicon-m-computer-desktop" class="mt-1 h-5 w-5" />')),
    ];
@endphp

<span
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
            this.renderIcon();

            const onChange = () => {
                this.updateResolved();
                if (this.theme === 'system') this.commit();
                this.renderIcon();
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
            this.renderIcon();
        },

        renderIcon() {
            this.$refs.icon.innerHTML = this.icons[this.displayKey()] ?? '';
        },
    }"
    x-show="ready"
>
    <button
        type="button"
        x-on:click="cycle()"
        class="appearance-none bg-transparent border-0 p-0 m-0 cursor-pointer leading-none"
        aria-label="Theme"
    >
        <span x-ref="icon"></span>
    </button>
</span>
