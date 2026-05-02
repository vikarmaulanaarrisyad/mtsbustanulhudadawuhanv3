{{-- STATUS PENDAFTARAN --}}

{{-- STATUS CARD --}}
<div class="status-card {{ $registrant->status }}">
    @if($registrant->status === 'pending')
        <i class="fas fa-history fa-2x mb-3"></i>
    @elseif($registrant->status === 'berkas_lengkap')
        <i class="fas fa-folder-open fa-2x mb-3"></i>
    @elseif($registrant->status === 'berkas_tidak_lengkap')
        <i class="fas fa-exclamation-circle fa-2x mb-3"></i>
    @elseif($registrant->status === 'diterima')
        <i class="fas fa-check-double fa-2x mb-3"></i>
    @elseif($registrant->status === 'ditolak')
        <i class="fas fa-times-circle fa-2x mb-3"></i>
    @endif
    <h3 class="mb-1">{{ $registrant->status_label }}</h3>
    <p class="mb-0 font-weight-normal opacity-75">Nomor Registrasi: <span class="font-weight-bold">{{ $registrant->registration_number }}</span></p>
</div>

{{-- TOMBOL CETAK --}}
<div class="row mb-4">
    <div class="col-md-6 mb-3 mb-md-0">
        <div class="ppdb-card h-100 mb-0 border-left-success">
            <div class="card-body d-flex align-items-center">
                <div class="bg-success-light p-3 rounded-circle mr-3">
                    <i class="fas fa-id-card fa-2x text-success"></i>
                </div>
                <div>
                    <h6 class="font-weight-bold mb-1">Bukti Pendaftaran</h6>
                    <p class="small text-muted mb-2">Gunakan sebagai kartu tanda pengenal pendaftar.</p>
                    <a href="{{ route('ppdb.print_registration') }}" class="btn btn-sm btn-success px-3 shadow-sm">
                        <i class="fas fa-download mr-1"></i> Unduh Kartu
                    </a>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-6">
        <div class="ppdb-card h-100 mb-0 border-left-primary">
            <div class="card-body d-flex align-items-center">
                <div class="bg-primary-light p-3 rounded-circle mr-3">
                    <i class="fas fa-file-signature fa-2x text-primary"></i>
                </div>
                <div>
                    <h6 class="font-weight-bold mb-1">Lembar Verifikasi</h6>
                    <p class="small text-muted mb-2">Bawa saat verifikasi berkas fisik di sekolah.</p>
                    <a href="{{ route('ppdb.print_verification') }}" class="btn btn-sm btn-primary px-3 shadow-sm">
                        <i class="fas fa-print mr-1"></i> Cetak Lembar
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>

@if($registrant->status === 'diterima')
    <div class="ppdb-card border-left-success shadow-sm mb-4 animate__animated animate__pulse animate__infinite animate__slow">
        <div class="card-body">
            <div class="d-md-flex align-items-center justify-content-between">
                <div class="mb-3 mb-md-0">
                    <h5 class="font-weight-bold text-success mb-1"><i class="fas fa-award mr-2"></i>Selamat! Anda Dinyatakan Lulus</h5>
                    <p class="mb-0 text-muted">Silakan unduh Surat Keterangan Kelulusan (SK) resmi Anda di sini.</p>
                </div>
                <a href="{{ route('ppdb.print_letter', $registrant->id) }}" class="btn btn-success btn-lg px-4 shadow">
                    <i class="fas fa-file-pdf mr-2"></i> Cetak SK Kelulusan
                </a>
            </div>
        </div>
    </div>
@endif

