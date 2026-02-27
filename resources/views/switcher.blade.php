@php
    $alignment ??= 'top-right';
    $style ??= 'icon';
    $includeSystem ??= true;
    $systemIconMode ??= 'resolved';
    $show ??= true;
    $isFixed ??= true;
@endphp

@if ($show)
    <x-light-switch::switcher
            :alignment="$alignment"
            :style="$style"
            :include-system="$includeSystem"
            :system-icon-mode="$systemIconMode"
            :is-fixed="$isFixed"
    />
@endif
