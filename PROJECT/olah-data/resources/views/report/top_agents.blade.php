@extends('layouts.app')

@section('content')
<div class="card">
    <div class="card-header">
        <h5 class="card-title mb-0">
            <i class="fas fa-user-tie me-2"></i>Top 10 Agents
        </h5>
    </div>
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover mb-0">
                <thead>
                    <tr>
                        <th class="text-center" style="width: 60px;">No</th>
                        <th style="width: 150px;">Partner ID</th>
                        <th>Store Name</th>
                        <th class="text-end" style="width: 150px;">Total Customers</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($agents as $i => $agent)
                    <tr>
                        <td class="text-center">{{ $i+1 }}</td>
                        <td>{{ $agent->partner_id }}</td>
                        <td>{{ $agent->store_name }}</td>
                        <td class="text-end">{{ number_format($agent->total_customer) }}</td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="4" class="text-center py-4">
                            <div class="text-muted">
                                <i class="fas fa-user-tie mb-2" style="font-size: 2rem;"></i>
                                <p class="mb-0">No agents found</p>
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