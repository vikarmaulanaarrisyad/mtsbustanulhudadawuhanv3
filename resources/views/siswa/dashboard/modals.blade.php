{{-- MODAL PENGAJUAN IZIN --}}
<div class="modal fade" id="modalPengajuanIzin" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content border-0 rounded-[3rem] shadow-2xl overflow-hidden">
            <div class="bg-grad-indigo px-10 py-10 text-white border-0 relative overflow-hidden text-center">
                <div class="absolute top-[-20px] right-[-20px] w-40 h-40 bg-white/10 rounded-full blur-2xl"></div>
                <div class="relative z-10">
                    <div class="w-16 h-16 bg-white/20 backdrop-blur-md rounded-2xl flex items-center justify-center mx-auto mb-4 border border-white/30 shadow-xl">
                        <i class="fas fa-envelope-open-text text-2xl"></i>
                    </div>
                    <h5 class="text-2xl font-black uppercase tracking-tight">Pengajuan Izin</h5>
                    <p class="text-[10px] font-black text-indigo-100 uppercase tracking-[0.2em] opacity-80 mt-1">Layanan Absensi Siswa</p>
                </div>
                <button type="button" class="absolute top-8 right-8 text-white/50 hover:text-white transition-colors" data-dismiss="modal">
                    <i class="fas fa-times text-xl"></i>
                </button>
            </div>
            <form id="formPengajuanIzin" enctype="multipart/form-data">
                @csrf
                <div class="p-10 space-y-6 bg-white">
                    <div>
                        <label class="text-[10px] font-black text-slate-400 uppercase tracking-widest mb-3 block">Jenis Pengajuan <span class="text-rose-500">*</span></label>
                        <select name="type" class="w-full bg-slate-50 border-0 rounded-2xl p-4 text-sm font-black text-slate-700 focus:ring-4 focus:ring-indigo-50 transition-all cursor-pointer" required>
                            <option value="">Pilih Jenis...</option>
                            <option value="Sakit">Sakit (Butuh Istirahat)</option>
                            <option value="Izin">Izin (Keperluan Mendesak)</option>
                        </select>
                    </div>
                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label class="text-[10px] font-black text-slate-400 uppercase tracking-widest mb-3 block">Mulai Tanggal <span class="text-rose-500">*</span></label>
                            <input type="date" name="start_date" class="w-full bg-slate-50 border-0 rounded-2xl p-4 text-sm font-black text-slate-700 focus:ring-4 focus:ring-indigo-50 transition-all" required>
                        </div>
                        <div>
                            <label class="text-[10px] font-black text-slate-400 uppercase tracking-widest mb-3 block">Sampai Tanggal</label>
                            <input type="date" name="end_date" class="w-full bg-slate-50 border-0 rounded-2xl p-4 text-sm font-black text-slate-700 focus:ring-4 focus:ring-indigo-50 transition-all">
                        </div>
                    </div>
                    <div>
                        <label class="text-[10px] font-black text-slate-400 uppercase tracking-widest mb-3 block">Alasan / Keterangan <span class="text-rose-500">*</span></label>
                        <textarea name="reason" rows="3" class="w-full bg-slate-50 border-0 rounded-2xl p-4 text-sm font-black text-slate-700 focus:ring-4 focus:ring-indigo-50 transition-all" required placeholder="Tuliskan alasan detail pengajuan izin..."></textarea>
                    </div>
                    <div>
                        <label class="text-[10px] font-black text-slate-400 uppercase tracking-widest mb-3 block">Lampiran (Foto Surat)</label>
                        <div class="relative group">
                            <input type="file" name="attachment" id="fileIzin" accept="image/*" class="hidden">
                            <label for="fileIzin" class="flex items-center justify-center w-full p-10 border-2 border-dashed border-slate-200 rounded-[2rem] bg-slate-50 cursor-pointer group-hover:border-indigo-500 group-hover:bg-indigo-50/30 transition-all">
                                <div class="text-center">
                                    <i class="fas fa-cloud-upload-alt text-4xl text-slate-300 group-hover:text-indigo-500 transition-colors mb-3"></i>
                                    <p class="text-[10px] font-black text-slate-400 uppercase tracking-widest">Klik Untuk Unggah Gambar</p>
                                </div>
                            </label>
                        </div>
                    </div>
                    <div class="pt-4 space-y-3">
                        <button type="submit" class="w-full bg-indigo-600 text-white p-5 rounded-2xl font-black text-xs uppercase tracking-widest hover:bg-slate-900 transition-all shadow-2xl shadow-indigo-100">Kirim Pengajuan</button>
                        <button type="button" class="w-full bg-slate-100 text-slate-400 p-4 rounded-2xl font-black text-[10px] uppercase tracking-widest hover:bg-slate-200 transition-all" data-dismiss="modal">Tutup Jendela</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>

