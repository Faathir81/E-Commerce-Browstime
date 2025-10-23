<?php

namespace App\Support;

use Illuminate\Contracts\Pagination\LengthAwarePaginator;

class PaginationHelper
{
    public static function meta(LengthAwarePaginator $paginator): array
    {
        return [
            'total'        => $paginator->total(),
            'per_page'     => $paginator->perPage(),
            'current_page' => $paginator->currentPage(),
            'last_page'    => $paginator->lastPage(),
        ];
    }

    public static function format(LengthAwarePaginator $paginator, array|string|null $extraMessage = null): array
    {
        return [
            'items' => $paginator->items(),
            'meta'  => self::meta($paginator),
            'message' => $extraMessage ?? 'OK',
        ];
    }
}
