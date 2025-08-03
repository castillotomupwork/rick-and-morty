<?php

namespace App\Enum;

enum Gender: string
{
    case FEMALE = 'female';
    case MALE = 'male';
    case GENDERLESS = 'genderless';
    case UNKNOWN = 'unknown';

    public function label(): string
    {
        return match($this) {
            self::FEMALE => 'Female',
            self::MALE => 'Male',
            self::GENDERLESS => 'Genderless',
            self::UNKNOWN => 'Unknown',
        };
    }

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