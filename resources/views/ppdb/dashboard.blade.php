@extends('layouts.ppdb')

@section('title', 'Dashboard PPDB')

@section('content')

    {{-- WELCOME BANNER --}}
    <div class="welcome-banner">
        <h4><i class="fas fa-hand-peace mr-2"></i>Selamat Datang, {{ $user->name }}!</h4>
        @if($ppdbOpen)
            <p>Pendaftaran PPDB Tahun {{ $academicYear->academic_year ?? '' }} sedang dibuka.</p>
        @else
            <p>Informasi pendaftaran PPDB akan ditampilkan di sini.</p>
        @endif
    </div>

    @if(!$ppdbOpen && !$registrant)
        {{-- PPDB BELUM BUKA --}}
        <div class="ppdb-card">
            <div class="card-body text-center py-5">
                <i class="fas fa-calendar-times fa-3x text-muted mb-3"></i>
                <h5 class="text-muted">Pendaftaran PPDB Belum Dibuka</h5>
                <p class="text-muted">Silakan tunggu informasi pembukaan pendaftaran dari sekolah.</p>
            </div>
        </div>

    @elseif(!$registrant)
        {{-- FORM BIODATA --}}
        @include('ppdb.form-biodata', ['action' => route('ppdb.store_biodata'), 'method' => 'POST'])

    @else
        {{-- STATUS PENDAFTARAN --}}
        @include('ppdb.status')
    @endif

@endsection
