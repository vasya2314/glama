<?php

if (!function_exists('kopToRub'))
{
    function kopToRub(int $amount): float
    {
        return (float)($amount / 100);
    }
}

if (!function_exists('rubToKop'))
{
    function rubToKop(float $amount): int
    {
        return (int)($amount * 100);
    }
}
