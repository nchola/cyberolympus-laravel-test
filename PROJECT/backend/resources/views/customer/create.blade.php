
<div class="container">
    <div class="modal-content">
        <div class="modal-header">
            <h5 class="modal-title" id="createCustomerModalLabel">Tambah Customer</h5>
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <form id="customerCreateForm">
            <div class="modal-body">
                <div class="mb-3">
                    <label>Nama Depan</label>
                    <input type="text" class="form-control" id="first_name" name="first_name" required>
                </div>
                <div class="mb-3">
                    <label>Nama Belakang</label>
                    <input type="text" class="form-control" id="last_name" name="last_name">
                </div>
                <div class="mb-3">
                    <label>No. Telepon</label>
                    <input type="text" class="form-control" id="phone" name="phone" required>
                </div>
                <div class="mb-3">
                    <label>Alamat</label>
                    <textarea class="form-control" id="address" name="address" required></textarea>
                </div>
                <div class="mb-3">
                    <label>Tanggal Registrasi</label>
                    <input type="date" class="form-control" id="tanggal_registrasi" name="tanggal_registrasi" required>
                </div>
            </div>
            <div class="modal-footer">
                <button type="submit" class="btn btn-success">Simpan</button>
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
            </div>
        </form>
    </div>
</div>

@push('scripts')
<script>
$(function(){
    $('#customerCreateForm').submit(function(e){
        e.preventDefault();
        $.ajax({
            url: '/customers',
            type: 'POST',
            data: {
                _token: '{{ csrf_token() }}',
                first_name: $('#first_name').val(),
                last_name: $('#last_name').val(),
                phone: $('#phone').val(),
                address: $('#address').val(),
                tanggal_registrasi: $('#tanggal_registrasi').val()
            },
            success: function(res) {
                window.location.href = '/customers';
            },
            error: function(xhr) {
                alert('Terjadi kesalahan! ' + xhr.responseJSON.message);
            }
        });
    });
});
</script>
@endpush 