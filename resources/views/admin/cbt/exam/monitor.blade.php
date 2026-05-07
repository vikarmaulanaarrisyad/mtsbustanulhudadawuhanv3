@extends('layouts.app')
@section('title', 'Live Monitoring: ' . $exam->name)
@section('content')
<div class="row mb-4">
    <div class="col-md-3">
        <div class="small-box bg-info">
            <div class="inner">
                <h3 id="stat-total">{{ $exam->studentExams->count() }}</h3>
                <p>Total Peserta Join</p>
            </div>
            <div class="icon"><i class="fas fa-users"></i></div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="small-box bg-warning">
            <div class="inner">
                <h3 id="stat-doing">{{ $exam->studentExams->where('status', 'doing')->count() }}</h3>
                <p>Sedang Mengerjakan</p>
            </div>
            <div class="icon"><i class="fas fa-spinner fa-spin"></i></div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="small-box bg-success">
            <div class="inner">
                <h3 id="stat-finished">{{ $exam->studentExams->where('status', 'finished')->count() }}</h3>
                <p>Selesai</p>
            </div>
            <div class="icon"><i class="fas fa-check"></i></div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="small-box bg-danger">
            <div class="inner">
                <h3>{{ $exam->token }}</h3>
                <p>TOKEN UJIAN</p>
            </div>
            <div class="icon"><i class="fas fa-key"></i></div>
        </div>
    </div>
</div>

<div class="card">
    <div class="card-header d-flex justify-content-between">
        <h3 class="card-title">Daftar Status Peserta Ujian</h3>
        <button class="btn btn-sm btn-primary" onclick="location.reload()"><i class="fas fa-sync"></i> Refresh Data</button>
    </div>
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-striped">
                <thead>
                    <tr>
                        <th>NISN</th>
                        <th>Nama Peserta</th>
                        <th>Status</th>
                        <th>Pelanggaran (Anti-Cheat)</th>
                        <th>Nilai Sementara/Akhir</th>
                        <th>Waktu Mulai</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($exam->studentExams as $se)
                        <tr>
                            <td>{{ $se->student->nisn }}</td>
                            <td>{{ $se->student->name }}</td>
                            <td>
                                @if($se->status == 'finished')
                                    <span class="badge badge-success">Selesai</span>
                                @elseif($se->status == 'doing')
                                    <span class="badge badge-warning">Sedang Mengerjakan</span>
                                @else
                                    <span class="badge badge-secondary">Belum Mulai</span>
                                @endif
                            </td>
                            <td>
                                @if($se->violation_count == 0)
                                    <span class="text-success"><i class="fas fa-check-circle"></i> Aman</span>
                                @else
                                    <span class="badge badge-danger">{{ $se->violation_count }}x Peringatan</span>
                                @endif
                            </td>
                            <td>
                                @if($se->status == 'finished')
                                    <strong class="text-primary" style="font-size: 1.2rem;">{{ $se->final_score }}</strong>
                                @else
                                    <span class="text-muted">Proses...</span>
                                @endif
                            </td>
                            <td>{{ $se->start_time ? $se->start_time->format('H:i:s') : '-' }}</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="text-center">Belum ada peserta yang memasukkan Token ujian.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
