<?php

namespace App\Repositories;

use App\Models\Product;
use App\Models\Customer;
use App\Models\Agent;
use App\Models\Order;
use App\Models\User;
use App\Repositories\Interfaces\ReportRepositoryInterface;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\DB;

class ReportRepository implements ReportRepositoryInterface
{
    public function getTopProducts(int $limit = 10)
    {
        return Product::topSelling($limit)->get();
    }

    public function getTopCustomers(int $limit = 10)
    {
        return DB::table('orders')
            ->select(
                'orders.customer_id',
                'orders.name as customer_name',
                DB::raw('COUNT(DISTINCT orders.id) as total_order')
            )
            ->whereIn('orders.status', [
                Order::STATUS_PAYMENT_SUCCESS,
                Order::STATUS_ORDER_PROCESS,
                Order::STATUS_ORDER_COMPLETED
            ])
            ->groupBy('orders.customer_id', 'orders.name')
            ->orderByDesc('total_order')
            ->limit($limit)
            ->get();
    }

    public function getTopAgents(int $limit = 10)
    {
        return DB::table('orders')
            ->select(
                'agent.id',
                'agent.partner_id',
                'agent.store_name',
                DB::raw('COUNT(DISTINCT orders.customer_id) as total_customer')
            )
            ->join('agent', 'orders.agent_id', '=', 'agent.id')
            ->whereNotNull('orders.agent_id')
            ->whereIn('orders.status', [
                Order::STATUS_PAYMENT_SUCCESS,
                Order::STATUS_ORDER_PROCESS,
                Order::STATUS_ORDER_COMPLETED
            ])
            ->groupBy('agent.id', 'agent.partner_id', 'agent.store_name')
            ->orderByDesc('total_customer')
            ->limit($limit)
            ->get();
    }

    public function getOrders(?string $search = null, ?string $startDate = null, ?string $endDate = null): LengthAwarePaginator
    {
        return DB::table('orders')
            ->select(
                'orders.*',
                'agent.partner_id',
                'agent.store_name'
            )
            ->leftJoin('agent', 'orders.agent_id', '=', 'agent.id')
            ->when($search, function($query) use ($search) {
                return $query->where(function($q) use ($search) {
                    $q->where('orders.name', 'like', "%{$search}%")
                      ->orWhere('agent.store_name', 'like', "%{$search}%")
                      ->orWhere('agent.partner_id', 'like', "%{$search}%");
                });
            })
            ->when($startDate, function($query) use ($startDate) {
                return $query->whereDate('orders.delivery_date', '>=', $startDate);
            })
            ->when($endDate, function($query) use ($endDate) {
                return $query->whereDate('orders.delivery_date', '<=', $endDate);
            })
            ->orderByDesc('orders.delivery_date')
            ->paginate(10);
    }

    public function getOrderDetail(int $orderId)
    {
        $order = DB::table('orders')
            ->select(
                'orders.*',
                'agent.partner_id',
                'agent.store_name'
            )
            ->leftJoin('agent', 'orders.agent_id', '=', 'agent.id')
            ->where('orders.id', $orderId)
            ->first();

        if ($order) {
            $order_details = DB::table('order_detail')
                ->select(
                    'order_detail.*',
                    'product.product_name'
                )
                ->join('product', 'order_detail.product_id', '=', 'product.id')
                ->where('order_detail.order_id', $orderId)
                ->get();

            return [
                'order' => $order,
                'order_details' => $order_details
            ];
        }

        return null;
    }
} 