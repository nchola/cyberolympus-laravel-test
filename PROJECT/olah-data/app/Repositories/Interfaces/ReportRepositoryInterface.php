<?php

namespace App\Repositories\Interfaces;

interface ReportRepositoryInterface
{
    public function getTopProducts(int $limit = 10);
    public function getTopCustomers(int $limit = 10);
    public function getTopAgents(int $limit = 10);
    public function getOrders(?string $search = null, ?string $startDate = null, ?string $endDate = null);
    public function getOrderDetail(int $orderId);
} 