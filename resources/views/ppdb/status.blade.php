{{-- STATUS PENDAFTARAN --}}

{{-- STATUS CARD --}}
<div class="status-card {{ $registrant->status }}">
    @if($registrant->status === 'pending')
        <i class="fas fa-clock fa-2x mb-2"></i>
    @elseif($registrant->status === 'berkas_lengkap')
        <i class="fas fa-folder-open fa-2x mb-2"></i>
    @elseif($registrant->status === 'berkas_tidak_lengkap')
        <i class="fas fa-exclamation-triangle fa-2x mb-2"></i>
    @elseif($registrant->status === 'diterima')
        <i class="fas fa-check-circle fa-2x mb-2"></i>
    @elseif($registrant->status === 'ditolak')
        <i class="fas fa-times-circle fa-2x mb-2"></i>
    @endif
    <h3>{{ $registrant->status_label }}</h3>
    <p>No. Pendaftaran: <strong>{{ $registrant->registration_number }}</strong></p>
</div>

{{-- CATATAN VERIFIKASI --}}
@if($registrant->catatan_verifikasi)
    <div class="alert alert-{{ $registrant->status_color }} mb-3">
        <i class="fas fa-comment-alt mr-1"></i>
        <strong>Catatan dari Verifikator:</strong><br>
        {{ $registrant->catatan_verifikasi }}
    </div>
@endif

{{-- DATA RINGKASAN --}}
<div class="ppdb-card mb-4">
    <div class="card-header">
        <i class="fas fa-id-card mr-1"></i> Data Pendaftaran
    </div>
    <div class="card-body">
        <div class="row">
            <div class="col-md-3 text-center mb-3">
                @if($registrant->foto)
                    <img src="{{ Storage::url($registrant->foto) }}" class="img-fluid rounded-circle mb-2"
                        style="width:100px;height:100px;object-fit:cover;border:3px solid #28a745;">
                @else
                    <div class="rounded-circle bg-secondary d-inline-flex align-items-center justify-content-center mb-2"
                        style="width:100px;height:100px;">
                        <i class="fas fa-user fa-2x text-white"></i>
                    </div>
                @endif
            </div>
            <div class="col-md-9">
                <table class="table table-sm table-borderless mb-0">
                    <tr><td width="30%"><strong>Nama Lengkap</strong></td><td>{{ $registrant->nama_lengkap }}</td></tr>
                    <tr><td><strong>NISN</strong></td><td>{{ $registrant->nisn ?? '-' }}</td></tr>
                    <tr><td><strong>NIK</strong></td><td>{{ $registrant->nik ?? '-' }}</td></tr>
                    <tr><td><strong>Jenis Kelamin</strong></td><td>{{ $registrant->jenis_kelamin === 'L' ? 'Laki-laki' : 'Perempuan' }}</td></tr>
                    <tr><td><strong>TTL</strong></td><td>{{ $registrant->tempat_lahir }}, {{ $registrant->tanggal_lahir->format('d F Y') }}</td></tr>
                    <tr><td><strong>Asal Sekolah</strong></td><td>{{ $registrant->asal_sekolah ?? '-' }}</td></tr>
                    <tr><td><strong>Gelombang</strong></td><td>{{ $registrant->admissionPhase->phase_name ?? '-' }}</td></tr>
                    <tr><td><strong>Jalur</strong></td><td>{{ $registrant->admissionType->admission_type_name ?? '-' }}</td></tr>
                    <tr><td><strong>Nama Ayah</strong></td><td>{{ $registrant->nama_ayah ?? '-' }}</td></tr>
                    <tr><td><strong>Nama Ibu</strong></td><td>{{ $registrant->nama_ibu ?? '-' }}</td></tr>
                    <tr><td><strong>No. HP Ortu</strong></td><td>{{ $registrant->no_hp_ortu ?? '-' }}</td></tr>
                    <tr><td><strong>Alamat</strong></td><td>{{ $registrant->alamat ?? '-' }}</td></tr>
                </table>
            </div>
        </div>
    </div>
</div>

{{-- BERKAS --}}
<div class="ppdb-card mb-4">
    <div class="card-header">
        <i class="fas fa-folder-open mr-1"></i> Berkas Persyaratan
    </div>
    <div class="card-body p-0">
        @if($registrant->documents->count() > 0)
            @foreach($registrant->documents as $doc)
                <div class="doc-item">
                    <div>
                        <span class="doc-name">{{ $doc->document_name }}</span>
                        @if($doc->verification_note)
                            <br><small class="text-muted">{{ $doc->verification_note }}</small>
                        @endif
                    </div>
                    <div>
                        @if($doc->is_verified)
                            <span class="badge badge-success"><i class="fas fa-check"></i> Terverifikasi</span>
                        @else
                            <span class="badge badge-secondary"><i class="fas fa-clock"></i> Menunggu</span>
                        @endif
                    </div>
                </div>
            @endforeach
        @else
            <div class="text-center py-4 text-muted">
                <i class="fas fa-inbox fa-2x mb-2"></i>
                <p>Belum ada berkas diupload.</p>
            </div>
        @endif
    </div>
</div>

{{-- TOMBOL EDIT --}}
@if(in_array($registrant->status, ['pending', 'berkas_tidak_lengkap']))
    <div class="ppdb-card">
        <div class="card-body">
            <p class="mb-3 text-muted">
                <i class="fas fa-info-circle mr-1"></i>
                @if($registrant->status === 'berkas_tidak_lengkap')
                    Berkas Anda belum lengkap. Silakan edit dan lengkapi data pendaftaran.
                @else
                    Anda masih dapat mengubah data pendaftaran selama belum diverifikasi.
                @endif
            </p>
            <button type="button" class="btn btn-ppdb" id="btnShowEdit">
                <i class="fas fa-edit mr-1"></i> Edit Data Pendaftaran
            </button>
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
            $('#editFormContainer').slideDown(300);
            $('html, body').animate({ scrollTop: $('#editFormContainer').offset().top - 20 }, 500);
        });
    </script>
    @endpush
@endif

{{-- VERIFIKASI INFO --}}
@if($registrant->verified_at)
    <div class="ppdb-card mt-4">
        <div class="card-body text-muted text-center">
            <small>
                <i class="fas fa-shield-alt mr-1"></i>
                Diverifikasi oleh <strong>{{ $registrant->verifier->name ?? '-' }}</strong>
                pada {{ $registrant->verified_at->format('d F Y, H:i') }} WIB
            </small>
        </div>
    </div>
@endif
