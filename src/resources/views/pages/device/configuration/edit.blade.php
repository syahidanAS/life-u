<!-- Edit User -->
<div class="modal fade" id="modalEditData" data-bs-backdrop="static" aria-labelledby="modalEditDataLabel"
    aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="staticBackdropLabel">Ubah Perangkat</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form method="POST" id="editForm" action="{{ route('device.config.update') }}">
                    @csrf
                    <input type="text" class="form-control" id="id_edit" name="id_edit" hidden>
                    <div class="row mb-2">
                        <label for="user_id_edit" class="form-label" required>Nama Pengguna<span
                                class="text-danger">*</span></label>
                        <select class="form-select user_id_edit" id="user_id_edit" name="user_id_edit"
                            aria-label="user_id_edit">
                            <option value="">Pilih Pengguna</option>
                        </select>
                    </div>
                    <div class="mb-2">
                        <label for="token_edit" class="form-label" required>Token<span
                                class="text-danger">*</span></label>
                        <input type="text" class="form-control" id="token_edit" name="token_edit"
                            placeholder="Masukkan token dari blynk" autocomplete="off">
                    </div>
                    <div class="mb-2">
                        <label for="blynk_email_edit" class="form-label" required>Email Blynk<span
                                class="text-danger">*</span></label>
                        <input type="text" class="form-control" id="blynk_email_edit" name="blynk_email_edit"
                            placeholder="Masukkan email akun blynk" autocomplete="off">
                    </div>

                    <div class="mb-2">
                        <label for="blynk_password_edit" class="form-label" required>Password Blynk<span
                                class="text-danger">*</span></label>
                        <input type="text" class="form-control" id="blynk_password_edit" name="blynk_password_edit"
                            placeholder="Masukkan password akun blynk" autocomplete="off">
                    </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batalkan</button>
                <button id="editBtn" type="submit" class="btn btn-primary">Simpan Perubahan</button>
            </div>
            </form>
        </div>
    </div>
</div>