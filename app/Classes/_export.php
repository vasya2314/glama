<?php

use App\Models\Report;

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

if (!function_exists('getClientAmountByReport'))
{
    function getClientAmountByReport(string $login, Report $report): float|int|null
    {
        $amount = null;
        $data = (array)@json_decode($report->data);

        if(!empty($data))
        {
            $arrItems = $data[$login] ?? null;

            if(!empty($arrItems) && is_array($arrItems))
            {
                foreach($arrItems as $item)
                {
                    if(isset($item->Cost))
                    {
                        $cost = (int)$item->Cost / 1000000;
                        $amount += $cost;
                    }
                }
            }
        }

        return $amount;

    }
}

if (!function_exists('priceFormat'))
{
    function priceFormat(float|int $amount): string
    {
        return number_format($amount, 2, ',', ' ');
    }
}
