<?php

namespace App\Enum;

enum Status: string
{
    case ALIVE = 'alive';
    case DEAD = 'dead';
    case UNKNOWN = 'unknown';

    public function label(): string
    {
        return match($this) {
            self::ALIVE => 'Alive',
            self::DEAD => 'Dead',
            self::UNKNOWN => 'Unknown',
        };
    }

    public static function safeFrom(string $value): ?Status
    {
        foreach (Status::cases() as $case) {
            if (strtolower($case->value) === strtolower($value)) {
                return $case;
            }
        }

        return null;
    }
}