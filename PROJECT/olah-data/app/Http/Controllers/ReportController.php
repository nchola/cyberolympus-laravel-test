<?php

namespace App\Http\Controllers;

use App\Repositories\Interfaces\ReportRepositoryInterface;
use Illuminate\Http\Request;

class ReportController extends Controller
{
    private ReportRepositoryInterface $reportRepository;

    public function __construct(ReportRepositoryInterface $reportRepository)
    {
        $this->reportRepository = $reportRepository;
    }

    // Top 10 produk paling banyak terjual
    public function topProducts()
    {
        $products = $this->reportRepository->getTopProducts();
        return view('report.top_products', compact('products'));
    }

    // Top 10 customer paling banyak pembelian
    public function topCustomers()
    {
        $customers = $this->reportRepository->getTopCustomers();
        return view('report.top_customers', compact('customers'));
    }

    // Top 10 agent paling banyak mendapatkan customer
    public function topAgents()
    {
        $agents = $this->reportRepository->getTopAgents();
        return view('report.top_agents', compact('agents'));
    }

    // List order & filter
    public function orders(Request $request)
    {
        $search = $request->get('search');
        $startDate = $request->get('start_date');
        $endDate = $request->get('end_date');

        $orders = $this->reportRepository->getOrders($search, $startDate, $endDate);
        
        return view('report.orders', compact('orders'));
    }

    // Detail order
    public function orderDetail($id)
    {
        $result = $this->reportRepository->getOrderDetail($id);
        
        if (!$result) {
            return redirect()->route('report.orders')
                ->with('error', 'Order not found');
        }

        return view('report.order_detail', [
            'order' => $result['order'],
            'order_details' => $result['order_details']
        ]);
    }
} 