@extends('layouts.ppdb')

@section('title', 'Ujian Berbasis Komputer (CBT)')

@section('content')
<div class="px-6 py-8 md:px-12 md:py-10 max-w-7xl mx-auto space-y-8">
    <div class="flex flex-col md:flex-row md:items-center justify-between gap-6">
        <div>
            <h1 class="text-3xl font-black text-slate-800 tracking-tight mb-2">Ujian Berbasis Komputer</h1>
            <p class="text-slate-500 font-medium">Sistem ujian madrasah digital terintegrasi.</p>
        </div>
    </div>

    @if(session('error'))
        <div class="bg-rose-50 border border-rose-200 text-rose-700 px-6 py-4 rounded-2xl font-bold flex items-center">
            <i class="fas fa-exclamation-circle mr-3 text-xl"></i> {{ session('error') }}
        </div>
    @endif
    
    @if(session('success'))
        <div class="bg-emerald-50 border border-emerald-200 text-emerald-700 px-6 py-4 rounded-2xl font-bold flex items-center">
            <i class="fas fa-check-circle mr-3 text-xl"></i> {{ session('success') }}
        </div>
    @endif

    <div class="bg-white rounded-[2.5rem] p-8 shadow-xl shadow-slate-200/50 border border-slate-100">
        <h4 class="text-xl font-black text-slate-800 uppercase tracking-widest mb-6">Jadwal Ujian Hari Ini</h4>
        
        @if($activeExams->isEmpty())
            <div class="text-center py-12">
                <div class="w-20 h-20 bg-slate-50 rounded-full flex items-center justify-center mx-auto mb-4">
                    <i class="fas fa-calendar-times text-2xl text-slate-400"></i>
                </div>
                <h5 class="text-lg font-bold text-slate-700">Tidak Ada Ujian Aktif</h5>
                <p class="text-slate-500">Belum ada jadwal ujian untuk kelas Anda hari ini.</p>
            </div>
        @else
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                @foreach($activeExams as $exam)
                    @php
                        $studentExam = $exam->studentExams->first();
                        $status = $studentExam ? $studentExam->status : 'not_started';
                    @endphp
                    <div class="border-2 border-slate-100 rounded-3xl p-6 hover:border-indigo-300 transition-colors bg-slate-50/50">
                        <div class="flex justify-between items-start mb-4">
                            <div>
                                <h5 class="font-black text-lg text-slate-800">{{ $exam->name }}</h5>
                                <span class="text-sm font-bold text-indigo-600 bg-indigo-50 px-3 py-1 rounded-lg inline-block mt-1">{{ $exam->bank->subject->name ?? 'Mapel' }}</span>
                            </div>
                            @if($status === 'finished')
                                <span class="bg-emerald-100 text-emerald-700 px-3 py-1 rounded-lg text-xs font-bold uppercase tracking-wider">Selesai</span>
                            @elseif($status === 'doing')
                                <span class="bg-amber-100 text-amber-700 px-3 py-1 rounded-lg text-xs font-bold uppercase tracking-wider">Sedang Dikerjakan</span>
                            @else
                                <span class="bg-slate-200 text-slate-600 px-3 py-1 rounded-lg text-xs font-bold uppercase tracking-wider">Belum Mulai</span>
                            @endif
                        </div>

                        <div class="space-y-2 mb-6">
                            <div class="flex justify-between text-sm">
                                <span class="text-slate-500 font-medium">Waktu Ujian</span>
                                <span class="text-slate-800 font-bold"><i class="far fa-clock mr-1 text-slate-400"></i> {{ \Carbon\Carbon::parse($exam->start_time)->format('H:i') }} - {{ \Carbon\Carbon::parse($exam->end_time)->format('H:i') }}</span>
                            </div>
                            <div class="flex justify-between text-sm">
                                <span class="text-slate-500 font-medium">Durasi</span>
                                <span class="text-slate-800 font-bold"><i class="fas fa-stopwatch mr-1 text-slate-400"></i> {{ $exam->duration_minutes }} Menit</span>
                            </div>
                            <div class="flex justify-between text-sm">
                                <span class="text-slate-500 font-medium">Jumlah Soal</span>
                                <span class="text-slate-800 font-bold"><i class="fas fa-list-ol mr-1 text-slate-400"></i> {{ $exam->bank->questions->count() ?? 0 }} Butir</span>
                            </div>
                            @if($status === 'finished' && $studentExam)
                                <div class="flex justify-between text-sm pt-2 border-t border-slate-200 mt-2">
                                    <span class="text-slate-500 font-medium">Nilai Anda</span>
                                    <span class="text-emerald-600 font-black text-lg">{{ $studentExam->final_score }}</span>
                                </div>
                            @endif
                        </div>

                        @if($status !== 'finished')
                            <form action="{{ route('student.cbt.join', $exam->id) }}" method="POST" class="flex gap-3">
                                @csrf
                                <input type="text" name="token" placeholder="Masukkan Token" required class="flex-1 bg-white border border-slate-300 text-slate-900 text-sm rounded-xl focus:ring-indigo-500 focus:border-indigo-500 block w-full p-2.5 font-bold uppercase" autocomplete="off" maxlength="6">
                                <button type="submit" class="text-white bg-indigo-600 hover:bg-indigo-700 focus:ring-4 focus:outline-none focus:ring-indigo-300 font-bold rounded-xl text-sm px-5 py-2.5 text-center transition-colors">Masuk</button>
                            </form>
                        @endif
                    </div>
                @endforeach
            </div>
        @endif
    </div>
</div>
@endsection
