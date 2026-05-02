@extends('layouts.front')

@section('content')
<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card shadow-lg border-0 rounded-lg mt-5">
                <div class="card-header bg-dark text-white text-center py-4">
                    <h3 class="font-weight-light my-2">Hasil Pengumuman PPDB</h3>
                </div>
                <div class="card-body p-5">
                    <div class="text-center mb-5">
                        <h4 class="text-muted mb-1">Status Pendaftaran Anda:</h4>
                        <div class="display-4 font-weight-bold">
                            @if($registrant->status == 'diterima')
                                <span class="text-success text-uppercase">LULUS / DITERIMA</span>
                            @elseif($registrant->status == 'ditolak')
                                <span class="text-danger text-uppercase">TIDAK DITERIMA</span>
                            @else
                                <span class="text-warning text-uppercase">DALAM PROSES</span>
                            @endif
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6 border-right">
                            <h5 class="border-bottom pb-2">Data Pendaftar</h5>
                            <table class="table table-borderless table-sm">
                                <tr>
                                    <td width="40%">No. Reg</td>
                                    <td>: <strong>{{ $registrant->registration_number }}</strong></td>
                                </tr>
                                <tr>
                                    <td>Nama</td>
                                    <td>: {{ $registrant->nama_lengkap }}</td>
                                </tr>
                                <tr>
                                    <td>Asal Sekolah</td>
                                    <td>: {{ $registrant->asal_sekolah }}</td>
                                </tr>
                            </table>
                        </div>
                        <div class="col-md-6 pl-md-4">
                            <h5 class="border-bottom pb-2">Detail Pendaftaran</h5>
                            <table class="table table-borderless table-sm">
                                <tr>
                                    <td width="40%">Jalur</td>
                                    <td>: {{ $registrant->admissionType->admission_type_name ?? '-' }}</td>
                                </tr>
                                <tr>
                                    <td>Gelombang</td>
                                    <td>: {{ $registrant->admissionPhase->phase_name ?? '-' }}</td>
                                </tr>
                                <tr>
                                    <td>Tanggal Daftar</td>
                                    <td>: {{ $registrant->created_at->format('d/m/Y') }}</td>
                                </tr>
                            </table>
                        </div>
                    </div>

                    @if($registrant->status == 'diterima')
                    <div class="alert alert-success mt-4">
                        <h5 class="alert-heading"><i class="fas fa-check-circle"></i> Selamat!</h5>
                        <p>Anda dinyatakan lulus seleksi PPDB. Silakan lakukan daftar ulang ke sekolah dengan membawa berkas yang diperlukan atau hubungi panitia PPDB.</p>
                    </div>
                    @elseif($registrant->status == 'pending' || $registrant->status == 'berkas_lengkap')
                    <div class="alert alert-info mt-4">
                        <h5 class="alert-heading"><i class="fas fa-info-circle"></i> Informasi</h5>
                        <p>Data Anda sedang dalam proses verifikasi oleh tim panitia. Silakan cek kembali secara berkala.</p>
                    </div>
                    @endif

                    @if($registrant->catatan_verifikasi)
                    <div class="mt-4 p-3 bg-light border rounded">
                        <small class="text-muted d-block mb-1">Catatan Panitia:</small>
                        <p class="mb-0 italic">"{{ $registrant->catatan_verifikasi }}"</p>
                    </div>
                    @endif
                </div>
                <div class="card-footer text-center py-3 bg-light d-flex justify-content-between px-4">
                    <a href="{{ route('front.ppdb_check') }}" class="btn btn-outline-secondary btn-sm"><i class="fas fa-arrow-left mr-1"></i> Cek Nomor Lain</a>
                    @if($registrant->status == 'diterima')
                        <button onclick="window.print()" class="btn btn-primary btn-sm"><i class="fas fa-print mr-1"></i> Cetak Bukti</button>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
