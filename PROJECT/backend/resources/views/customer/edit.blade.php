<div class="modal-content">
  <div class="modal-header">
    <h5 class="modal-title" id="editCustomerModalLabel">Edit Customer</h5>
    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
  </div>
  <form id="editCustomerForm">
    <div class="modal-body">
      <input type="hidden" id="edit_customer_id" value="">
      <div class="mb-3">
        <label>Nama Depan</label>
        <input type="text" class="form-control" id="edit_first_name" name="first_name" value="" required>
      </div>
      <div class="mb-3">
        <label>Nama Belakang</label>
        <input type="text" class="form-control" id="edit_last_name" name="last_name" value="">
      </div>
      <div class="mb-3">
        <label>No. Telepon</label>
        <input type="text" class="form-control" id="edit_phone" name="phone" value="" required>
      </div>
      <div class="mb-3">
        <label>Alamat</label>
        <textarea class="form-control" id="edit_address" name="address" required></textarea>
      </div>
      <div class="mb-3">
        <label>Tanggal Registrasi</label>
        <input type="date" class="form-control" id="edit_tanggal_registrasi" name="tanggal_registrasi" value="" required>
      </div>
    </div>
    <div class="modal-footer">
      <button type="submit" class="btn btn-success">Simpan</button>
      <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
    </div>
  </form>
</div>
<script>
// Tidak perlu script di sini, semua dihandle dari index.blade.php
</script> 