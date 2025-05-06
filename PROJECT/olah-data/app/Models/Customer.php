<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;

class Customer extends Model
{
    use HasFactory;

    protected $table = 'customer';
    protected $guarded = ['id'];

    public function user()
    {
        return $this->belongsTo(User::class, 'referral_id');
    }

    public function orders()
    {
        return $this->hasMany(Order::class, 'customer_id');
    }

    public function scopeTopBuyers(Builder $query, int $limit = 10): Builder
    {
        return $query->select('customer.*')
            ->selectRaw('COUNT(DISTINCT orders.id) as total_order')
            ->join('orders', 'customer.id', '=', 'orders.customer_id')
            ->whereIn('orders.status', [2, 3, 4]) // Only count successful orders
            ->groupBy('customer.id')
            ->orderByDesc('total_order')
            ->limit($limit);
    }

    public function getSuccessfulOrdersCountAttribute(): int
    {
        return $this->orders()
            ->whereIn('status', [2, 3, 4])
            ->count();
    }

    public function getNameAttribute(): string
    {
        return $this->attributes['name'] ?? '';
    }
}
