<table id="customerTable" class="table table-bordered">
    <thead>
        <tr>
            <th>Nama</th>
            <th>Telepon</th>
            <th>Alamat</th>
            <th>Tanggal Registrasi</th>
            <th>Aksi</th>
        </tr>
    </thead>
    <tbody>
        @forelse($customers as $customer)
        <tr data-id="{{ $customer->id }}">
            <td>{{ $customer->first_name ? $customer->first_name . ' ' . $customer->last_name : '-' }}</td>
            <td>{{ $customer->phone ?? '-' }}</td>
            <td>{{ $customer->address ?? '-' }}</td>
            <td>{{ $customer->tanggal_registrasi ? date('d/m/Y', strtotime($customer->tanggal_registrasi)) : '-' }}</td>
            <td>
                <button class="viewBtn btn btn-sm btn-info">Detail</button>
                <button class="editBtn btn btn-sm btn-warning">Edit</button>
                <button class="deleteBtn btn btn-sm btn-danger">Hapus</button>
            </td>
        </tr>
        @empty
        <tr>
            <td colspan="5" class="text-center">Tidak ada data customer</td>
        </tr>
        @endforelse
    </tbody>
</table>
<div class="d-flex justify-content-center">
    {!! $customers->links('pagination::bootstrap-5') !!} 