@extends($layout)

@section('title', 'Ranking Siswa')
@section('subtitle', 'Laporan')

@section('content')
<div class="row">
    <!-- FILTERS -->
    <div class="col-12 mb-4 animate__animated animate__fadeInDown">
        <div class="card shadow-sm border-0 premium-card">
            <div class="card-body p-3">
                <form action="" method="GET" class="row align-items-end">
                    <div class="col-md-4">
                        <div class="form-group mb-0">
                            <label class="text-xs font-weight-bold text-muted uppercase">Tahun Pelajaran</label>
                            <select name="academic_year_id" class="form-control rounded-pill border-2" required>
                                @foreach($academicYears as $ay)
                                    <option value="{{ $ay->id }}" {{ $academic_year_id == $ay->id ? 'selected' : '' }}>{{ $ay->academic_year }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="form-group mb-0">
                            <label class="text-xs font-weight-bold text-muted uppercase">Pilih Rombel (Kelas)</label>
                            <select name="class_group_id" class="form-control rounded-pill border-2" required>
                                <option value="">-- Pilih Kelas --</option>
                                @foreach($classGroups as $cg)
                                    <option value="{{ $cg->id }}" {{ $class_group_id == $cg->id ? 'selected' : '' }}>{{ $cg->kelas_lengkap }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <button type="submit" class="btn btn-primary btn-block rounded-pill font-weight-bold shadow-primary py-2">
                            <i class="fas fa-sync-alt mr-2"></i> TAMPILKAN RANKING
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- RANKING TABLE -->
    <div class="col-12 animate__animated animate__fadeInUp">
        <div class="card shadow-sm border-0 premium-card">
            <div class="card-header bg-white border-0 py-4 d-flex justify-content-between align-items-center">
                <div>
                    <h4 class="mb-1 font-weight-bold text-dark">Peringkat Prestasi Siswa</h4>
                    <p class="text-muted text-sm mb-0">Dihitung berdasarkan nilai rata-rata seluruh mata pelajaran.</p>
                </div>
                <div class="btn-group">
                    <a href="{{ route('admin.analytics.ranking.export', ['class_group_id' => $class_group_id, 'academic_year_id' => $academic_year_id]) }}" class="btn btn-outline-success btn-sm rounded-pill px-4 font-weight-bold mr-2 {{ !count($rankings) ? 'disabled' : '' }}">
                        <i class="fas fa-file-excel mr-2"></i> EXCEL
                    </a>
                    <button type="button" class="btn btn-outline-primary btn-sm rounded-pill px-4 font-weight-bold">
                        <i class="fas fa-file-word mr-2"></i> WORD
                    </button>
                </div>
            </div>
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0" id="table-ranking">
                        <thead class="bg-light-soft text-uppercase">
                            <tr>
                                <th width="80px" class="text-center py-3">Peringkat</th>
                                <th>NIS / Nama Siswa</th>
                                <th class="text-center">Kehadiran</th>
                                <th class="text-center">Rata-rata Nilai</th>
                                <th class="text-center">Total Skor</th>
                                <th width="150px">Keterangan</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($rankings as $index => $r)
                                <tr>
                                    <td class="text-center">
                                        @if($index == 0)
                                            <span class="badge badge-warning p-2" style="border-radius: 50%; width: 35px; height: 35px; line-height: 20px;">
                                                <i class="fas fa-crown"></i>
                                            </span>
                                        @elseif($index == 1)
                                            <span class="badge badge-secondary p-2" style="border-radius: 50%; width: 30px; height: 30px; line-height: 15px;">2</span>
                                        @elseif($index == 2)
                                            <span class="badge badge-light border p-2" style="border-radius: 50%; width: 30px; height: 30px; line-height: 15px;">3</span>
                                        @else
                                            <span class="text-muted font-weight-bold">{{ $index + 1 }}</span>
                                        @endif
                                    </td>
                                    <td>
                                        <div class="d-flex align-items-center">
                                            <div class="avatar bg-soft-primary text-primary rounded-circle mr-3 d-flex align-items-center justify-content-center" style="width: 40px; height: 40px;">
                                                {{ substr($r['student']->nama_lengkap, 0, 1) }}
                                            </div>
                                            <div>
                                                <h6 class="mb-0 font-weight-bold text-dark">{{ $r['student']->nama_lengkap }}</h6>
                                                <small class="text-muted">{{ $r['student']->nis }}</small>
                                            </div>
                                        </div>
                                    </td>
                                    <td class="text-center">
                                        <div class="progress progress-xs mb-1 bg-light" style="width: 100px; margin: 0 auto;">
                                            <div class="progress-bar bg-success" style="width: {{ $r['attendance_rate'] }}%"></div>
                                        </div>
                                        <small class="font-weight-bold text-success">{{ $r['attendance_rate'] }}%</small>
                                    </td>
                                    <td class="text-center">
                                        <h5 class="mb-0 font-weight-bold text-primary">{{ $r['avg_grade'] }}</h5>
                                    </td>
                                    <td class="text-center text-muted font-weight-bold">{{ number_format($r['total_score']) }}</td>
                                    <td>
                                        @if($r['avg_grade'] >= 85)
                                            <span class="badge badge-soft-success">Sangat Memuaskan</span>
                                        @elseif($r['avg_grade'] >= 75)
                                            <span class="badge badge-soft-primary">Memuaskan</span>
                                        @else
                                            <span class="badge badge-soft-warning">Cukup</span>
                                        @endif
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="6" class="text-center py-5 text-muted italic">
                                        <i class="fas fa-info-circle mr-2"></i> Silakan pilih Kelas dan Tahun Pelajaran untuk menampilkan ranking.
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
    .premium-card { border-radius: 20px; }
    .bg-light-soft { background: #f8fafc; color: #64748b; font-size: 0.7rem; letter-spacing: 1px; }
    .bg-soft-primary { background: #e0e7ff; }
    .shadow-primary { box-shadow: 0 4px 15px rgba(78,115,223,0.3); }
    #table-ranking td { padding: 1.25rem 1rem; border-bottom: 1px solid #f1f5f9; vertical-align: middle; }
    .badge-soft-success { background: #dcfce7; color: #15803d; }
    .badge-soft-primary { background: #e0e7ff; color: #4338ca; }
    .badge-soft-warning { background: #fef9c3; color: #a16207; }
</style>
@endsection
