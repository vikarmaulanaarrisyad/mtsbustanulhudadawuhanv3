@extends('layouts.front')

@section('title', 'Monitoring Hasil Seleksi PPDB Real-Time')

@push('css')
    <meta http-equiv="refresh" content="60"> {{-- Refresh halaman setiap 60 detik --}}
    <style>
        .monitoring-hero {
            background: linear-gradient(135deg, #0b8c89, #14b8a6);
            padding: 80px 0;
            color: white;
            text-align: center;
            border-radius: 0 0 50px 50px;
            margin-bottom: 50px;
            box-shadow: 0 15px 30px rgba(11, 140, 137, 0.2);
        }

        .refresh-indicator {
            display: inline-flex;
            align-items: center;
            background: rgba(255,255,255,0.15);
            backdrop-filter: blur(10px);
            padding: 8px 20px;
            border-radius: 50px;
            font-size: 0.85rem;
            margin-top: 20px;
            border: 1px solid rgba(255,255,255,0.2);
        }

        .refresh-dot {
            width: 10px;
            height: 10px;
            background: #4ade80;
            border-radius: 50%;
            margin-right: 10px;
            box-shadow: 0 0 10px #4ade80;
            animation: pulse 2s infinite;
        }

        @keyframes pulse {
            0% { transform: scale(1); opacity: 1; }
            50% { transform: scale(1.5); opacity: 0.5; }
            100% { transform: scale(1); opacity: 1; }
        }

        .type-section {
            background: white;
            border-radius: 30px;
            padding: 40px;
            margin-bottom: 40px;
            box-shadow: 0 10px 40px rgba(0,0,0,0.05);
            border: 1px solid #f0f0f0;
        }

        .table-premium {
            border-radius: 20px;
            overflow: hidden;
            border: none;
        }

        .table-premium thead th {
            background: #f8fafc;
            border: none;
            padding: 20px;
            font-weight: 700;
            color: #64748b;
            text-transform: uppercase;
            font-size: 0.8rem;
            letter-spacing: 1px;
        }

        .table-premium tbody td {
            padding: 20px;
            vertical-align: middle;
            border-top: 1px solid #f1f5f9;
        }

        .rank-badge {
            width: 40px;
            height: 40px;
            display: flex;
            align-items: center;
            justify-content: center;
            border-radius: 12px;
            font-weight: 800;
            font-size: 1.1rem;
        }

        .rank-top { background: #fef3c7; color: #d97706; }
        .rank-normal { background: #f1f5f9; color: #64748b; }

        .status-pill {
            padding: 6px 15px;
            border-radius: 50px;
            font-size: 0.85rem;
            font-weight: 600;
        }

        .status-accepted { background: #dcfce7; color: #166534; }
        .status-waiting { background: #fef3c7; color: #92400e; }
        .status-outside { background: #fee2e2; color: #991b1b; }

        .quota-card {
            background: #f8fafc;
            border-radius: 20px;
            padding: 20px;
            border-left: 5px solid var(--primary-color);
        }
    </style>
@endpush

@section('content')
    <div class="monitoring-hero animate__animated animate__fadeIn">
        <div class="container">
            <h1 class="font-weight-bold mb-3">Monitoring Hasil Seleksi</h1>
            <p class="opacity-75 h5 mb-4">{{ $activePhase->phase_name }} - TP {{ $activePhase->academicYear->year_name }}</p>
            
            <div class="refresh-indicator">
                <div class="refresh-dot"></div>
                Halaman ini diperbarui otomatis setiap 1 menit
            </div>
        </div>
    </div>

    <div class="container mb-5">
        @forelse ($admissionTypes as $type)
            @php
                $typeRegistrants = $registrants->get($type->id);
                $quota = $quotas->get($type->id) ?? 0;
            @endphp
            
            <div class="type-section" data-aos="fade-up">
                <div class="row align-items-center mb-4">
                    <div class="col-md-6">
                        <h3 class="font-weight-bold text-dark mb-0">Jalur {{ $type->admission_type_name }}</h3>
                        <p class="text-muted small mb-0">Berdasarkan skor seleksi tertinggi</p>
                    </div>
                    <div class="col-md-6 text-md-right">
                        <div class="quota-card d-inline-block text-left">
                            <span class="text-muted small d-block">Kuota Tersedia</span>
                            <span class="h4 font-weight-bold text-success mb-0">{{ $quota }} Siswa</span>
                        </div>
                    </div>
                </div>

                <div class="table-responsive">
                    <table class="table table-premium">
                        <thead>
                            <tr>
                                <th width="80">Peringkat</th>
                                <th>Nama Lengkap</th>
                                <th class="text-center">No. Pendaftaran</th>
                                <th class="text-center">Skor Seleksi</th>
                                <th class="text-center">Status Sementara</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($typeRegistrants as $index => $reg)
                                @php
                                    $rank = $index + 1;
                                    $isInside = $rank <= $quota;
                                    
                                    // Masking Nama
                                    $nameParts = explode(' ', $reg->nama_lengkap);
                                    $maskedName = $nameParts[0];
                                    if(count($nameParts) > 1) {
                                        $maskedName .= ' ' . substr($nameParts[1], 0, 1) . str_repeat('*', 5);
                                    }
                                @endphp
                                <tr>
                                    <td>
                                        <div class="rank-badge {{ $rank <= 3 ? 'rank-top' : 'rank-normal' }}">
                                            {{ $rank }}
                                        </div>
                                    </td>
                                    <td>
                                        <span class="font-weight-bold text-dark">{{ $maskedName }}</span>
                                        <div class="text-muted small">{{ $reg->asal_sekolah }}</div>
                                    </td>
                                    <td class="text-center font-weight-bold text-muted">{{ $reg->registration_number }}</td>
                                    <td class="text-center">
                                        <span class="badge badge-light px-3 py-2" style="font-size: 1rem; border-radius: 10px;">
                                            {{ number_format($reg->selection_score, 2) }}
                                        </span>
                                    </td>
                                    <td class="text-center">
                                        @if ($isInside)
                                            <span class="status-pill status-accepted">
                                                <i class="fa fa-check-circle mr-1"></i> Lolos Kuota
                                            </span>
                                        @else
                                            <span class="status-pill status-outside">
                                                <i class="fa fa-clock mr-1"></i> Luar Kuota
                                            </span>
                                        @endif
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                
                @if($typeRegistrants->count() > $quota)
                    <div class="alert alert-warning rounded-xl mt-4 border-0 shadow-sm">
                        <i class="fa fa-info-circle mr-2"></i> Siswa di peringkat <strong>>{{ $quota }}</strong> berstatus cadangan. Jika siswa di peringkat atas tidak melakukan daftar ulang, posisi Anda dapat naik.
                    </div>
                @endif
            </div>
        @empty
            <div class="text-center py-5">
                <i class="fa fa-users-slash fa-4x text-light mb-3"></i>
                <h4 class="text-muted">Belum ada data seleksi untuk gelombang ini</h4>
                <p class="text-muted">Pastikan proses verifikasi berkas sudah dilakukan oleh admin.</p>
            </div>
        @endforelse

        <div class="card border-0 rounded-xl bg-light p-4 text-center mt-5">
            <h5 class="font-weight-bold text-dark mb-2">Informasi Penting</h5>
            <p class="text-muted small mb-0">
                Peringkat ini bersifat dinamis dan dapat berubah sewaktu-waktu seiring dengan bertambahnya data pendaftar yang diverifikasi. <br>
                Keputusan akhir kelulusan tetap mengacu pada pengumuman resmi yang akan diterbitkan pada tanggal {{ $activePhase->announcement_date ? $activePhase->announcement_date->format('d M Y') : '-' }}.
            </p>
        </div>
    </div>
@endsection
