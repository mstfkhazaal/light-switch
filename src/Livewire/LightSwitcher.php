<?php

declare(strict_types=1);

namespace Awcodes\LightSwitch\Livewire;

use Illuminate\Contracts\View\View;
use Livewire\Component;

class LightSwitcher extends Component
{
    public string $alignment = 'top-right';

    public string $style = 'icon';

    public bool $includeSystem = true;

    public string $systemIconMode = 'resolved';

    public bool $inline = false;
    
    public bool $isFixed = true;

    public function mount(
        ?string $alignment = null,
        ?string $style = null,
        ?bool $includeSystem = null,
        ?string $systemIconMode = null,
        bool $inline = false,
        ?bool $isFixed = null,
    ): void {
        $this->alignment = $alignment ?? 'top-right';
        $this->style = $style ?? 'icon';
        $this->includeSystem = $includeSystem ?? true;

        $mode = strtolower((string) ($systemIconMode ?? 'resolved'));
        $this->systemIconMode = in_array($mode, ['resolved', 'system'], true) ? $mode : 'resolved';

        $this->inline = $inline;
        $this->isFixed = $isFixed ?? true;
    }

    public function render(): View
    {
        return view('light-switch::livewire.switcher');
    }
}
