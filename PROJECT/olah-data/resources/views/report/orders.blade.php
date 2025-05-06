@extends('layouts.app')

@section('content')
<div class="card">
    <div class="card-header">
        <h5 class="card-title mb-0">
            <i class="fas fa-shopping-cart me-2"></i>Order List
        </h5>
    </div>
    <div class="card-body">
        <form action="{{ route('report.orders') }}" method="get" class="mb-4">
            <div class="row g-3">
                <div class="col-md-4">
                    <div class="input-group">
                        <span class="input-group-text">
                            <i class="fas fa-search"></i>
                        </span>
                        <input type="text" name="search" class="form-control" placeholder="Search customer or agent..." value="{{ request('search') }}">
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="input-group">
                        <span class="input-group-text">
                            <i class="fas fa-calendar"></i>
                        </span>
                        <input type="date" name="start_date" class="form-control" placeholder="Start Date" value="{{ request('start_date') }}">
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="input-group">
                        <span class="input-group-text">
                            <i class="fas fa-calendar"></i>
                        </span>
                        <input type="date" name="end_date" class="form-control" placeholder="End Date" value="{{ request('end_date') }}">
                    </div>
                </div>
                <div class="col-md-2">
                    <button type="submit" class="btn btn-primary w-100">
                        <i class="fas fa-filter me-1"></i>Filter
                    </button>
                </div>
            </div>
        </form>

        <div class="table-responsive">
            <table class="table table-hover align-middle">
                <thead>
                    <tr>
                        <th style="width: 50px;">No</th>
                        <th style="width: 130px;">Invoice</th>
                        <th>Customer</th>
                        <th>Agent</th>
                        <th style="width: 120px;">Delivery Date</th>
                        <th style="width: 150px;">Status</th>
                        <th style="width: 100px;">Action</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($orders as $i => $order)
                    <tr>
                        <td class="text-center">{{ $orders->firstItem() + $i }}</td>
                        <td>
                            <span class="small text-muted">#</span>{{ $order->invoice_id }}
                        </td>
                        <td>
                            <div class="d-flex align-items-center">
                                <i class="fas fa-user text-muted me-2"></i>
                                {{ $order->name }}
                            </div>
                        </td>
                        <td>
                            @if($order->agent_id)
                                <div class="d-flex align-items-center">
                                    <i class="fas fa-store text-muted me-2"></i>
                                    {{ $order->store_name }}
                                </div>
                            @else
                                <span class="text-muted">
                                    <i class="fas fa-store-slash me-1"></i>No Agent
                                </span>
                            @endif
                        </td>
                        <td>{{ \Carbon\Carbon::parse($order->delivery_date)->format('d M Y') }}</td>
                        <td>
                            @php
                                [$statusText, $statusIcon, $statusClass] = match((int)$order->status) {
                                    1 => ['New Order', 'shopping-cart', 'secondary'],      // new order
                                    2 => ['Payment Success', 'check-circle', 'success'],   // payment success
                                    3 => ['Processing', 'cog', 'info'],                    // order process
                                    4 => ['Completed', 'check-double', 'primary'],         // completed
                                    5 => ['Cancelled', 'times-circle', 'danger'],          // cancel
                                    6 => ['Payment Pending', 'clock', 'warning'],          // payment pending
                                    7 => ['Payment Failed', 'exclamation-circle', 'danger'], // payment failed
                                    default => ['Unknown', 'question-circle', 'secondary']
                                };
                            @endphp
                            <div class="d-flex align-items-center">
                                <i class="fas fa-{{ $statusIcon }} text-{{ $statusClass }} me-2"></i>
                                <span class="badge bg-{{ $statusClass }}">{{ $statusText }}</span>
                            </div>
                        </td>
                        <td>
                            <a href="{{ route('report.orderDetail', $order->id) }}" 
                               class="btn btn-info btn-sm">
                                <i class="fas fa-eye me-1"></i>Detail
                            </a>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="7" class="text-center py-4">
                            <div class="text-muted">
                                <i class="fas fa-box-open mb-2" style="font-size: 2rem;"></i>
                                <p class="mb-0">No orders found</p>
                            </div>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="d-flex justify-content-end mt-4">
            {{ $orders->withQueryString()->links('pagination::bootstrap-5') }}
        </div>
    </div>
</div>

<style>
/* Status icon animations */
.fa-cog {
    animation: spin 2s linear infinite;
}
.fa-clock {
    animation: pulse 2s ease-in-out infinite;
}
@keyframes spin {
    100% { transform: rotate(360deg); }
}
@keyframes pulse {
    0% { opacity: 1; }
    50% { opacity: 0.5; }
    100% { opacity: 1; }
}
</style>
@endsection 