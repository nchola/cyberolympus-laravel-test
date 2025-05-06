<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;

class Product extends Model
{
    use HasFactory;

    protected $table = 'product';
    protected $guarded = ['id'];

    public function category()
    {
        return $this->belongsTo(ProductCategory::class, 'category');
    }

    public function orderDetails()
    {
        return $this->hasMany(OrderDetail::class, 'product_id');
    }

    public function scopeTopSelling(Builder $query, int $limit = 10): Builder
    {
        return $query->select('product.*')
            ->selectRaw('SUM(order_detail.qty) as total_terjual')
            ->join('order_detail', 'product.id', '=', 'order_detail.product_id')
            ->join('orders', 'orders.id', '=', 'order_detail.order_id')
            ->whereIn('orders.status', [2, 3, 4]) // Only count successful orders
            ->groupBy('product.id', 'product.product_name') // Add product_name to GROUP BY
            ->orderByDesc('total_terjual')
            ->limit($limit);
    }

    public function getTotalSoldAttribute(): int
    {
        return $this->orderDetails()
            ->whereHas('order', function($q) {
                $q->whereIn('status', [2, 3, 4]); // Only count successful orders
            })
            ->sum('qty');
    }
}
