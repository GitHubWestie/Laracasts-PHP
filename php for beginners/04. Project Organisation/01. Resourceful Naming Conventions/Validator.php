<?php

class Validator {
    public static function string(string $string, int $min = 1, int $max = INF)
    {
        $string = trim($string);

        return strlen($string) >= $min && strlen($string) <= $max;
    }
}