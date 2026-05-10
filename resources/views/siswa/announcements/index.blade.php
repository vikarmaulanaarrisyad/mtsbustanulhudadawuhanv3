@extends('layouts.ppdb')

@section('title', 'Pengumuman Sekolah')

@section('content')
<div class="dashboard-wrapper pb-20">
    <!-- TOP HEADER SECTION -->
    <div class="header-banner bg-grad-indigo pt-12 pb-32 px-6 relative overflow-hidden">
        <div class="max-w-7xl mx-auto relative z-10">
            <div class="flex flex-col md:flex-row justify-between items-center space-y-8 md:space-y-0">
                <div class="flex items-center space-x-6">
                    <a href="{{ route('siswa.dashboard') }}" class="w-12 h-12 bg-white/10 backdrop-blur-md rounded-2xl flex items-center justify-center text-white hover:bg-white/20 transition-all shadow-xl border border-white/10">
                        <i class="fas fa-arrow-left"></i>
                    </a>
                    <div class="text-white">
                        <span class="bg-rose-500/40 backdrop-blur-md text-[9px] font-black px-4 py-1.5 rounded-full uppercase tracking-widest border border-white/20 shadow-lg mb-2 inline-block">Informasi</span>
                        <h1 class="text-3xl md:text-5xl font-black tracking-tighter leading-none">Mading Digital</h1>
                    </div>
                </div>
            </div>
        </div>
        <!-- Animated Background Elements -->
        <div class="absolute right-[-100px] top-[-100px] w-96 h-96 bg-white/10 rounded-full blur-[100px] animate-pulse"></div>
        <div class="absolute left-[-50px] bottom-[-50px] w-64 h-64 bg-rose-500/20 rounded-full blur-[80px]"></div>
    </div>

    <!-- MAIN CONTENT AREA -->
    <div class="max-w-4xl mx-auto px-6 -mt-16 relative z-20">
        <div class="space-y-8">
            @forelse($announcements as $ann)
                <div class="bg-white rounded-[3rem] p-10 shadow-2xl shadow-slate-200/50 border border-slate-50 overflow-hidden relative group hover:-translate-y-2 transition-all duration-500 cursor-pointer" onclick='showAnnouncement(@json($ann))'>
                    <div class="flex items-center justify-between mb-8">
                        <div class="flex items-center space-x-4">
                            <div class="w-14 h-14 bg-rose-50 text-rose-600 rounded-2xl flex items-center justify-center shadow-inner">
                                <i class="fas fa-bullhorn text-xl"></i>
                            </div>
                            <div>
                                <span class="bg-rose-100 text-rose-700 text-[9px] font-black px-3 py-1 rounded-lg uppercase tracking-widest border border-rose-200 mb-1 inline-block">
                                    {{ $ann->type }}
                                </span>
                                <h4 class="text-xl font-black text-slate-800 tracking-tight">{{ $ann->title }}</h4>
                            </div>
                        </div>
                        <div class="text-right">
                            <span class="block text-[9px] font-black text-slate-400 uppercase tracking-widest mb-1">Diposting</span>
                            <span class="text-xs font-black text-slate-700 uppercase">{{ $ann->created_at->translatedFormat('d F Y') }}</span>
                        </div>
                    </div>

                    <div class="text-slate-500 text-sm leading-relaxed line-clamp-3 mb-8">
                        {!! $ann->content !!}
                    </div>

                    <div class="flex items-center justify-between pt-8 border-t border-slate-50">
                        <div class="flex items-center space-x-2 text-[10px] font-black text-slate-400 uppercase tracking-widest">
                            <i class="far fa-clock"></i>
                            <span>{{ $ann->created_at->diffForHumans() }}</span>
                        </div>
                        <span class="text-indigo-600 text-xs font-black uppercase tracking-widest flex items-center">
                            Baca Selengkapnya <i class="fas fa-chevron-right ml-2 text-[10px]"></i>
                        </span>
                    </div>

                    <!-- Subtle background decoration -->
                    <div class="absolute right-0 top-0 p-10 opacity-[0.02] group-hover:opacity-[0.05] transition-opacity">
                        <i class="fas fa-newspaper fa-6x"></i>
                    </div>
                </div>
            @empty
                <div class="bg-white rounded-[3rem] p-20 shadow-2xl shadow-slate-200/50 border border-slate-50 text-center">
                    <div class="w-24 h-24 bg-slate-50 text-slate-200 rounded-[2.5rem] flex items-center justify-center mx-auto mb-8 shadow-inner">
                        <i class="fas fa-bell-slash text-4xl"></i>
                    </div>
                    <h4 class="text-xl font-black text-slate-800 mb-2">Tidak Ada Pengumuman</h4>
                    <p class="text-sm text-slate-400 max-w-sm mx-auto">Saat ini belum ada informasi atau pengumuman terbaru dari sekolah untuk Anda.</p>
                </div>
            @endforelse

            <div class="mt-10">
                {{ $announcements->links() }}
            </div>
        </div>
    </div>
</div>

<style>
    body { background-color: #f8fafc; font-family: 'Outfit', sans-serif; }
    .bg-grad-indigo { background: linear-gradient(135deg, #1e1b4b 0%, #312e81 100%); }
    .pagination { justify-content: center; }
    .page-item .page-link { border-radius: 12px; margin: 0 5px; border: none; font-weight: 800; color: #64748b; }
    .page-item.active .page-link { background-color: #4f46e5; color: white; }
</style>
@endsection

@push('scripts')
<script>
    function showAnnouncement(ann) {
        Swal.fire({
            title: ann.title,
            html: `<div class="text-left text-sm leading-relaxed text-slate-600 font-medium">${ann.content}</div>`,
            confirmButtonText: 'MENGERTI',
            confirmButtonColor: '#4F46E5',
            customClass: { popup: 'rounded-[2.5rem]', title: 'font-black tracking-tight' }
        });
    }
</script>
@endpush
