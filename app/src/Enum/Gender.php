<?php

namespace App\Enum;

enum Gender: string
{
    case FEMALE = 'female';
    case MALE = 'male';
    case GENDERLESS = 'genderless';
    case UNKNOWN = 'unknown';

    /**
     * Returns a human-readable label for the gender value.
     *
     * @return string
     */
    public function label(): string
    {
        return match($this) {
            self::FEMALE => 'Female',
            self::MALE => 'Male',
            self::GENDERLESS => 'Genderless',
            self::UNKNOWN => 'Unknown',
        };
    }

    /**
     * Safely converts a string to a Gender enum value.
     * Comparison is case-insensitive. Returns null if no match is found.
     *
     * @param string $value
     * @return Gender|null
     */
    public static function safeFrom(string $value): ?Gender
    {
        foreach (Gender::cases() as $case) {
            if (strtolower($case->value) === strtolower($value)) {
                return $case;
            }
        }
        return null;
    }
}
