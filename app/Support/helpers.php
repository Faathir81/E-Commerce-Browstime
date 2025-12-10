<?php

if (! function_exists('formatCurrency')) {
    function formatCurrency(int|float|null $value): string
    {
        $amount = $value ?? 0;

        return number_format($amount, 0, ',', '.');
    }
}

if (! function_exists('formatRupiah')) {
    function formatRupiah(int|float|null $value, bool $withPrefix = true): string
    {
        $prefix = $withPrefix ? 'Rp ' : '';

        return $prefix . formatCurrency($value);
    }
}
