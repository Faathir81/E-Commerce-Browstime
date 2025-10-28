<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Order extends Model
{
    protected $fillable = [
        'user_id','code','status','buyer_name','buyer_phone','ship_address',
        'ship_city','ship_postal','shipping_cost','shipping_courier','shipping_service',
        'shipping_etd','weight_gram','origin_city_id','destination_city_id',
        'subtotal','grand_total','estimated_ready_at','estimated_delivery_at',
    ];

    public function items(): HasMany
    {
        return $this->hasMany(OrderItem::class);
    }

    public function payments(): HasMany
    {
        return $this->hasMany(Payment::class);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function materialStocks(): HasMany
    {
        return $this->hasMany(MaterialStock::class);
    }
}
