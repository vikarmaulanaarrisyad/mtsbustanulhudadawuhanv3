@extends($layout)

@section('title', 'Pengumuman Madrasah')

@section('content')
<div class="min-h-screen bg-slate-50 pb-24">
    <!-- Premium Header -->
    <div class="bg-indigo-600 pt-12 pb-24 px-6 rounded-b-[3.5rem] shadow-2xl relative overflow-hidden">
        <div class="absolute -right-10 -top-10 w-48 h-48 bg-white/10 rounded-full blur-3xl"></div>
        <div class="absolute left-10 bottom-0 w-32 h-32 bg-indigo-400/20 rounded-full blur-2xl"></div>
        
        <div class="flex items-center justify-between relative z-10 mb-8">
            <div class="flex items-center space-x-4">
                <a href="{{ route('dashboard') }}" class="w-10 h-10 bg-white/20 backdrop-blur-md rounded-xl flex items-center justify-center text-white border border-white/30 active:scale-90 transition-all">
                    <i class="fas fa-chevron-left text-sm"></i>
                </a>
                <div>
                    <p class="text-indigo-100 text-[10px] font-black uppercase tracking-widest opacity-80">Informasi Internal</p>
                    <h1 class="text-white text-xl font-black leading-tight">Pengumuman</h1>
                </div>
            </div>
            <div class="w-10 h-10 bg-white/20 backdrop-blur-md rounded-xl flex items-center justify-center text-white border border-white/30">
                <i class="fas fa-bullhorn text-sm"></i>
            </div>
        </div>
    </div>

    <!-- Announcement List -->
    <div class="px-6 -mt-12 relative z-20">
        <div class="space-y-4">
            @forelse($announcements as $item)
                @php $isRead = $item->isReadBy(auth()->id()); @endphp
                <div onclick="showAnnouncement({{ $item->id }})" class="bg-white rounded-[2rem] p-5 shadow-xl shadow-slate-200/50 border border-slate-50 relative overflow-hidden group active:scale-[0.98] transition-all cursor-pointer">
                    @if(!$isRead)
                        <div class="absolute top-0 right-0 w-16 h-16 bg-rose-500 text-white flex items-center justify-center rotate-45 translate-x-8 -translate-y-8 shadow-lg">
                            <span class="text-[8px] font-black uppercase tracking-tighter -rotate-45 -translate-y-2 translate-x-1">Baru</span>
                        </div>
                    @endif

                    <div class="flex items-start space-x-4">
                        <div class="w-12 h-12 {{ $item->type == 'Guru' ? 'bg-indigo-50 text-indigo-500' : 'bg-emerald-50 text-emerald-500' }} rounded-2xl flex items-center justify-center shadow-sm border border-slate-100 flex-shrink-0">
                            <i class="fas {{ $item->type == 'Guru' ? 'fa-user-tie' : 'fa-globe' }} text-lg"></i>
                        </div>
                        <div class="flex-1 pr-4">
                            <div class="flex items-center space-x-2 mb-1">
                                <span class="text-[8px] font-black uppercase tracking-widest {{ $item->type == 'Guru' ? 'text-indigo-400' : 'text-emerald-400' }}">{{ $item->type }}</span>
                                <span class="w-1 h-1 bg-slate-300 rounded-full"></span>
                                <span class="text-[8px] text-slate-400 font-bold uppercase tracking-widest">{{ $item->created_at->translatedFormat('d M Y') }}</span>
                            </div>
                            <h3 class="text-slate-800 font-black text-sm leading-tight mb-2 group-hover:text-indigo-600 transition-colors">{{ $item->title }}</h3>
                            <p class="text-slate-400 text-[10px] font-medium line-clamp-2 leading-relaxed">
                                {{ Str::limit(strip_tags($item->content), 80) }}
                            </p>
                        </div>
                    </div>
                </div>
            @empty
                <div class="text-center py-20 bg-white rounded-[3rem] border-2 border-dashed border-slate-100 shadow-inner">
                    <div class="w-20 h-20 bg-slate-50 rounded-full flex items-center justify-center mx-auto mb-4">
                        <i class="fas fa-comment-slash text-3xl text-slate-200"></i>
                    </div>
                    <p class="text-slate-400 font-black text-[10px] uppercase tracking-widest">Belum ada pengumuman</p>
                </div>
            @endforelse
        </div>
    </div>
</div>

<!-- Announcement Detail Modal -->
<div class="modal fade" id="announcementModal" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered px-4" role="document">
        <div class="modal-content border-0 rounded-[2.5rem] shadow-2xl overflow-hidden">
            <div class="modal-header border-0 p-6 pb-2 flex justify-between items-center">
                <div class="flex items-center space-x-2">
                    <span id="modal-badge" class="px-2 py-0.5 rounded-md text-[8px] font-black uppercase tracking-widest"></span>
                    <span id="modal-date" class="text-[9px] text-slate-400 font-black uppercase tracking-widest"></span>
                </div>
                <button type="button" class="w-9 h-9 bg-slate-50 rounded-full flex items-center justify-center text-slate-400" data-dismiss="modal">
                    <i class="fas fa-times text-sm"></i>
                </button>
            </div>
            <div class="modal-body p-6 pt-2">
                <h2 id="modal-title" class="text-slate-800 font-black text-lg leading-tight mb-4 tracking-tight"></h2>
                <div id="modal-content" class="text-slate-500 text-sm leading-relaxed space-y-3 prose prose-slate max-w-none">
                    <!-- Loaded via JS -->
                </div>
                <div class="mt-8">
                    <button class="w-full bg-slate-800 text-white font-black py-4 rounded-2xl shadow-lg active:scale-95 transition-all uppercase text-[10px] tracking-widest" data-dismiss="modal">Tutup</button>
                </div>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
    function showAnnouncement(id) {
        Swal.fire({
            title: 'Memuat...',
            allowOutsideClick: false,
            didOpen: () => { Swal.showLoading() }
        });

        $.get('{{ url("admin/announcements") }}/' + id)
            .done(response => {
                const data = response.data;
                Swal.close();
                
                $('#modal-title').text(data.title);
                $('#modal-content').html(data.content);
                $('#modal-date').text(new Date(data.created_at).toLocaleDateString('id-ID', { day: 'numeric', month: 'long', year: 'numeric' }));
                
                const badge = $('#modal-badge');
                if (data.type === 'Guru') {
                    badge.attr('class', 'px-2 py-0.5 bg-indigo-50 text-indigo-500 rounded-md text-[8px] font-black uppercase tracking-widest').text('Guru');
                } else {
                    badge.attr('class', 'px-2 py-0.5 bg-emerald-50 text-emerald-500 rounded-md text-[8px] font-black uppercase tracking-widest').text('Umum');
                }

                $('#announcementModal').modal('show');
                
                // Reload page when modal closed to update unread status if it was unread
                $('#announcementModal').on('hidden.bs.modal', function () {
                    // Optimized: only reload if it was unread
                    window.location.reload();
                });
            })
            .fail(() => {
                Swal.fire({ icon: 'error', title: 'Gagal', text: 'Tidak dapat memuat pengumuman' });
            });
    }
</script>
@endpush
@endsection
