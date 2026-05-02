<div class="modal fade" id="modalResetPassword" tabindex="-1" role="dialog" aria-labelledby="modalResetPasswordLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="modalResetPasswordLabel">Reset Password User: <span id="resetUserName"></span></h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form id="formResetPassword" onsubmit="event.preventDefault(); submitResetPassword(this);">
                @csrf
                <div class="modal-body">
                    <div class="form-group">
                        <label for="new_password">Password Baru</label>
                        <input type="password" name="password" id="new_password" class="form-control" required minlength="6">
                    </div>
                    <div class="form-group">
                        <label for="password_confirmation">Konfirmasi Password Baru</label>
                        <input type="password" name="password_confirmation" id="password_confirmation" class="form-control" required minlength="6">
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Tutup</button>
                    <button type="submit" class="btn btn-primary" id="btnSubmitReset">Simpan Password</button>
                </div>
            </form>
        </div>
    </div>
</div>
