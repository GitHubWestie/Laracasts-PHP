<?php
namespace Core;

class Validator {
    public static function email(string $value): bool
    {
        return filter_var($value, FILTER_VALIDATE_EMAIL);
    }

    public static function string(string $string, int $min = 1, $max = INF)
    {
        $string = trim($string);

        return strlen($string) >= $min && strlen($string) <= $max;
    }
}