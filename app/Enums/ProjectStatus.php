<?php

namespace App\Enums;

enum ProjectStatus: string
{
    case PLANNING = 'planning';
    case ANALYSIS = 'analysis';
    case DESIGN = 'design';
    case IMPLEMENTATION = 'implementation';
    case TESTING = 'testing';
    case DEPLOYMENT = 'deployment';

    public function label(): string
    {
        return match($this) {
            self::PLANNING => 'Planning',
            self::ANALYSIS => 'Analysis',
            self::DESIGN => 'Design',
            self::IMPLEMENTATION => 'Implementation',
            self::TESTING => 'Testing',
            self::DEPLOYMENT => 'Deployment',
        };
    }

    public function order(): int
    {
        return match($this) {
            self::PLANNING => 1,
            self::ANALYSIS => 2,
            self::DESIGN => 3,
            self::IMPLEMENTATION => 4,
            self::TESTING => 5,
            self::DEPLOYMENT => 6,
        };
    }

    public function percentage(): int
    {
        return match($this) {
            self::PLANNING => 10,
            self::ANALYSIS => 25,
            self::DESIGN => 40,
            self::IMPLEMENTATION => 65,
            self::TESTING => 85,
            self::DEPLOYMENT => 100,
        };
    }
}
