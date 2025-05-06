@extends('layouts.app')

@section('content')
<div class="card">
    <div class="card-header d-flex justify-content-between align-items-center">
        <h5 class="card-title mb-0">
            <i class="fas fa-file-invoice me-2"></i>Order #{{ $order->invoice_id }}
        </h5>
        <a href="{{ route('report.orders') }}" class="btn btn-secondary btn-sm">
            <i class="fas fa-arrow-left me-1"></i>Back to List
        </a>
    </div>
    <div class="card-body">
        <div class="row">
            <div class="col-md-6">
                <div class="card mb-3">
                    <div class="card-header">
                        <h6 class="card-title mb-0">
                            <i class="fas fa-info-circle me-2"></i>Order Information
                        </h6>
                    </div>
                    <div class="card-body">
                        <table class="table table-sm">
                            <tr>
                                <th style="width: 150px;">Invoice ID</th>
                                <td>{{ $order->invoice_id }}</td>
                            </tr>
                            <tr>
                                <th>Customer</th>
                                <td>{{ $order->name }}</td>
                            </tr>
                            <tr>
                                <th>Agent</th>
                                <td>
                                    @if($order->agent_id)
                                        <div class="small">
                                            <div><strong>ID:</strong> {{ $order->partner_id }}</div>
                                            <div><strong>Store:</strong> {{ $order->store_name }}</div>
                                        </div>
                                    @else
                                        <span class="text-muted">No Agent</span>
                                    @endif
                                </td>
                            </tr>
                            <tr>
                                <th>Order Time</th>
                                <td>{{ $order->order_time ? \Carbon\Carbon::parse($order->order_time)->format('d M Y H:i') : '-' }}</td>
                            </tr>
                            <tr>
                                <th>Delivery Date</th>
                                <td>{{ $order->delivery_date ? \Carbon\Carbon::parse($order->delivery_date)->format('d M Y') : '-' }}</td>
                            </tr>
                            <tr>
                                <th>Payment Date</th>
                                <td>{{ $order->payment_date ? \Carbon\Carbon::parse($order->payment_date)->format('d M Y H:i') : '-' }}</td>
                            </tr>
                            <tr>
                                <th>Status</th>
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
                            </tr>
                            @if($order->payment_method)
                            <tr>
                                <th>Payment Method</th>
                                <td>{{ $order->payment_method }}</td>
                            </tr>
                            @endif
                        </table>
                    </div>
                </div>

                <div class="card">
                    <div class="card-header">
                        <h6 class="card-title mb-0">
                            <i class="fas fa-map-marker-alt me-2"></i>Delivery Information
                        </h6>
                    </div>
                    <div class="card-body">
                        <table class="table table-sm">
                            <tr>
                                <th style="width: 150px;">Address</th>
                                <td>{{ $order->address }}</td>
                            </tr>
                            <tr>
                                <th>Area</th>
                                <td>
                                    {{ $order->kelurahan }}, 
                                    {{ $order->kecamatan }}, 
                                    {{ $order->kota }}, 
                                    {{ $order->provinsi }} {{ $order->kode_pos }}
                                </td>
                            </tr>
                            <tr>
                                <th>Phone</th>
                                <td>{{ $order->phone }}</td>
                            </tr>
                            @if($order->delivery_fee > 0)
                            <tr>
                                <th>Delivery Fee</th>
                                <td>{{ number_format($order->delivery_fee) }}</td>
                            </tr>
                            @endif
                        </table>
                    </div>
                </div>
            </div>

            <div class="col-md-6">
                <div class="card">
                    <div class="card-header">
                        <h6 class="card-title mb-0">
                            <i class="fas fa-shopping-basket me-2"></i>Order Items
                        </h6>
                    </div>
                    <div class="card-body p-0">
                        <div class="table-responsive">
                            <table class="table table-sm table-hover mb-0">
                                <thead>
                                    <tr>
                                        <th class="text-center" style="width: 50px;">No</th>
                                        <th>Product</th>
                                        <th class="text-end" style="width: 80px;">Qty</th>
                                        <th class="text-end" style="width: 120px;">Price</th>
                                        <th class="text-end" style="width: 120px;">Subtotal</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @php $total = 0; @endphp
                                    @forelse($order_details as $i => $detail)
                                        @php $subtotal = $detail->qty * $detail->price; $total += $subtotal; @endphp
                                        <tr>
                                            <td class="text-center">{{ $i + 1 }}</td>
                                            <td>{{ $detail->product_name }}</td>
                                            <td class="text-end">{{ number_format($detail->qty) }}</td>
                                            <td class="text-end">{{ number_format($detail->price) }}</td>
                                            <td class="text-end">{{ number_format($subtotal) }}</td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="5" class="text-center py-3 text-muted">
                                                <i class="fas fa-box-open mb-2" style="font-size: 2rem;"></i>
                                                <p class="mb-0">No items found</p>
                                            </td>
                                        </tr>
                                    @endforelse
                                </tbody>
                                <tfoot class="table-group-divider">
                                    <tr class="fw-bold">
                                        <td colspan="4" class="text-end">Subtotal:</td>
                                        <td class="text-end">{{ number_format($total) }}</td>
                                    </tr>
                                    @if($order->payment_discount > 0)
                                    <tr>
                                        <td colspan="4" class="text-end">Discount:</td>
                                        <td class="text-end">-{{ number_format($order->payment_discount) }}</td>
                                    </tr>
                                    @endif
                                    @if($order->delivery_fee > 0)
                                    <tr>
                                        <td colspan="4" class="text-end">Delivery Fee:</td>
                                        <td class="text-end">{{ number_format($order->delivery_fee) }}</td>
                                    </tr>
                                    @endif
                                    <tr class="fw-bold">
                                        <td colspan="4" class="text-end">Final Total:</td>
                                        <td class="text-end">{{ number_format($order->payment_final) }}</td>
                                    </tr>
                                </tfoot>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
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