@extends('layouts.teacher')

@section('content')
<div class="min-h-screen pb-32 bg-slate-50">
    <!-- HEADER SECTION -->
    <div class="bg-grad-indigo pt-12 pb-32 px-6 relative overflow-hidden">
        <div class="max-w-7xl mx-auto relative z-10 text-white">
            <div class="flex items-center justify-between mb-8">
                <a href="{{ route('guru.dashboard') }}" class="w-12 h-12 bg-white/10 backdrop-blur-md rounded-2xl flex items-center justify-center border border-white/10 hover:bg-white/20 transition-all">
                    <i class="fas fa-arrow-left"></i>
                </a>
                <h1 class="text-xl font-black tracking-tight uppercase">Statistik Presensi</h1>
                <div class="w-12"></div>
            </div>

            <div class="text-center mb-8">
                <p class="text-indigo-200 text-xs font-black uppercase tracking-[0.2em] mb-2">Periode Laporan</p>
                <div class="flex items-center justify-center space-x-3">
                    <h2 class="text-3xl font-black leading-none">
                        {{ \Carbon\Carbon::create()->month($month)->translatedFormat('F') }} {{ $year }}
                    </h2>
                </div>
            </div>

            <!-- STATS GRID -->
            <div class="grid grid-cols-3 gap-4">
                <div class="bg-white/10 backdrop-blur-md rounded-3xl p-4 border border-white/10 text-center animate__animated animate__fadeInUp" style="animation-delay: 0.1s">
                    <p class="text-[9px] font-black uppercase text-indigo-200 mb-1">Hadir (%)</p>
                    <p class="text-xl font-black">{{ $stats['attendance_rate'] }}%</p>
                </div>
                <div class="bg-white/10 backdrop-blur-md rounded-3xl p-4 border border-white/10 text-center animate__animated animate__fadeInUp" style="animation-delay: 0.2s">
                    <p class="text-[9px] font-black uppercase text-indigo-200 mb-1">Terlambat</p>
                    <p class="text-xl font-black text-amber-400">{{ $stats['late'] }}</p>
                </div>
                <div class="bg-white/10 backdrop-blur-md rounded-3xl p-4 border border-white/10 text-center animate__animated animate__fadeInUp" style="animation-delay: 0.3s">
                    <p class="text-[9px] font-black uppercase text-indigo-200 mb-1">Total Jam</p>
                    <p class="text-xl font-black text-emerald-400">{{ $stats['total_hours'] }}h</p>
                </div>
            </div>
        </div>

        <!-- Decoration -->
        <div class="absolute right-[-50px] top-[-50px] w-64 h-64 bg-white/5 rounded-full blur-3xl"></div>
        <div class="absolute left-[-30px] bottom-[-30px] w-48 h-48 bg-indigo-400/10 rounded-full blur-2xl"></div>
    </div>

    <!-- MAIN CONTENT -->
    <div class="max-w-7xl mx-auto px-6 -mt-16 relative z-20">
        
        <!-- CHART SECTION -->
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-8">
            <!-- STATUS DISTRIBUTION -->
            <div class="bg-white rounded-[2.5rem] p-8 shadow-2xl border border-slate-100 flex flex-col items-center">
                <h3 class="text-xs font-black text-slate-400 uppercase tracking-widest mb-8 self-start">Distribusi Status</h3>
                <div class="w-full max-w-[200px] aspect-square relative">
                    <canvas id="statusChart"></canvas>
                </div>
                <div class="grid grid-cols-2 gap-4 w-full mt-8">
                    <div class="flex items-center space-x-2">
                        <div class="w-3 h-3 rounded-full bg-emerald-500"></div>
                        <span class="text-[10px] font-bold text-slate-500">Tepat Waktu ({{ $stats['present'] }})</span>
                    </div>
                    <div class="flex items-center space-x-2">
                        <div class="w-3 h-3 rounded-full bg-amber-500"></div>
                        <span class="text-[10px] font-bold text-slate-500">Terlambat ({{ $stats['late'] }})</span>
                    </div>
                    <div class="flex items-center space-x-2">
                        <div class="w-3 h-3 rounded-full bg-rose-500"></div>
                        <span class="text-[10px] font-bold text-slate-500">Alpa ({{ $stats['absent'] }})</span>
                    </div>
                    <div class="flex items-center space-x-2">
                        <div class="w-3 h-3 rounded-full bg-indigo-500"></div>
                        <span class="text-[10px] font-bold text-slate-500">Izin/Sakit ({{ $stats['permit'] + $stats['sick'] }})</span>
                    </div>
                </div>
            </div>

            <!-- WORKING HOURS TREND -->
            <div class="bg-white rounded-[2.5rem] p-8 shadow-2xl border border-slate-100">
                <h3 class="text-xs font-black text-slate-400 uppercase tracking-widest mb-8">Tren Jam Kerja (Harian)</h3>
                <div class="w-full h-[250px]">
                    <canvas id="hoursChart"></canvas>
                </div>
            </div>
        </div>

        <!-- FILTER SECTION -->
        <div class="bg-white rounded-[2rem] p-6 shadow-xl border border-slate-100 mb-8">
            <form action="{{ route('guru.attendance.report') }}" method="GET" class="flex items-center space-x-4">
                <div class="flex-1">
                    <select name="month" class="w-full bg-slate-50 border-0 rounded-2xl px-4 py-3 text-sm font-bold text-slate-700 focus:ring-2 focus:ring-indigo-500">
                        @foreach(range(1, 12) as $m)
                            <option value="{{ $m }}" {{ $month == $m ? 'selected' : '' }}>
                                {{ \Carbon\Carbon::create()->month($m)->translatedFormat('F') }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div class="flex-1">
                    <select name="year" class="w-full bg-slate-50 border-0 rounded-2xl px-4 py-3 text-sm font-bold text-slate-700 focus:ring-2 focus:ring-indigo-500">
                        @foreach(range(date('Y')-2, date('Y')) as $y)
                            <option value="{{ $y }}" {{ $year == $y ? 'selected' : '' }}>{{ $y }}</option>
                        @endforeach
                    </select>
                </div>
                <button type="submit" class="w-12 h-12 bg-indigo-600 text-white rounded-2xl flex items-center justify-center shadow-lg shadow-indigo-200">
                    <i class="fas fa-search"></i>
                </button>
            </form>
        </div>

        <!-- TABLE SECTION -->
        <div class="bg-white rounded-[2.5rem] shadow-2xl border border-slate-100 overflow-hidden">
            <div class="px-8 py-6 border-b border-slate-100 flex items-center justify-between">
                <h3 class="text-sm font-black text-slate-700 uppercase tracking-widest">Detail Kehadiran</h3>
                <span class="bg-slate-100 text-[10px] font-black px-3 py-1 rounded-full text-slate-500">{{ $attendances->count() }} Data</span>
            </div>
            <div class="overflow-x-auto">
                <table class="w-full">
                    <thead>
                        <tr class="bg-slate-50/50">
                            <th class="px-8 py-4 text-left text-[10px] font-black text-slate-400 uppercase tracking-widest">Tanggal</th>
                            <th class="px-8 py-4 text-left text-[10px] font-black text-slate-400 uppercase tracking-widest">Masuk</th>
                            <th class="px-8 py-4 text-left text-[10px] font-black text-slate-400 uppercase tracking-widest">Pulang</th>
                            <th class="px-8 py-4 text-left text-[10px] font-black text-slate-400 uppercase tracking-widest">Durasi</th>
                            <th class="px-8 py-4 text-left text-[10px] font-black text-slate-400 uppercase tracking-widest text-center">Status</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100 text-sm font-bold text-slate-600">
                        @forelse($attendances as $att)
                        <tr class="hover:bg-slate-50 transition-all group">
                            <td class="px-8 py-5">
                                <span class="block text-slate-700 leading-none mb-1">{{ $att->date->translatedFormat('d F Y') }}</span>
                                <span class="text-[9px] text-slate-400 font-black uppercase">{{ $att->date->translatedFormat('l') }}</span>
                            </td>
                            <td class="px-8 py-5">
                                <span class="{{ $att->status == 'late' ? 'text-amber-500' : 'text-slate-600' }}">
                                    {{ $att->check_in ? \Carbon\Carbon::parse($att->check_in)->format('H:i') : '--:--' }}
                                </span>
                            </td>
                            <td class="px-8 py-5">
                                <span>{{ $att->check_out ? \Carbon\Carbon::parse($att->check_out)->format('H:i') : '--:--' }}</span>
                            </td>
                            <td class="px-8 py-5">
                                @if($att->check_in && $att->check_out)
                                    @php
                                        $in = \Carbon\Carbon::parse($att->check_in);
                                        $out = \Carbon\Carbon::parse($att->check_out);
                                        $diff = $out->diff($in);
                                    @endphp
                                    <span class="text-indigo-600">{{ $diff->h }}j {{ $diff->i }}m</span>
                                @else
                                    <span class="text-slate-300">-</span>
                                @endif
                            </td>
                            <td class="px-8 py-5 text-center">
                                <span class="inline-block px-4 py-1.5 rounded-xl text-[9px] font-black uppercase tracking-widest border
                                    @if($att->status == 'present') bg-emerald-50 text-emerald-600 border-emerald-100
                                    @elseif($att->status == 'late') bg-amber-50 text-amber-600 border-amber-100
                                    @elseif($att->status == 'absent') bg-rose-50 text-rose-600 border-rose-100
                                    @else bg-indigo-50 text-indigo-600 border-indigo-100
                                    @endif
                                ">
                                    {{ $att->status_label }}
                                </span>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="5" class="px-8 py-20 text-center">
                                <div class="w-20 h-20 bg-slate-100 rounded-full flex items-center justify-center mx-auto mb-4 text-slate-300 text-2xl">
                                    <i class="fas fa-calendar-times"></i>
                                </div>
                                <p class="text-slate-400 font-bold uppercase tracking-widest text-xs">Belum ada data presensi periode ini</p>
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<style>
    .bg-grad-indigo { background: linear-gradient(135deg, #1e1b4b 0%, #312e81 100%); }
</style>
@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // STATUS CHART
        const statusCtx = document.getElementById('statusChart').getContext('2d');
        new Chart(statusCtx, {
            type: 'doughnut',
            data: {
                labels: ['Hadir', 'Terlambat', 'Alpa', 'Izin', 'Sakit'],
                datasets: [{
                    data: @json($chartData['status_counts']),
                    backgroundColor: ['#10b981', '#f59e0b', '#f43f5e', '#6366f1', '#8b5cf6'],
                    borderWidth: 0,
                    hoverOffset: 10
                }]
            },
            options: {
                cutout: '80%',
                plugins: { legend: { display: false } },
                responsive: true,
                maintainAspectRatio: true
            }
        });

        // HOURS TREND CHART
        const hoursCtx = document.getElementById('hoursChart').getContext('2d');
        new Chart(hoursCtx, {
            type: 'bar',
            data: {
                labels: @json($chartData['labels']),
                datasets: [{
                    label: 'Jam Kerja',
                    data: @json($chartData['hours']),
                    backgroundColor: 'rgba(99, 102, 241, 0.2)',
                    borderColor: '#6366f1',
                    borderWidth: 2,
                    borderRadius: 8,
                    maxBarThickness: 15
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: { legend: { display: false } },
                scales: {
                    y: {
                        beginAtZero: true,
                        grid: { display: true, color: '#f1f5f9' },
                        ticks: { font: { size: 10, weight: 'bold' }, color: '#94a3b8' }
                    },
                    x: {
                        grid: { display: false },
                        ticks: { font: { size: 10, weight: 'bold' }, color: '#94a3b8' }
                    }
                }
            }
        });
    });
</script>
@endpush
