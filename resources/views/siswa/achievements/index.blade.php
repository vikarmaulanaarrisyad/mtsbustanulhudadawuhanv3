@extends('layouts.ppdb')

@section('title', 'Prestasi Saya')

@section('content')
<div class="dashboard-wrapper pb-20">
    <!-- TOP HEADER SECTION -->
    <div class="header-banner bg-grad-warning pt-12 pb-32 px-6 relative overflow-hidden">
        <div class="max-w-7xl mx-auto relative z-10">
            <div class="flex flex-col md:flex-row justify-between items-center space-y-8 md:space-y-0">
                <div class="flex items-center space-x-6">
                    <a href="{{ route('siswa.dashboard') }}" class="w-12 h-12 bg-white/10 backdrop-blur-md rounded-2xl flex items-center justify-center text-white hover:bg-white/20 transition-all shadow-xl border border-white/10">
                        <i class="fas fa-arrow-left"></i>
                    </a>
                    <div class="text-white">
                        <span class="bg-amber-500/40 backdrop-blur-md text-[9px] font-black px-4 py-1.5 rounded-full uppercase tracking-widest border border-white/20 shadow-lg mb-2 inline-block">Portofolio</span>
                        <h1 class="text-3xl md:text-5xl font-black tracking-tighter leading-none">Prestasi Saya</h1>
                    </div>
                </div>
                <button onclick="openUploadModal()" class="px-8 py-4 bg-white text-amber-600 rounded-2xl font-black text-xs uppercase tracking-widest shadow-2xl hover:-translate-y-1 transition-all flex items-center">
                    <i class="fas fa-plus-circle mr-3"></i> Unggah Prestasi
                </button>
            </div>
        </div>
        <div class="absolute right-[-100px] top-[-100px] w-96 h-96 bg-white/10 rounded-full blur-[100px] animate-pulse"></div>
    </div>

    <!-- MAIN CONTENT AREA -->
    <div class="max-w-7xl mx-auto px-6 -mt-16 relative z-20">
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
            @forelse($achievements as $ach)
                <div class="bg-white rounded-[3rem] p-8 shadow-2xl shadow-slate-200/50 border border-slate-50 relative overflow-hidden group hover:-translate-y-2 transition-all duration-500">
                    <div class="flex items-center justify-between mb-6">
                        <span class="bg-amber-50 text-amber-600 text-[9px] font-black px-3 py-1.5 rounded-xl uppercase tracking-widest border border-amber-100">
                            {{ $ach->category }}
                        </span>
                        @php
                            $statusColors = ['pending' => 'amber', 'approved' => 'emerald', 'rejected' => 'rose'];
                            $statusLabels = ['pending' => 'MENUNGGU', 'approved' => 'TERVERIFIKASI', 'rejected' => 'DITOLAK'];
                            $color = $statusColors[$ach->status] ?? 'slate';
                        @endphp
                        <span class="text-[9px] font-black text-{{ $color }}-500 uppercase tracking-widest flex items-center">
                            <i class="fas fa-circle mr-2 text-[6px]"></i> {{ $statusLabels[$ach->status] ?? $ach->status }}
                        </span>
                    </div>

                    <h4 class="text-xl font-black text-slate-800 mb-2 leading-tight tracking-tight">{{ $ach->title }}</h4>
                    <p class="text-xs font-black text-slate-400 uppercase tracking-widest mb-6">{{ $ach->event_name }} ({{ $ach->year }})</p>

                    <div class="flex items-center gap-4 mb-8">
                        <div class="w-10 h-10 bg-slate-50 rounded-xl flex items-center justify-center text-slate-400">
                            <i class="fas fa-map-marker-alt"></i>
                        </div>
                        <div>
                            <span class="block text-[8px] font-black text-slate-400 uppercase tracking-widest">Tingkat</span>
                            <h6 class="text-xs font-black text-slate-700 mb-0 uppercase">{{ $ach->level }}</h6>
                        </div>
                    </div>

                    <div class="pt-6 border-t border-slate-50 flex items-center justify-between">
                        <div class="flex -space-x-3">
                            @if($ach->image)
                                <div class="w-10 h-10 rounded-full border-2 border-white bg-slate-100 flex items-center justify-center text-slate-400 shadow-sm" title="Foto Dokumentasi">
                                    <i class="fas fa-image text-xs"></i>
                                </div>
                            @endif
                            @if($ach->certificate_path)
                                <div class="w-10 h-10 rounded-full border-2 border-white bg-indigo-50 flex items-center justify-center text-indigo-400 shadow-sm" title="Sertifikat">
                                    <i class="fas fa-file-contract text-xs"></i>
                                </div>
                            @endif
                            @if($ach->trophy_path)
                                <div class="w-10 h-10 rounded-full border-2 border-white bg-amber-50 flex items-center justify-center text-amber-400 shadow-sm" title="Foto Piala">
                                    <i class="fas fa-trophy text-xs"></i>
                                </div>
                            @endif
                        </div>
                        <button onclick='showDetail(@json($ach))' class="text-indigo-600 text-[10px] font-black uppercase tracking-widest hover:text-indigo-700">Detail <i class="fas fa-chevron-right ml-1"></i></button>
                    </div>

                    <!-- Decoration -->
                    <div class="absolute right-[-20px] bottom-[-20px] opacity-[0.03] group-hover:opacity-[0.08] transition-opacity">
                        <i class="fas fa-award fa-8x"></i>
                    </div>
                </div>
            @empty
                <div class="col-span-full bg-white rounded-[3rem] p-20 shadow-2xl shadow-slate-200/50 border border-slate-50 text-center">
                    <div class="w-24 h-24 bg-slate-50 text-slate-200 rounded-[2.5rem] flex items-center justify-center mx-auto mb-8 shadow-inner">
                        <i class="fas fa-medal text-4xl"></i>
                    </div>
                    <h4 class="text-xl font-black text-slate-800 mb-2">Belum Ada Prestasi Terdata</h4>
                    <p class="text-sm text-slate-400 max-w-sm mx-auto">Klik tombol "Unggah Prestasi" untuk menambahkan pencapaian luar biasa Anda ke dalam sistem.</p>
                </div>
            @endforelse
        </div>
        
        <div class="mt-12">
            {{ $achievements->links() }}
        </div>
    </div>
