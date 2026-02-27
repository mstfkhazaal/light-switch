<?php

declare(strict_types=1);

namespace Awcodes\LightSwitch\Enums;

enum SwitcherStyle: string
{
    case Icon = 'icon';
    case Button = 'button';
    case Dropdown = 'dropdown';
    case Toggle = 'toggle';
}
