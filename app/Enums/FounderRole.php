<?php

namespace App\Enums;

enum FounderRole: string
{
    case CEO = 'ceo';
    case CTO = 'cto';
    case CFO = 'cfo';

    public function label(): string
    {
        return match($this) {
            self::CEO => 'CEO',
            self::CTO => 'CTO',
            self::CFO => 'CFO',
        };
    }

    public function canEditLandingPage(): bool
    {
        return $this === self::CTO;
    }
}
