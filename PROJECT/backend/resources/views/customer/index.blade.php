@extends('layouts.app')
@section('content')
<div class="container">
    <h2>Daftar Customer</h2>
    
    <!-- Search and Filter -->
    <div class="row mb-3">
        <div class="col-md-4">
            <label for="search" class="form-label">Nama Customer</label>
            <input type="text" id="search" class="form-control" placeholder="Cari customer...">
        </div>
        <div class="col-md-3">
            <label for="start_date" class="form-label">Tanggal Registrasi Mulai</label>
            <input type="date" id="start_date" class="form-control" placeholder="Tanggal Mulai">
        </div>
        <div class="col-md-3">
            <label for="end_date" class="form-label">Tanggal Registrasi Akhir</label>
            <input type="date" id="end_date" class="form-control" placeholder="Tanggal Akhir">
        </div>
        <div class="col-md-2">
            <button id="filterBtn" class="btn btn-primary">Filter</button>
        </div>
    </div>

    <button id="addBtn" class="btn btn-primary mb-3">Tambah Customer</button>

    <div id="tableContainer">
        @include('customer.table')
    </div>
</div>

<!-- Modal Detail Customer -->
<div class="modal fade" id="showCustomerModal" tabindex="-1" aria-labelledby="showCustomerModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="showCustomerModalLabel">Detail Customer</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        <table class="table">
          <tr><th>Nama</th><td id="show_nama"></td></tr>
          <tr><th>Telepon</th><td id="show_telepon"></td></tr>
          <tr><th>Alamat</th><td id="show_alamat"></td></tr>
          <tr><th>Tanggal Registrasi</th><td id="show_tanggal"></td></tr>
        </table>
      </div>
    </div>
  </div>
</div>

<!-- Modal Edit Customer -->
<div class="modal fade" id="editCustomerModal" tabindex="-1" aria-labelledby="editCustomerModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    @include('customer.edit', ['customer' => null])
  </div>
</div>

<!-- Modal Create Customer -->
<div class="modal fade" id="createCustomerModal" tabindex="-1" aria-labelledby="createCustomerModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    @include('customer.create')
  </div>
</div>

@push('scripts')
<script>
$(function(){
    // Search with debounce
    let searchTimer;
    $('#search').on('keyup', function(){
        clearTimeout(searchTimer);
        searchTimer = setTimeout(function(){
            loadCustomers();
        }, 500);
    });

    // Date filter
    $('#filterBtn').click(function(){
        loadCustomers();
    });

    function loadCustomers(page = 1) {
        $.ajax({
            url: '{{ route("customers.index") }}',
            data: {
                page: page,
                search: $('#search').val(),
                start_date: $('#start_date').val(),
                end_date: $('#end_date').val()
            },
            success: function(response) {
                $('#tableContainer').html(response);
            }
        });
    }

    // Handle pagination clicks
    $(document).on('click', '.pagination a', function(e) {
        e.preventDefault();
        let page = $(this).attr('href').split('page=')[1];
        loadCustomers(page);
    });

    // Tampilkan modal tambah
    $('#addBtn').click(function(){
        $('#customerCreateForm')[0].reset();
        $('#createCustomerModal').modal('show');
    });

    // Tampilkan modal detail
    $(document).on('click', '.viewBtn', function(){
        let id = $(this).closest('tr').data('id');
        $.get('/customers/' + id, function(res) {
            $('#show_nama').text((res.first_name || '') + ' ' + (res.last_name || ''));
            $('#show_telepon').text(res.phone || '-');
            $('#show_alamat').text(res.address || '-');
            $('#show_tanggal').text(res.tanggal_registrasi ? new Date(res.tanggal_registrasi).toLocaleDateString('id-ID') : '-');
            $('#showCustomerModal').modal('show');
        });
    });

    // Tampilkan modal edit
    $(document).on('click', '.editBtn', function(){
        let id = $(this).closest('tr').data('id');
        $.get('/customers/' + id, function(res) {
            $('#edit_customer_id').val(id);
            $('#edit_first_name').val(res.first_name || '');
            $('#edit_last_name').val(res.last_name || '');
            $('#edit_phone').val(res.phone || '');
            $('#edit_address').val(res.address || '');
            $('#edit_tanggal_registrasi').val(res.tanggal_registrasi ? res.tanggal_registrasi.substr(0,10) : '');
            $('#editCustomerModal').modal('show');
        });
    });

    // Submit form edit
    $('#editCustomerForm').submit(function(e){
        e.preventDefault();
        let id = $('#edit_customer_id').val();
        $.ajax({
            url: '/customers/' + id,
            type: 'PUT',
            data: {
                _token: '{{ csrf_token() }}',
                first_name: $('#edit_first_name').val(),
                last_name: $('#edit_last_name').val(),
                phone: $('#edit_phone').val(),
                address: $('#edit_address').val(),
                tanggal_registrasi: $('#edit_tanggal_registrasi').val()
            },
            success: function(res) {
                $('#editCustomerModal').modal('hide');
                loadCustomers();
            },
            error: function(xhr) {
                alert('Terjadi kesalahan! ' + xhr.responseJSON.message);
            }
        });
    });

    // Hapus customer
    $(document).on('click', '.deleteBtn', function(){
        let id = $(this).closest('tr').data('id');
        if(confirm('Yakin hapus?')) {
            $.ajax({
                url: '/customers/' + id,
                type: 'DELETE',
                data: {_token: '{{ csrf_token() }}'},
                success: function(res) { 
                    loadCustomers();
                },
                error: function(xhr) {
                    alert('Gagal menghapus data!');
                }
            });
        }
    });
});
</script>
@endpush
@endsection 