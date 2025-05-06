@extends('layouts.app')

@section('content')
<div class="card">
    <div class="card-header">
        <h5 class="card-title mb-0">
            <i class="fas fa-box me-2"></i>Top 10 Products
        </h5>
    </div>
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover mb-0">
                <thead>
                    <tr>
                        <th class="text-center" style="width: 60px;">No</th>
                        <th>Product Name</th>
                        <th class="text-end" style="width: 150px;">Total Sold</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($products as $i => $product)
                    <tr>
                        <td class="text-center">{{ $i+1 }}</td>
                        <td>{{ $product->product_name }}</td>
                        <td class="text-end">{{ number_format($product->total_terjual) }}</td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="3" class="text-center py-4">
                            <div class="text-muted">
                                <i class="fas fa-box-open mb-2" style="font-size: 2rem;"></i>
                                <p class="mb-0">No products found</p>
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