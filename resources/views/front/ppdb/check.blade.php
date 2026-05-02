@extends('layouts.front')

@section('content')
<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-md-6">
            <div class="card shadow-lg border-0 rounded-lg mt-5">
                <div class="card-header bg-primary text-white text-center py-4">
                    <h3 class="font-weight-light my-2">Cek Status PPDB</h3>
                    <p class="mb-0">Masukkan nomor pendaftaran Anda untuk melihat hasil</p>
                </div>
                <div class="card-body p-4">
                    @if(session('error'))
                        <div class="alert alert-danger">
                            {{ session('error') }}
                        </div>
                    @endif

                    <form action="{{ route('front.ppdb_submit') }}" method="POST">
                        @csrf
                        <div class="form-group mb-4">
                            <label for="registration_number" class="small mb-1">Nomor Pendaftaran</label>
                            <input class="form-control py-4" id="registration_number" name="registration_number" type="text" placeholder="Contoh: PPDB-2026-0001" required />
                        </div>
                        <div class="form-group d-flex align-items-center justify-content-between mt-4 mb-0">
                            <button type="submit" class="btn btn-primary btn-block py-2">
                                <i class="fas fa-search mr-2"></i> Cek Sekarang
                            </button>
                        </div>
                    </form>
                </div>
                <div class="card-footer text-center py-3 bg-light">
                    <div class="small text-muted">Belum mendaftar? <a href="#">Daftar Sekarang</a></div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
