<?php

if (!function_exists('kopToRub'))
{
    function kopToRub(int $amount): float|int
    {
        return (float)($amount / 100);
    }
}
