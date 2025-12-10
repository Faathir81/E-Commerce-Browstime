<?php

if (! function_exists('formatCurrency')) {
    function formatCurrency(int|float|null $value): string
    {
        $amount = $value ?? 0;

        return number_format($amount, 0, ',', '.');
    }
}