<style>
    .border-left-success { border-left: 4px solid #28a745 !important; }
    .border-left-primary { border-left: 4px solid #007bff !important; }
    .bg-success-light { background-color: rgba(40, 167, 69, 0.1); }
    .bg-primary-light { background-color: rgba(0, 123, 255, 0.1); }
</style>

{{-- CATATAN VERIFIKASI --}}
@if($registrant->catatan_verifikasi)
    <div class="alert alert-{{ $registrant->status_color }} border-0 shadow-sm mb-4 p-4 rounded-xl" style="background: #fff; border-left: 5px solid currentColor !important;">
        <div class="d-flex align-items-start">
            <i class="fas fa-comment-dots mt-1 mr-3 fa-lg"></i>
            <div>
                <h6 class="font-weight-bold mb-1">Catatan dari Verifikator:</h6>
                <div class="text-dark">{{ $registrant->catatan_verifikasi }}</div>
            </div>
        </div>
    </div>
@endif

{{-- DATA RINGKASAN --}}
<div class="ppdb-card">
    <div class="card-header">
        <i class="fas fa-user-shield mr-2"></i> Ringkasan Profil Pendaftar
    </div>
    <div class="card-body">
        <div class="row align-items-center">
            <div class="col-md-3 text-center mb-4 mb-md-0">
                <div class="position-relative d-inline-block">
                    @if($registrant->foto)
                        <img src="{{ Storage::url($registrant->foto) }}" class="img-fluid rounded shadow"
                            style="width:140px;height:180px;object-fit:cover;border:4px solid #fff;">
                    @else
                        <div class="rounded bg-light d-flex align-items-center justify-content-center shadow-sm"
                            style="width:140px;height:180px;border:2px dashed #cbd5e0;">
                            <i class="fas fa-user-tie fa-4x text-light"></i>
                        </div>
                    @endif
                    <div class="mt-2">
                        <span class="badge badge-success px-3 py-2 rounded-pill shadow-sm">Pendaftar Aktif</span>
                    </div>
                </div>
            </div>
            <div class="col-md-9">
                <div class="row">
                    <div class="col-sm-6 mb-3">
                        <small class="text-muted text-uppercase font-weight-bold letter-spacing-1">Nama Lengkap</small>
                        <p class="mb-0 font-weight-bold text-dark">{{ $registrant->nama_lengkap }}</p>
                    </div>
                    <div class="col-sm-6 mb-3">
                        <small class="text-muted text-uppercase font-weight-bold letter-spacing-1">NISN / NIK</small>
                        <p class="mb-0 text-dark">{{ $registrant->nisn ?? '-' }} / {{ $registrant->nik ?? '-' }}</p>
                    </div>
                    <div class="col-sm-6 mb-3">
                        <small class="text-muted text-uppercase font-weight-bold letter-spacing-1">Tempat, Tgl Lahir</small>
                        <p class="mb-0 text-dark">{{ $registrant->tempat_lahir }}, {{ $registrant->tanggal_lahir->format('d M Y') }}</p>
                    </div>
                    <div class="col-sm-6 mb-3">
                        <small class="text-muted text-uppercase font-weight-bold letter-spacing-1">Asal Sekolah</small>
                        <p class="mb-0 text-dark">{{ $registrant->asal_sekolah ?? '-' }}</p>
                    </div>
                    <div class="col-sm-6 mb-3">
                        <small class="text-muted text-uppercase font-weight-bold letter-spacing-1">Gelombang / Jalur</small>
                        <p class="mb-0 text-dark">{{ $registrant->admissionPhase->phase_name ?? '-' }} ({{ $registrant->admissionType->admission_type_name ?? '-' }})</p>
                    </div>
                    <div class="col-sm-6 mb-3">
                        <small class="text-muted text-uppercase font-weight-bold letter-spacing-1">Kontak Orang Tua</small>
                        <p class="mb-0 text-dark"><i class="fab fa-whatsapp text-success mr-1"></i> {{ $registrant->no_hp_ortu ?? '-' }}</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

{{-- BERKAS --}}
<div class="ppdb-card">
    <div class="card-header">
        <i class="fas fa-file-invoice mr-2"></i> Verifikasi Berkas Persyaratan
    </div>
    <div class="card-body p-0">
        @if($registrant->documents->count() > 0)
            @foreach($registrant->documents as $doc)
                <div class="doc-item">
                    <div class="d-flex align-items-center">
                        <div class="bg-light p-2 rounded mr-3">
                            <i class="fas fa-file-alt text-primary"></i>
                        </div>
                        <div>
                            <span class="doc-name">{{ $doc->document_name }}</span>
                            @if($doc->verification_note)
                                <br><small class="text-danger font-italic"><i class="fas fa-exclamation-circle mr-1"></i> {{ $doc->verification_note }}</small>
                            @endif
                        </div>
                    </div>
                    <div>
                        @if($doc->is_verified)
                            <span class="badge badge-success px-3 py-2 rounded-pill"><i class="fas fa-check-circle mr-1"></i> Terverifikasi</span>
                        @else
                            <span class="badge badge-warning px-3 py-2 rounded-pill text-dark"><i class="fas fa-clock mr-1"></i> Sedang Ditinjau</span>
                        @endif
                    </div>
                </div>
            @endforeach
        @else
            <div class="text-center py-5 text-muted">
                <i class="fas fa-folder-open fa-3x mb-3 opacity-25"></i>
                <p class="mb-0">Belum ada dokumen yang diunggah ke sistem.</p>
            </div>
        @endif
    </div>
</div>

{{-- TOMBOL EDIT --}}
@if(in_array($registrant->status, ['pending', 'berkas_tidak_lengkap']))
    <div class="ppdb-card border-top border-warning" style="border-top-width: 4px !important;">
        <div class="card-body">
            <div class="d-md-flex align-items-center justify-content-between">
                <div class="mb-3 mb-md-0">
                    <h6 class="font-weight-bold text-dark mb-1">Perlu melakukan perubahan data?</h6>
                    <p class="mb-0 text-muted small">
                        @if($registrant->status === 'berkas_tidak_lengkap')
                            <i class="fas fa-exclamation-triangle text-warning mr-1"></i> Mohon segera lengkapi berkas Anda sesuai catatan verifikator.
                        @else
                            <i class="fas fa-info-circle text-info mr-1"></i> Data masih dapat diubah selama status pendaftaran masih dalam peninjauan.
                        @endif
                    </p>
                </div>
                <button type="button" class="btn btn-ppdb shadow-sm" id="btnShowEdit">
                    <i class="fas fa-pencil-alt mr-2"></i> Perbarui Data Sekarang
                </button>
            </div>
        </div>
    </div>

    {{-- FORM EDIT (hidden by default) --}}
    <div id="editFormContainer" style="display:none;" class="mt-4">
        @include('ppdb.form-biodata', [
            'action' => route('ppdb.update_biodata'),
            'method' => 'PUT',
            'registrant' => $registrant
        ])
    </div>

    @push('scripts')
    <script>
        $('#btnShowEdit').on('click', function() {
            $(this).closest('.ppdb-card').hide();
            $('#editFormContainer').slideDown(400);
            $('html, body').animate({ scrollTop: $('#editFormContainer').offset().top - 30 }, 600);
        });
    </script>
    @endpush
@endif

{{-- VERIFIKASI INFO --}}
@if($registrant->verified_at)
    <div class="text-center py-4 text-muted">
        <small class="font-italic">
            <i class="fas fa-shield-alt mr-1"></i>
            Validasi sistem otomatis — Verifikator: <strong>{{ $registrant->verifier->name ?? 'Admin Sistem' }}</strong>
            <br>Waktu Verifikasi: {{ $registrant->verified_at->format('d M Y, H:i') }} WIB
        </small>
    </div>
@endif