{{-- MODAL MUTABA'AH YAUMIYAH --}}
<div class="modal fade" id="modalMutabaah" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content border-0 rounded-[3rem] shadow-2xl overflow-hidden">
            <div class="bg-grad-indigo px-10 py-10 text-white border-0 relative overflow-hidden text-center">
                <div class="absolute top-[-20px] left-[-20px] w-40 h-40 bg-white/10 rounded-full blur-2xl"></div>
                <div class="relative z-10">
                    <div class="w-16 h-16 bg-white/20 backdrop-blur-md rounded-2xl flex items-center justify-center mx-auto mb-4 border border-white/30 shadow-xl">
                        <i class="fas fa-tasks text-2xl"></i>
                    </div>
                    <h5 class="text-2xl font-black uppercase tracking-tight">Ibadah Harian</h5>
                    <p class="text-[10px] font-black text-indigo-100 uppercase tracking-[0.2em] opacity-80 mt-1">Jurnal Mutaba'ah Yaumiyah</p>
                </div>
                <button type="button" class="absolute top-8 right-8 text-white/50 hover:text-white transition-colors" data-dismiss="modal">
                    <i class="fas fa-times text-xl"></i>
                </button>
            </div>
            <form id="formMutabaahModal">
                @csrf
                <div class="p-10 space-y-6 bg-white">
                    <p class="text-[10px] font-black text-slate-400 uppercase tracking-[0.2em] mb-6">Capaian Ibadah Hari Ini</p>
                    <div class="grid grid-cols-2 gap-4">
                        @php
                            $prayers_m = [
                                'shubuh' => 'Shubuh',
                                'zhuhur' => 'Zhuhur',
                                'ashar' => 'Ashar',
                                'maghrib' => 'Maghrib',
                                'isya' => 'Isya',
                                'dhuha' => 'Dhuha',
                                'tahajud' => 'Tahajud'
                            ];
                        @endphp
                        @foreach($prayers_m as $key => $label)
                            <div class="relative">
                                <input type="checkbox" name="{{ $key }}" id="check{{ $key }}" class="peer hidden" {{ ($todayMutabaah && $todayMutabaah->$key) ? 'checked' : '' }} value="1">
                                <label for="check{{ $key }}" class="flex items-center gap-3 p-4 bg-slate-50 border-2 border-transparent peer-checked:border-indigo-500 peer-checked:bg-indigo-50 rounded-2xl cursor-pointer transition-all">
                                    <div class="w-6 h-6 rounded-lg bg-white border-2 border-slate-200 peer-checked:border-indigo-500 flex items-center justify-center text-indigo-600">
                                        <i class="fas fa-check scale-0 peer-checked:scale-100 transition-transform text-[10px]"></i>
                                    </div>
                                    <span class="text-[10px] font-black text-slate-700 uppercase tracking-widest">{{ $label }}</span>
                                </label>
                            </div>
                        @endforeach
                    </div>
                    <div>
                        <label class="text-[10px] font-black text-slate-400 uppercase tracking-widest mb-3 block">Tadarus / Catatan</label>
                        <input type="text" name="tadarus" class="w-full bg-slate-50 border-0 rounded-2xl p-4 text-sm font-black text-slate-700 focus:ring-4 focus:ring-indigo-50" placeholder="Contoh: Surah Al-Kahfi ayat 1-10" value="{{ $todayMutabaah->tadarus ?? '' }}">
                    </div>
                    <div class="pt-4 space-y-3">
                        <button type="submit" class="w-full bg-indigo-600 text-white p-5 rounded-2xl font-black text-xs uppercase tracking-widest hover:bg-slate-900 transition-all shadow-2xl shadow-indigo-100">Simpan Jurnal</button>
                        <button type="button" class="w-full bg-slate-100 text-slate-400 p-4 rounded-2xl font-black text-[10px] uppercase tracking-widest hover:bg-slate-200 transition-all" data-dismiss="modal">Tutup</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>

{{-- MODAL TAHFIDZ HISTORY --}}
<div class="modal fade" id="modalTahfidz" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
        <div class="modal-content border-0 rounded-[3rem] shadow-2xl overflow-hidden">
            <div class="bg-grad-indigo px-10 py-10 text-white border-0 relative overflow-hidden text-center">
                <div class="absolute top-[-20px] right-[-20px] w-40 h-40 bg-white/10 rounded-full blur-2xl"></div>
                <div class="relative z-10">
                    <div class="w-16 h-16 bg-white/20 backdrop-blur-md rounded-2xl flex items-center justify-center mx-auto mb-4 border border-white/30 shadow-xl">
                        <i class="fas fa-quran text-2xl"></i>
                    </div>
                    <h5 class="text-2xl font-black uppercase tracking-tight">Riwayat Hafalan</h5>
                    <p class="text-[10px] font-black text-indigo-100 uppercase tracking-[0.2em] opacity-80 mt-1">Laporan Tahfidz Al-Qur'an</p>
                </div>
                <button type="button" class="absolute top-8 right-8 text-white/50 hover:text-white transition-colors" data-dismiss="modal">
                    <i class="fas fa-times text-xl"></i>
                </button>
            </div>
            <div class="p-10 bg-white">
                @if($tahfidzLogs->isEmpty())
                    <div class="text-center py-20 bg-slate-50 rounded-[3rem] border-2 border-dashed border-slate-200">
                        <i class="fas fa-book-open text-5xl text-slate-200 mb-6"></i>
                        <p class="text-xs font-black text-slate-400 uppercase tracking-widest">Belum ada catatan hafalan</p>
                    </div>
                @else
                    <div class="max-h-[500px] overflow-y-auto pr-4 space-y-4 custom-scrollbar">
                        @foreach($tahfidzLogs as $log)
                            <div class="flex items-center gap-6 p-6 bg-slate-50 rounded-[2.5rem] border border-slate-100 hover:border-indigo-200 hover:bg-white transition-all shadow-sm group">
                                <div class="w-16 h-16 bg-white rounded-2xl flex items-center justify-center text-indigo-600 shadow-xl font-black text-2xl border border-slate-50 group-hover:bg-indigo-600 group-hover:text-white transition-all">
                                    {{ $log->grade }}
                                </div>
                                <div class="flex-1">
                                    <div class="flex items-center justify-between mb-2">
                                        <h6 class="font-black text-slate-800 text-lg leading-none tracking-tight">{{ $log->surah_name }}</h6>
                                        <span class="text-[10px] font-black text-slate-400 uppercase tracking-widest">{{ $log->date->format('d M Y') }}</span>
                                    </div>
                                    <p class="text-[9px] text-slate-500 font-black uppercase tracking-[0.2em] leading-none">Ayat {{ $log->verse_range }} &bull; Juz {{ $log->juz }} &bull; {{ ucfirst($log->type) }}</p>
                                </div>
                            </div>
                        @endforeach
                    </div>
                @endif
                <div class="mt-8">
                    <button type="button" class="w-full bg-slate-100 text-slate-400 p-4 rounded-2xl font-black text-[10px] uppercase tracking-widest hover:bg-slate-200 transition-all" data-dismiss="modal">Tutup Jendela</button>
                </div>
            </div>
        </div>
    </div>
</div>

{{-- MODAL POIN AKHLAK --}}
<div class="modal fade" id="modalPoin" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
        <div class="modal-content border-0 rounded-[3rem] shadow-2xl overflow-hidden">
            <div class="bg-grad-indigo px-10 py-10 text-white border-0 relative overflow-hidden text-center">
                <div class="absolute top-[-20px] right-[-20px] w-40 h-40 bg-white/10 rounded-full blur-2xl"></div>
                <div class="relative z-10">
                    <div class="w-16 h-16 bg-white/20 backdrop-blur-md rounded-2xl flex items-center justify-center mx-auto mb-4 border border-white/30 shadow-xl">
                        <i class="fas fa-medal text-2xl"></i>
                    </div>
                    <h5 class="text-2xl font-black uppercase tracking-tight">Riwayat Poin</h5>
                    <p class="text-[10px] font-black text-indigo-100 uppercase tracking-[0.2em] opacity-80 mt-1">Catatan Karakter Siswa</p>
                </div>
                <button type="button" class="absolute top-8 right-8 text-white/50 hover:text-white transition-colors" data-dismiss="modal">
                    <i class="fas fa-times text-xl"></i>
                </button>
            </div>
            <div class="p-10 bg-white">
                <div class="bg-indigo-50 p-10 rounded-[3rem] border border-indigo-100 text-center mb-10 shadow-inner">
                    <p class="text-[10px] font-black text-indigo-400 uppercase tracking-[0.3em] mb-3">Skor Akhlak Terakumulasi</p>
                    <h2 class="text-7xl font-black text-indigo-600 tracking-tighter">{{ $netPoints }}</h2>
                </div>

                <div class="max-h-[400px] overflow-y-auto pr-4 space-y-4 custom-scrollbar">
                    @forelse($behaviorLogs as $log)
                        <div class="flex items-center gap-6 p-6 bg-slate-50 rounded-[2.5rem] border border-slate-100 group hover:bg-white transition-all">
                            <div class="w-14 h-14 {{ $log->type == 'positive' ? 'bg-emerald-50 text-emerald-600' : 'bg-rose-50 text-rose-600' }} rounded-2xl flex items-center justify-center text-xl shadow-sm border border-white">
                                <i class="fas {{ $log->type == 'positive' ? 'fa-plus-circle' : 'fa-minus-circle' }}"></i>
                            </div>
                            <div class="flex-1">
                                <div class="flex items-center justify-between mb-2">
                                    <h6 class="font-black text-slate-800 text-base leading-none tracking-tight">{{ $log->description }}</h6>
                                    <span class="text-lg font-black {{ $log->type == 'positive' ? 'text-emerald-600' : 'text-rose-600' }}">{{ $log->type == 'positive' ? '+' : '-' }}{{ $log->points }}</span>
                                </div>
                                <p class="text-[9px] text-slate-400 font-black uppercase tracking-[0.2em] leading-none">{{ $log->date->format('d M Y') }} &bull; {{ $log->category }}</p>
                            </div>
                        </div>
                    @empty
                        <div class="text-center py-12">
                            <p class="text-xs font-black text-slate-300 uppercase tracking-widest">Belum ada riwayat poin</p>
                        </div>
                    @endforelse
                </div>
                <div class="mt-10">
                    <button type="button" class="w-full bg-slate-100 text-slate-400 p-4 rounded-2xl font-black text-[10px] uppercase tracking-widest hover:bg-slate-200 transition-all" data-dismiss="modal">Tutup Jendela</button>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    $(document).ready(function() {
        $('#formPengajuanIzin').submit(function(e) {
            e.preventDefault();
            let formData = new FormData(this);
            Swal.fire({ title: 'MEMPROSES...', allowOutsideClick: false, didOpen: () => Swal.showLoading(), customClass: { popup: 'rounded-[2.5rem]' } });
            $.ajax({
                url: '{{ route("siswa.store_permit") }}',
                type: 'POST',
                data: formData,
                processData: false,
                contentType: false,
                success: function(response) {
                    Swal.fire({ icon: 'success', title: 'BERHASIL', text: response.message, customClass: { popup: 'rounded-[2.5rem]' } }).then(() => location.reload());
                },
                error: function(xhr) {
                    Swal.fire({ icon: 'error', title: 'GAGAL', text: xhr.responseJSON?.message || 'Terjadi kesalahan', customClass: { popup: 'rounded-[2.5rem]' } });
                }
            });
        });

        $('#formMutabaahModal').submit(function(e) {
            e.preventDefault();
            let formData = $(this).serialize();
            Swal.fire({ title: 'MENYIMPAN...', allowOutsideClick: false, didOpen: () => Swal.showLoading(), customClass: { popup: 'rounded-[2.5rem]' } });
            $.post('{{ route("siswa.store_mutabaah") }}', formData)
                .done(response => {
                    Swal.fire({ icon: 'success', title: 'BERHASIL', text: response.message, timer: 1500, showConfirmButton: false, customClass: { popup: 'rounded-[2.5rem]' } });
                    setTimeout(() => location.reload(), 1500);
                })
                .fail(xhr => {
                    Swal.fire({ icon: 'error', title: 'GAGAL', text: xhr.responseJSON?.message || 'Terjadi kesalahan', customClass: { popup: 'rounded-[2.5rem]' } });
                });
        });

        $('#fileIzin').on('change', function() {
            let fileName = $(this).val().split('\\').pop();
            $(this).next().find('p').html(fileName || 'Klik Untuk Unggah Gambar');
        });
    });
</script>

<style>
    .custom-scrollbar::-webkit-scrollbar { width: 5px; }
    .custom-scrollbar::-webkit-scrollbar-track { background: transparent; }
    .custom-scrollbar::-webkit-scrollbar-thumb { background: #e2e8f0; border-radius: 10px; }
</style>
