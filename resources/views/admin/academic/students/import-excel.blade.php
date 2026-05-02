<!-- Modal Import Excel -->
<div class="modal fade" id="importExcelModal" data-backdrop="static" data-keyboard="false">
    <div class="modal-dialog modal-lg">
        <div class="modal-content rounded-3 shadow-lg">
            <div class="modal-header bg-primary text-white">
                <h5 class="modal-title" id="importExcelModalLabel">
                    <i class="fas fa-file-import"></i> Import Data Siswa dari Excel
                </h5>
                <button type="button" class="close text-white" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <!-- Informasi -->
                <div class="alert alert-info d-flex align-items-start" role="alert">
                    <i class="fas fa-info-circle mr-2 mt-1"></i>
                    <div>
                        <strong>Petunjuk Import:</strong>
                        <ul class="mb-0 pl-3 mt-1">
                            <li>Download template terlebih dahulu</li>
                            <li>Isi data sesuai format kolom pada template</li>
                            <li>Kolom <strong>NIS</strong>, <strong>Nama Lengkap</strong>, <strong>Jenis Kelamin (L/P)</strong>, dan <strong>Tanggal Lahir</strong> wajib diisi</li>
                            <li>Kolom <strong>Kelas</strong> diisi format: <code>Kelas 7 A</code> (sesuai data kelas yang sudah ada)</li>
                            <li>Data dengan NIS yang sudah ada akan otomatis dilewati</li>
                        </ul>
                    </div>
                </div>

                <!-- Download Template -->
                <div class="mb-3">
                    <a href="{{ route('students.download_template') }}" class="btn btn-success btn-sm shadow">
                        <i class="fas fa-download"></i> Download Template Excel
                    </a>
                </div>

                <!-- Form Upload -->
                <form id="uploadFormStudent" action="{{ route('students.import_excel') }}" method="POST"
                    enctype="multipart/form-data">
                    @csrf
                    <div class="mb-3">
                        <label for="excelFileStudent" class="form-label fw-bold">Pilih File Excel</label>
                        <input type="file" class="form-control border-primary shadow-sm" id="excelFileStudent"
                            name="excelFile" accept=".xlsx, .xls" required>
                        <div class="small text-muted mt-1">Hanya file dengan format .xlsx atau .xls (maks 5MB).</div>
                    </div>

                    <!-- Progress Bar -->
                    <div class="progress mb-3 d-none" id="uploadProgressStudent">
                        <div class="progress-bar progress-bar-striped progress-bar-animated bg-success"
                            role="progressbar" style="width: 0%;" id="progressBarStudent">
                        </div>
                    </div>

                    <div class="d-flex justify-content-end">
                        <button type="button" class="btn btn-secondary mr-2" data-dismiss="modal">Close</button>
                        <button type="submit" class="btn btn-primary shadow" id="uploadBtnStudent">
                            <i class="fas fa-upload"></i> Upload & Import
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

@push('scripts')
    <script>
        $(document).ready(function() {
            $("#uploadFormStudent").on("submit", function(event) {
                event.preventDefault();

                let form = this;
                let progressBar = $("#progressBarStudent");
                let uploadProgress = $("#uploadProgressStudent");
                let fileInput = $("#excelFileStudent");
                let uploadBtn = $("#uploadBtnStudent");

                uploadProgress.removeClass("d-none");
                progressBar.css("width", "0%").removeClass("bg-success bg-danger");

                uploadBtn.prop("disabled", true).html(`
                    <span class="spinner-border spinner-border-sm me-1" role="status" aria-hidden="true"></span>
                    Mengimport...
                `);

                let formData = new FormData(form);
                let xhr = new XMLHttpRequest();
                xhr.open("POST", form.action, true);

                // Set CSRF header
                xhr.setRequestHeader('X-CSRF-TOKEN', $('meta[name="csrf-token"]').attr('content'));

                xhr.upload.onprogress = function(e) {
                    if (e.lengthComputable) {
                        let percentComplete = (e.loaded / e.total) * 100;
                        progressBar.css("width", percentComplete + "%");
                    }
                };

                xhr.onload = function() {
                    if (xhr.status === 200) {
                        progressBar.addClass("bg-success");
                        let response = JSON.parse(xhr.responseText);
                        Swal.fire({
                            icon: 'success',
                            title: 'Berhasil!',
                            text: response.message || 'Data siswa berhasil diimport.',
                            timer: 3000,
                            showConfirmButton: false
                        }).then(() => {
                            $('#importExcelModal').modal('hide');
                            table.ajax.reload();
                            // Reset
                            uploadBtn.prop("disabled", false).html('<i class="fas fa-upload"></i> Upload & Import');
                            progressBar.css("width", "0%").removeClass("bg-success bg-danger");
                            uploadProgress.addClass("d-none");
                            fileInput.val("");
                        });
                    } else {
                        uploadBtn.prop("disabled", false).html('<i class="fas fa-upload"></i> Upload & Import');
                        let errorMsg = 'Terjadi kesalahan saat import!';
                        try {
                            let response = JSON.parse(xhr.responseText);
                            errorMsg = response.message || errorMsg;
                        } catch (e) {}
                        Swal.fire({
                            icon: 'error',
                            title: 'Oops!',
                            text: errorMsg,
                            timer: 3000,
                        }).then(() => {
                            progressBar.addClass("bg-danger");
                            progressBar.css("width", "0%").removeClass("bg-success bg-danger");
                            uploadProgress.addClass("d-none");
                            fileInput.val("");
                        });
                    }
                };

                xhr.onerror = function() {
                    uploadBtn.prop("disabled", false).html('<i class="fas fa-upload"></i> Upload & Import');
                    Swal.fire({
                        icon: 'error',
                        title: 'Oops!',
                        text: 'Gagal mengunggah file. Periksa koneksi internet Anda.',
                        showConfirmButton: true
                    });
                };

                xhr.send(formData);
            });

            // Reset saat modal ditutup
            $("#importExcelModal").on("hidden.bs.modal", function() {
                let form = $("#uploadFormStudent")[0];
                let fileInput = $("#excelFileStudent");
                let progressBar = $("#progressBarStudent");
                let uploadProgress = $("#uploadProgressStudent");
                let uploadBtn = $("#uploadBtnStudent");

                setTimeout(function() {
                    form.reset();
                    fileInput.val("");
                    progressBar.css("width", "0%").removeClass("bg-success bg-danger");
                    uploadProgress.addClass("d-none");
                    uploadBtn.prop("disabled", false).html('<i class="fas fa-upload"></i> Upload & Import');
                }, 300);
            });
        });
    </script>
@endpush
