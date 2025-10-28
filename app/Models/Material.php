<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Material extends Model
{
    protected $fillable = ['name', 'unit', 'min_qty'];

    public function stocks(): HasMany
    {
        return $this->hasMany(MaterialStock::class);
    }

    public function getCurrentStockAttribute(): float|int
    {
        return (float) ($this->stocks()->sum('qty') ?? 0);
    }
}