@extends('layouts.app')

@section('content')
<div class="card">
    <div class="card-header">
        <h5 class="card-title mb-0">
            <i class="fas fa-users me-2"></i>Top 10 Customers
        </h5>
    </div>
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover mb-0">
                <thead>
                    <tr>
                        <th class="text-center" style="width: 60px;">No</th>
                        <th>Customer Name</th>
                        <th class="text-end" style="width: 150px;">Total Orders</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($customers as $i => $customer)
                    <tr>
                        <td class="text-center">{{ $i+1 }}</td>
                        <td>{{ $customer->customer_name }}</td>
                        <td class="text-end">{{ number_format($customer->total_order) }}</td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="3" class="text-center py-4">
                            <div class="text-muted">
                                <i class="fas fa-users mb-2" style="font-size: 2rem;"></i>
                                <p class="mb-0">No customers found</p>
                            </div>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection 