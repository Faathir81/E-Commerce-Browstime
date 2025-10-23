<?php

namespace App\Policies;

use App\Models\User;
use App\Models\Order;

class OrderPolicy
{
    /**
     * Buyer boleh lihat order miliknya.
     * Admin boleh lihat semua.
     */
    public function view(User $user, Order $order): bool
    {
        if ($user->is_admin ?? false) return true;
        return $order->user_id === $user->id;
    }

    /**
     * Buyer hanya bisa konfirmasi order miliknya.
     */
    public function confirm(User $user, Order $order): bool
    {
        return $order->user_id === $user->id;
    }
}
