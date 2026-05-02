@extends($layout)

@section('title', 'Pengaturan Kop Surat')
@section('subtitle', 'Persuratan')

@section('content')
    <div class="row">
        <div class="col-md-8">
            <x-card>
                <x-slot name="header">
                    <h3 class="card-title"><i class="fas fa-heading mr-1"></i> Detail Kop Surat</h3>
                </x-slot>

                <form id="formSetting" onsubmit="event.preventDefault(); updateSetting(this);">
                    @csrf
                    <input type="file" name="logo" id="logoInput" style="display:none;" onchange="previewImage(this)">

                    <div class="row">
                        <div class="col-md-9">
                            <div class="form-group">
                                <label>Nama Sekolah / Lembaga</label>
                                <input type="text" name="school_name" class="form-control"
                                    value="{{ $mailSetting->school_name }}">
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group">
                                <label>Singkatan (Kop)</label>
                                <input type="text" name="school_code" class="form-control"
                                    value="{{ $mailSetting->school_code }}" placeholder="Contoh: MTs-BH">
                            </div>
                        </div>
                    </div>
                    <div class="form-group">
                        <label>Sub Header (Yayasan/Instansi di Atasnya)</label>
                        <input type="text" name="sub_header" class="form-control" value="{{ $mailSetting->sub_header }}">
                    </div>
                    <div class="form-group">
                        <label>Alamat Lengkap</label>
                        <textarea name="address" class="form-control" rows="2">{{ $mailSetting->address }}</textarea>
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>Telepon</label>
                                <input type="text" name="phone" class="form-control" value="{{ $mailSetting->phone }}">
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>Email</label>
                                <input type="email" name="email" class="form-control" value="{{ $mailSetting->email }}">
                            </div>
                        </div>
                    </div>
                    <div class="form-group">
                        <label>Website</label>
                        <input type="text" name="website" class="form-control" value="{{ $mailSetting->website }}">
                    </div>

                    <hr>
                    <h5 class="text-primary mb-3"><i class="fas fa-file-signature mr-1"></i> Penandatangan Default</h5>
                    <div class="row">
                        <div class="col-md-4">
                            <div class="form-group">
                                <label>Nama Penandatangan</label>
                                <input type="text" name="default_signer_name" class="form-control"
                                    value="{{ $mailSetting->default_signer_name }}"
                                    placeholder="Contoh: Budi Santoso, S.Pd.I">
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label>Jabatan</label>
                                <input type="text" name="default_signer_position" class="form-control"
                                    value="{{ $mailSetting->default_signer_position }}"
                                    placeholder="Contoh: Kepala Madrasah">
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label>NIP</label>
                                <input type="text" name="default_signer_nip" class="form-control"
                                    value="{{ $mailSetting->default_signer_nip }}" placeholder="Contoh: 1980...">
                            </div>
                        </div>
                    </div>

                    <hr>
                    <div class="form-group">
                        <label>Garis Header</label>
                        <select name="header_line_style" class="form-control">
                            <option value="none" {{ $mailSetting->header_line_style == 'none' ? 'selected' : '' }}>Tanpa
                                Garis</option>
                            <option value="solid" {{ $mailSetting->header_line_style == 'solid' ? 'selected' : '' }}>Garis
                                Tunggal</option>
                            <option value="double" {{ $mailSetting->header_line_style == 'double' ? 'selected' : '' }}>
                                Garis Ganda</option>
                        </select>
                    </div>

                    <hr>
                    <button type="submit" class="btn btn-primary" id="btnSave">
                        <i class="fas fa-save mr-1"></i> Simpan Perubahan
                    </button>
                </form>
            </x-card>
        </div>

        <div class="col-md-4">
            <x-card>
                <x-slot name="header">
                    <h3 class="card-title"><i class="fas fa-image mr-1"></i> Logo Lembaga</h3>
                </x-slot>
                <div class="text-center p-3">
                    @if ($mailSetting->logo)
                        <img src="{{ Storage::url($mailSetting->logo) }}" id="previewLogo" class="img-fluid mb-3"
                            style="max-height: 150px; border: 1px solid #ddd; padding: 10px;">
                    @else
                        <div class="bg-light py-5 mb-3 border">Belum ada logo</div>
                    @endif
                    <div class="form-group">
                        <button type="button" class="btn btn-outline-info btn-sm btn-block"
                            onclick="$('#logoInput').click()">
                            <i class="fas fa-upload mr-1"></i> Pilih Logo Baru
                        </button>
                        <small class="text-muted">Format: PNG, JPG (Maks 2MB)</small>
                    </div>
                </div>
            </x-card>

            <div class="alert alert-info">
                <h5><i class="icon fas fa-info"></i> Info</h5>
                Pengaturan ini akan digunakan secara otomatis sebagai Kop Surat pada semua dokumen PDF yang dihasilkan oleh
                sistem persuratan.
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script>
        function previewImage(input) {
            if (input.files && input.files[0]) {
                var reader = new FileReader();
                reader.onload = function(e) {
                    $('#previewLogo').attr('src', e.target.result).show();
                    if ($('#previewLogo').length == 0) {
                        $('.text-center.p-3').prepend('<img src="' + e.target.result +
                            '" id="previewLogo" class="img-fluid mb-3" style="max-height: 150px; border: 1px solid #ddd; padding: 10px;">'
                        );
                        $('.bg-light').remove();
                    }
                }
                reader.readAsDataURL(input.files[0]);
            }
        }

        function updateSetting(form) {
            Swal.fire({
                title: 'Menyimpan...',
                allowOutsideClick: false,
                didOpen: () => Swal.showLoading()
            });
            let formData = new FormData(form);

            $.ajax({
                url: "{{ route('mail-settings.update') }}",
                type: "POST",
                data: formData,
                contentType: false,
                processData: false,
                success: function(response) {
                    Swal.fire({
                        icon: 'success',
                        title: 'Berhasil',
                        text: response.message
                    }).then(() => {
                        location.reload();
                    });
                },
                error: function(xhr) {
                    Swal.fire({
                        icon: 'error',
                        title: 'Gagal',
                        text: xhr.responseJSON?.message || 'Terjadi kesalahan'
                    });
                }
            });
        }
    </script>
@endpush
