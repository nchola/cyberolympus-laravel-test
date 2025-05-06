<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;

class Order extends Model
{
    use HasFactory;

    protected $table = 'orders';
    protected $guarded = ['id'];

    /**
     * The attributes that should be cast.
     *
     * @var array
     */
    protected $casts = [
        'delivery_date' => 'datetime',
        'payment_date' => 'datetime',
        'order_time' => 'datetime',
        'delivery_time' => 'datetime',
        'payment_total' => 'float',
        'payment_discount' => 'float',
        'payment_final' => 'float',
        'order_weight' => 'float',
        'order_distance' => 'float',
        'delivery_fee' => 'float',
    ];

    // Status Constants
    public const STATUS_NEW_ORDER = 1;
    public const STATUS_PAYMENT_SUCCESS = 2;
    public const STATUS_ORDER_PROCESS = 3;
    public const STATUS_ORDER_COMPLETED = 4;
    public const STATUS_ORDER_CANCEL = 5;
    public const STATUS_PAYMENT_PENDING = 6;
    public const STATUS_PAYMENT_FAILED = 7;

    public static array $statusLabels = [
        self::STATUS_NEW_ORDER => 'New Order',
        self::STATUS_PAYMENT_SUCCESS => 'Payment Success',
        self::STATUS_ORDER_PROCESS => 'Order Process',
        self::STATUS_ORDER_COMPLETED => 'Order Completed',
        self::STATUS_ORDER_CANCEL => 'Order Cancel',
        self::STATUS_PAYMENT_PENDING => 'Payment Pending',
        self::STATUS_PAYMENT_FAILED => 'Payment Failed',
    ];

    // Relationships
    public function customer()
    {
        return $this->belongsTo(Customer::class, 'customer_id');
    }

    public function agent()
    {
        return $this->belongsTo(Agent::class, 'agent_id');
    }

    public function orderDetails()
    {
        return $this->hasMany(OrderDetail::class, 'order_id');
    }

    // Scopes
    public function scopeSuccessful(Builder $query): Builder
    {
        return $query->whereIn('status', [
            self::STATUS_PAYMENT_SUCCESS,
            self::STATUS_ORDER_PROCESS,
            self::STATUS_ORDER_COMPLETED
        ]);
    }

    public function scopeFilterByDateRange(Builder $query, ?string $startDate, ?string $endDate): Builder
    {
        if ($startDate) {
            $query->whereDate('delivery_date', '>=', Carbon::parse($startDate));
        }

        if ($endDate) {
            $query->whereDate('delivery_date', '<=', Carbon::parse($endDate));
        }

        return $query;
    }

    public function scopeSearch(Builder $query, ?string $search): Builder
    {
        if ($search) {
            $query->where(function($q) use ($search) {
                $q->whereHas('customer', function($q) use ($search) {
                    $q->where('name', 'like', "%{$search}%");
                })->orWhereHas('agent', function($q) use ($search) {
                    $q->where('name', 'like', "%{$search}%");
                });
            });
        }

        return $query;
    }

    // Accessors & Helpers
    public function getStatusLabelAttribute(): string
    {
        return self::$statusLabels[$this->status] ?? 'Unknown';
    }

    public function getCustomerFullAddressAttribute(): string
    {
        return implode(', ', array_filter([
            $this->address,
            $this->kelurahan,
            $this->kecamatan,
            $this->kota,
            $this->provinsi,
            $this->kode_pos
        ]));
    }

    public function getTotalItemsAttribute(): int
    {
        return $this->orderDetails->sum('qty');
    }

    public function getFormattedDeliveryDateAttribute(): string
    {
        return $this->delivery_date ? Carbon::parse($this->delivery_date)->format('d M Y') : '-';
    }

    public function getFormattedPaymentDateAttribute(): string
    {
        return $this->payment_date ? Carbon::parse($this->payment_date)->format('d M Y H:i') : '-';
    }
}