</div>

{{-- MODAL UPLOAD --}}
<div class="modal fade" id="modalUploadAchievement" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
        <div class="modal-content border-0 rounded-[3rem] shadow-2xl overflow-hidden">
            <div class="bg-grad-warning px-10 py-10 text-white border-0 relative overflow-hidden text-center">
                <div class="absolute top-[-20px] right-[-20px] w-40 h-40 bg-white/10 rounded-full blur-2xl"></div>
                <div class="relative z-10">
                    <div class="w-16 h-16 bg-white/20 backdrop-blur-md rounded-2xl flex items-center justify-center mx-auto mb-4 border border-white/30 shadow-xl">
                        <i class="fas fa-trophy text-2xl"></i>
                    </div>
                    <h5 class="text-2xl font-black uppercase tracking-tight">Unggah Prestasi</h5>
                    <p class="text-[10px] font-black text-amber-100 uppercase tracking-[0.2em] opacity-80 mt-1">Dokumentasikan Pencapaian Anda</p>
                </div>
                <button type="button" class="absolute top-8 right-8 text-white/50 hover:text-white transition-colors" data-dismiss="modal">
                    <i class="fas fa-times text-xl"></i>
                </button>
            </div>
            <form id="formUploadAchievement" enctype="multipart/form-data">
                @csrf
                <div class="p-10 bg-white space-y-6">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label class="text-[10px] font-black text-slate-400 uppercase tracking-widest mb-3 block">Judul Prestasi <span class="text-rose-500">*</span></label>
                            <input type="text" name="title" class="w-full bg-slate-50 border-0 rounded-2xl p-4 text-sm font-black text-slate-700 focus:ring-4 focus:ring-amber-50 transition-all" required placeholder="Contoh: Juara 1 Lomba MTQ">
                        </div>
                        <div>
                            <label class="text-[10px] font-black text-slate-400 uppercase tracking-widest mb-3 block">Nama Event <span class="text-rose-500">*</span></label>
                            <input type="text" name="event_name" class="w-full bg-slate-50 border-0 rounded-2xl p-4 text-sm font-black text-slate-700 focus:ring-4 focus:ring-amber-50 transition-all" required placeholder="Contoh: PORSENI JATIM">
                        </div>
                    </div>
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                        <div>
                            <label class="text-[10px] font-black text-slate-400 uppercase tracking-widest mb-3 block">Kategori <span class="text-rose-500">*</span></label>
                            <select name="category" class="w-full bg-slate-50 border-0 rounded-2xl p-4 text-sm font-black text-slate-700" required>
                                <option value="Akademik">Akademik</option>
                                <option value="Non-Akademik">Non-Akademik</option>
                            </select>
                        </div>
                        <div>
                            <label class="text-[10px] font-black text-slate-400 uppercase tracking-widest mb-3 block">Tingkat <span class="text-rose-500">*</span></label>
                            <select name="level" class="w-full bg-slate-50 border-0 rounded-2xl p-4 text-sm font-black text-slate-700" required>
                                <option value="Sekolah">Sekolah</option>
                                <option value="Kecamatan">Kecamatan</option>
                                <option value="Kabupaten">Kabupaten</option>
                                <option value="Provinsi">Provinsi</option>
                                <option value="Nasional">Nasional</option>
                                <option value="Internasional">Internasional</option>
                            </select>
                        </div>
                        <div>
                            <label class="text-[10px] font-black text-slate-400 uppercase tracking-widest mb-3 block">Peringkat <span class="text-rose-500">*</span></label>
                            <input type="text" name="rank" class="w-full bg-slate-50 border-0 rounded-2xl p-4 text-sm font-black text-slate-700 focus:ring-4 focus:ring-amber-50" required placeholder="Contoh: Juara 1">
                        </div>
                    </div>
                    <div>
                        <label class="text-[10px] font-black text-slate-400 uppercase tracking-widest mb-3 block">Tanggal & Deskripsi</label>
                        <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
                            <input type="date" name="date" class="w-full bg-slate-50 border-0 rounded-2xl p-4 text-sm font-black text-slate-700" required>
                            <input type="text" name="description" class="md:col-span-3 w-full bg-slate-50 border-0 rounded-2xl p-4 text-sm font-black text-slate-700" placeholder="Keterangan tambahan (opsional)...">
                        </div>
                    </div>
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                        <div class="text-center">
                            <label class="text-[9px] font-black text-slate-400 uppercase tracking-widest mb-2 block">Sertifikat</label>
                            <input type="file" name="certificate_path" id="fileCert" class="hidden" accept="image/*">
                            <label for="fileCert" class="w-full aspect-square bg-slate-50 border-2 border-dashed border-slate-200 rounded-3xl flex items-center justify-center cursor-pointer hover:bg-amber-50 hover:border-amber-400 transition-all">
                                <i class="fas fa-file-contract text-2xl text-slate-300"></i>
                            </label>
                        </div>
                        <div class="text-center">
                            <label class="text-[9px] font-black text-slate-400 uppercase tracking-widest mb-2 block">Foto Piala</label>
                            <input type="file" name="trophy_path" id="fileTrophy" class="hidden" accept="image/*">
                            <label for="fileTrophy" class="w-full aspect-square bg-slate-50 border-2 border-dashed border-slate-200 rounded-3xl flex items-center justify-center cursor-pointer hover:bg-amber-50 hover:border-amber-400 transition-all">
                                <i class="fas fa-trophy text-2xl text-slate-300"></i>
                            </label>
                        </div>
                        <div class="text-center">
                            <label class="text-[9px] font-black text-slate-400 uppercase tracking-widest mb-2 block">Dokumentasi</label>
                            <input type="file" name="image" id="filePhoto" class="hidden" accept="image/*">
                            <label for="filePhoto" class="w-full aspect-square bg-slate-50 border-2 border-dashed border-slate-200 rounded-3xl flex items-center justify-center cursor-pointer hover:bg-amber-50 hover:border-amber-400 transition-all">
                                <i class="fas fa-camera text-2xl text-slate-300"></i>
                            </label>
                        </div>
                    </div>
                    <div class="pt-6">
                        <button type="submit" class="w-full bg-amber-600 text-white p-6 rounded-[2rem] font-black text-xs uppercase tracking-widest hover:bg-slate-900 transition-all shadow-2xl shadow-amber-100">Kirim Prestasi Untuk Verifikasi</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>

