@extends('layouts.app')
@section('title', 'Peringkat & Rekap Nilai CBT')
@section('subtitle', 'CBT Madrasah Digital')

@section('content')
<div class="row">
    <div class="col-12">
        <div class="card shadow-lg border-0 mb-4" style="border-radius:25px; background: linear-gradient(135deg, #1e293b 0%, #334155 100%);">
            <div class="card-body p-4 p-md-5">
                <div class="row align-items-center">
                    <div class="col-md-8 text-white">
                        <h1 class="display-5 font-weight-bold mb-2">🏆 Papan Peringkat</h1>
                        <p class="lead opacity-80 mb-4">Analisis prestasi siswa berdasarkan hasil ujian CBT secara real-time.</p>
                        
                        <form action="{{ route('admin.cbt.ranking.index') }}" method="GET" class="row g-3">
                            <div class="col-md-5">
                                <label class="text-xs font-weight-bold text-uppercase opacity-70 mb-2 d-block">Pilih Mata Pelajaran / Ujian</label>
                                <select name="cbt_exam_id" class="form-control border-0 rounded-pill px-4 shadow-sm" onchange="this.form.submit()">
                                    <option value="">-- Ranking Global (Akumulasi) --</option>
                                    @foreach($exams as $ex)
                                        <option value="{{ $ex->id }}" {{ $selectedExam == $ex->id ? 'selected' : '' }}>
                                            {{ $ex->name }} ({{ $ex->bank->subject->name ?? '-' }})
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-4">
                                <label class="text-xs font-weight-bold text-uppercase opacity-70 mb-2 d-block">Filter Kelas</label>
                                <select name="class_group_id" class="form-control border-0 rounded-pill px-4 shadow-sm" onchange="this.form.submit()">
                                    <option value="">Semua Kelas</option>
                                    @foreach($classGroups as $cg)
                                        <option value="{{ $cg->id }}" {{ $selectedClass == $cg->id ? 'selected' : '' }}>
                                            {{ $cg->class_group }} {{ $cg->sub_class_group }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-3 d-flex align-items-end">
                                <a href="{{ route('admin.cbt.ranking.index') }}" class="btn btn-outline-light rounded-pill px-4 mb-1">
                                    <i class="fas fa-sync"></i> Reset
                                </a>
                            </div>
                        </form>
                    </div>
                    <div class="col-md-4 d-none d-md-block text-center">
                        <img src="https://illustrations.popsy.co/white/winner.svg" alt="Ranking" style="max-width: 250px;" class="animate__animated animate__zoomIn">
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-12">
        <div class="card border-0 shadow-sm" style="border-radius:20px;">
            <div class="card-header bg-white py-4 border-0 d-flex justify-content-between align-items-center">
                <h4 class="mb-0 font-weight-bold text-dark">
                    @if($selectedExam)
                        Detail Peringkat: {{ $exams->find($selectedExam)->name }}
                    @else
                        100 Besar Ranking Global
                    @endif
                </h4>
                <div class="badge badge-soft-primary px-3 py-2 rounded-pill font-weight-bold">
                    Total: {{ count($rankings) }} Siswa
                </div>
            </div>
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-hover align-items-center mb-0">
                        <thead class="bg-light">
                            <tr>
                                <th class="text-center font-weight-bold text-uppercase text-xs" width="80px">Rank</th>
                                <th class="font-weight-bold text-uppercase text-xs">Siswa</th>
                                <th class="font-weight-bold text-uppercase text-xs text-center">Kelas</th>
                                @if(!$selectedExam)
                                    <th class="font-weight-bold text-uppercase text-xs text-center">Jml Ujian</th>
                                    <th class="font-weight-bold text-uppercase text-xs text-center">Rata-rata</th>
                                @endif
                                <th class="font-weight-bold text-uppercase text-xs text-center" width="150px">Skor Akhir</th>
                                <th class="font-weight-bold text-uppercase text-xs text-center">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($rankings as $index => $rank)
                                @php $pos = $index + 1; @endphp
                                <tr>
                                    <td class="text-center">
                                        @if($pos == 1)
                                            <div class="rank-medal gold">1</div>
                                        @elseif($pos == 2)
                                            <div class="rank-medal silver">2</div>
                                        @elseif($pos == 3)
                                            <div class="rank-medal bronze">3</div>
                                        @else
                                            <span class="font-weight-bold text-muted">{{ $pos }}</span>
                                        @endif
                                    </td>
                                    <td>
                                        <div class="d-flex align-items-center">
                                            <div class="avatar-sm rounded-circle bg-soft-info mr-3 text-info font-weight-bold">
                                                {{ substr($rank->nama_lengkap ?? $rank->student->nama_lengkap, 0, 1) }}
                                            </div>
                                            <div>
                                                <h6 class="mb-0 font-weight-bold text-sm">{{ $rank->nama_lengkap ?? $rank->student->nama_lengkap }}</h6>
                                                <small class="text-muted">NISN: {{ $rank->nisn ?? $rank->student->nisn }}</small>
                                            </div>
                                        </div>
                                    </td>
                                    <td class="text-center">
                                        <span class="badge badge-pill badge-light font-weight-bold">
                                            @if(isset($rank->class_group))
                                                {{ $rank->class_group }} {{ $rank->sub_class_group }}
                                            @else
                                                {{ $rank->student->classGroup->class_group ?? '' }} {{ $rank->student->classGroup->sub_class_group ?? '-' }}
                                            @endif
                                        </span>
                                    </td>
                                    @if(!$selectedExam)
                                        <td class="text-center font-weight-bold">{{ $rank->exams_count }}</td>
                                        <td class="text-center">
                                            <span class="text-indigo-600 font-weight-bold">{{ number_format($rank->average_score, 1) }}</span>
                                        </td>
                                    @endif
                                    <td class="text-center">
                                        <h5 class="mb-0 font-weight-black text-primary">
                                            {{ number_format($rank->total_score ?? $rank->final_score, 1) }}
                                        </h5>
                                    </td>
                                    <td class="text-center">
                                        @if($selectedExam)
                                            <a href="{{ route('admin.cbt.exam.export-student-pdf', $rank->id) }}" class="btn btn-xs btn-outline-danger rounded-pill">
                                                <i class="fas fa-file-pdf mr-1"></i> Detail
                                            </a>
                                        @else
                                            <button class="btn btn-xs btn-soft-info rounded-pill" disabled>Rekap</button>
                                        @endif
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="7" class="text-center py-5 opacity-50">
                                        <i class="fas fa-trophy fa-3x mb-3"></i>
                                        <p class="font-weight-bold">Belum ada data nilai yang tersedia.</p>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
    .font-weight-black { font-weight: 900; }
    .avatar-sm { width: 36px; height: 36px; display: flex; align-items: center; justify-content: center; }
    .badge-soft-primary { background: #eef2ff; color: #4f46e5; }
    .bg-soft-info { background: #ecf9ff; color: #0ea5e9; }
    
    .rank-medal {
        width: 35px;
        height: 35px;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        margin: 0 auto;
        font-weight: 900;
        color: white;
        box-shadow: 0 4px 10px rgba(0,0,0,0.1);
    }
    .gold { background: linear-gradient(135deg, #facc15 0%, #ca8a04 100%); }
    .silver { background: linear-gradient(135deg, #cbd5e1 0%, #64748b 100%); }
    .bronze { background: linear-gradient(135deg, #fb923c 0%, #c2410c 100%); }

    .btn-premium {
        background: linear-gradient(135deg, #4f46e5 0%, #7c3aed 100%);
        color: white;
        border: none;
        transition: 0.3s;
    }
    .btn-premium:hover { transform: translateY(-2px); box-shadow: 0 10px 20px rgba(79, 70, 229, 0.3); color: white; }
</style>
@endsection
