<?php

namespace App\Enum;

enum Status: string
{
    case ALIVE = 'alive';
    case DEAD = 'dead';
    case UNKNOWN = 'unknown';

    /**
     * Returns a human-readable label for the status value.
     *
     * @return string
     */
    public function label(): string
    {
        return match($this) {
            self::ALIVE => 'Alive',
            self::DEAD => 'Dead',
            self::UNKNOWN => 'Unknown'
        };
    }

    /**
     * Safely converts a string to a Status enum value.
     * Comparison is case-insensitive. Returns null if no match is found.
     *
     * @param string $value
     * @return Status|null
     */
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
