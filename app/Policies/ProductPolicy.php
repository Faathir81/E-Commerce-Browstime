<?php

namespace App\Policies;

use App\Models\User;
use App\Models\Product;

class ProductPolicy
{
    /**
     * Buyer atau guest boleh lihat katalog.
     */
    public function viewAny(?User $user): bool
    {
        return true;
    }

    /**
     * Semua boleh lihat detail produk.
     */
    public function view(?User $user, Product $product): bool
    {
        return true;
    }

    /**
     * Hanya admin boleh CRUD.
     * (Kalau nanti kamu punya role di model User, bisa ubah jadi $user->is_admin)
     */
    public function create(User $user): bool
    {
        return $user->is_admin ?? false;
    }

    public function update(User $user, Product $product): bool
    {
        return $user->is_admin ?? false;
    }

    public function delete(User $user, Product $product): bool
    {
        return $user->is_admin ?? false;
    }
}