<style>
    body { background-color: #f8fafc; font-family: 'Outfit', sans-serif; }
    .bg-grad-warning { background: linear-gradient(135deg, #f59e0b 0%, #d97706 100%); }
    .pagination { justify-content: center; }
    .page-item .page-link { border-radius: 12px; margin: 0 5px; border: none; font-weight: 800; color: #64748b; }
    .page-item.active .page-link { background-color: #f59e0b; color: white; }
</style>

@push('scripts')
<script>
    function openUploadModal() {
        $('#modalUploadAchievement').modal('show');
    }

    function showDetail(ach) {
        Swal.fire({
            title: ach.title,
            html: `
                <div class="text-left space-y-4">
                    <div class="p-4 bg-slate-50 rounded-2xl border border-slate-100">
                        <span class="block text-[10px] font-black text-slate-400 uppercase tracking-widest mb-1">Event / Kegiatan</span>
                        <p class="text-sm font-black text-slate-800 mb-0">${ach.event_name}</p>
                    </div>
                    <div class="grid grid-cols-2 gap-4">
                        <div class="p-4 bg-slate-50 rounded-2xl border border-slate-100">
                            <span class="block text-[10px] font-black text-slate-400 uppercase tracking-widest mb-1">Peringkat</span>
                            <p class="text-sm font-black text-slate-800 mb-0">${ach.rank}</p>
                        </div>
                        <div class="p-4 bg-slate-50 rounded-2xl border border-slate-100">
                            <span class="block text-[10px] font-black text-slate-400 uppercase tracking-widest mb-1">Tingkat</span>
                            <p class="text-sm font-black text-slate-800 mb-0">${ach.level}</p>
                        </div>
                    </div>
                    <div class="p-4 bg-slate-50 rounded-2xl border border-slate-100">
                        <span class="block text-[10px] font-black text-slate-400 uppercase tracking-widest mb-1">Deskripsi</span>
                        <p class="text-xs font-medium text-slate-600 mb-0">${ach.description || '-'}</p>
                    </div>
                </div>
            `,
            showConfirmButton: true,
            confirmButtonText: 'TUTUP',
            confirmButtonColor: '#f59e0b',
            customClass: { popup: 'rounded-[3rem]', title: 'font-black tracking-tight' }
        });
    }

    $('#formUploadAchievement').submit(function(e) {
        e.preventDefault();
        let formData = new FormData(this);
        Swal.fire({ title: 'MEMPROSES...', didOpen: () => Swal.showLoading(), customClass: { popup: 'rounded-[3rem]' } });
        
        $.ajax({
            url: '{{ route("siswa.achievements.store") }}',
            type: 'POST',
            data: formData,
            contentType: false, processData: false,
            success: function(res) {
                if(res.success) {
                    Swal.fire({ icon: 'success', title: 'BERHASIL', text: res.message, customClass: { popup: 'rounded-[3rem]' } }).then(() => location.reload());
                }
            },
            error: function(xhr) {
                Swal.fire({ icon: 'error', title: 'GAGAL', text: xhr.responseJSON?.message || 'Terjadi kesalahan', customClass: { popup: 'rounded-[3rem]' } });
            }
        });
    });

    // File input preview label update
    $('input[type="file"]').on('change', function() {
        let label = $(this).next('label');
        if (this.files && this.files[0]) {
            label.addClass('bg-amber-100 border-amber-500').find('i').removeClass('text-slate-300').addClass('text-amber-600');
        }
    });
</script>
@endpush
@endsection
