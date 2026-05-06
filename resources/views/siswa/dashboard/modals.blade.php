{{-- MODAL PENGAJUAN IZIN --}}
<div class="modal fade" id="modalPengajuanIzin" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content" style="border-radius: 16px; border: none; overflow: hidden;">
            <div class="modal-header" style="background: linear-gradient(135deg, #3b82f6, #2563eb); color: white; border: none;">
                <h5 class="modal-title font-weight-bold"><i class="fas fa-envelope-open-text mr-2"></i> Pengajuan Izin / Sakit</h5>
                <button type="button" class="close text-white" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form id="formPengajuanIzin" enctype="multipart/form-data">
                @csrf
                <div class="modal-body p-4">
                    <div class="form-group mb-3">
                        <label class="font-weight-bold text-sm">Jenis Pengajuan <span class="text-danger">*</span></label>
                        <select name="type" class="form-control" style="border-radius: 10px;" required>
                            <option value="">Pilih Jenis</option>
                            <option value="Sakit">Sakit</option>
                            <option value="Izin">Izin (Keperluan Keluarga, dll)</option>
                        </select>
                    </div>
                    <div class="row">
                        <div class="col-6">
                            <div class="form-group mb-3">
                                <label class="font-weight-bold text-sm">Mulai Tanggal <span class="text-danger">*</span></label>
                                <input type="date" name="start_date" class="form-control" style="border-radius: 10px;" required>
                            </div>
                        </div>
                        <div class="col-6">
                            <div class="form-group mb-3">
                                <label class="font-weight-bold text-sm">Sampai Tanggal</label>
                                <input type="date" name="end_date" class="form-control" style="border-radius: 10px;">
                                <small class="text-muted">Kosongkan jika hanya 1 hari</small>
                            </div>
                        </div>
                    </div>
                    <div class="form-group mb-3">
                        <label class="font-weight-bold text-sm">Alasan / Keterangan <span class="text-danger">*</span></label>
                        <textarea name="reason" rows="3" class="form-control" style="border-radius: 10px;" required placeholder="Jelaskan alasan izin..."></textarea>
                    </div>
                    <div class="form-group mb-0">
                        <label class="font-weight-bold text-sm">Lampiran (Opsional)</label>
                        <div class="custom-file">
                            <input type="file" name="attachment" class="custom-file-input" id="customFile" accept="image/*">
                            <label class="custom-file-label" for="customFile" style="border-radius: 10px;">Pilih foto surat/keterangan</label>
                        </div>
                        <small class="text-muted">Format: JPG/PNG, Maks: 5MB.</small>
                    </div>
                </div>
                <div class="modal-footer bg-light" style="border: none;">
                    <button type="button" class="btn btn-secondary" style="border-radius: 10px;" data-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-primary" style="border-radius: 10px; background: linear-gradient(135deg, #3b82f6, #2563eb); border: none;">Kirim Pengajuan</button>
                </div>
            </form>
        </div>
    </div>
</div>

{{-- MODAL MUTABA'AH YAUMIYAH --}}
<div class="modal fade" id="modalMutabaah" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content" style="border-radius: 25px; border: none; overflow: hidden;">
            <div class="modal-header" style="background: linear-gradient(135deg, #10b981, #059669); color: white; border: none; padding: 20px 25px;">
                <h5 class="modal-title font-weight-bold"><i class="fas fa-tasks mr-2"></i> Jurnal Ibadah Harian</h5>
                <button type="button" class="close text-white" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form id="formMutabaahModal">
                @csrf
                <div class="modal-body p-4">
                    <p class="text-muted text-sm mb-4">Silakan centang ibadah yang telah Anda laksanakan hari ini.</p>
                    <div class="row">
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
                            <div class="col-6 mb-3">
                                <div class="custom-control custom-checkbox custom-checkbox-lg p-3" style="background: #f8fafc; border-radius: 12px; border: 1px solid #e2e8f0;">
                                    <input type="checkbox" name="{{ $key }}" class="custom-control-input" id="modalcheck{{ $key }}" {{ ($todayMutabaah && $todayMutabaah->$key) ? 'checked' : '' }} value="1">
                                    <label class="custom-control-label font-weight-bold text-dark" for="modalcheck{{ $key }}" style="cursor: pointer;">{{ $label }}</label>
                                </div>
                            </div>
                        @endforeach
                    </div>
                    <div class="form-group mb-0 mt-2">
                        <label class="font-weight-bold text-sm">Tadarus / Catatan Ibadah</label>
                        <input type="text" name="tadarus" class="form-control" style="border-radius: 12px;" placeholder="Misal: Surah Al-Kahfi ayat 1-10" value="{{ $todayMutabaah->tadarus ?? '' }}">
                    </div>
                </div>
                <div class="modal-footer bg-light" style="border: none;">
                    <button type="button" class="btn btn-secondary" style="border-radius: 12px;" data-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-success px-4" style="border-radius: 12px; background: linear-gradient(135deg, #10b981, #059669); border: none; font-weight: 700;">Simpan Jurnal</button>
                </div>
            </form>
        </div>
    </div>
</div>

{{-- MODAL TAHFIDZ HISTORY --}}
<div class="modal fade" id="modalTahfidz" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
        <div class="modal-content" style="border-radius: 25px; border: none; overflow: hidden;">
            <div class="modal-header" style="background: linear-gradient(135deg, #059669, #047857); color: white; border: none; padding: 20px 25px;">
                <h5 class="modal-title font-weight-bold"><i class="fas fa-quran mr-2"></i> Riwayat Hafalan (Tahfidz)</h5>
                <button type="button" class="close text-white" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body p-4">
                @if($tahfidzLogs->isEmpty())
                    <div class="text-center py-5">
                        <i class="fas fa-book-open fa-3x text-muted mb-3 d-block" style="opacity:.1"></i>
                        <p class="text-muted">Belum ada catatan hafalan terdaftar.</p>
                    </div>
                @else
                    <div class="table-responsive">
                        <table class="table table-hover">
                            <thead class="bg-light">
                                <tr>
                                    <th>Tanggal</th>
                                    <th>Surah</th>
                                    <th>Ayat</th>
                                    <th>Juz</th>
                                    <th>Jenis</th>
                                    <th>Grade</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($tahfidzLogs as $log)
                                    <tr>
                                        <td>{{ $log->date->format('d/m/Y') }}</td>
                                        <td class="font-weight-bold">{{ $log->surah_name }}</td>
                                        <td>{{ $log->verse_range }}</td>
                                        <td>{{ $log->juz }}</td>
                                        <td>{{ ucfirst($log->type) }}</td>
                                        <td><span class="font-weight-bold text-success">{{ $log->grade }}</span></td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>

{{-- MODAL POIN --}}
<div class="modal fade" id="modalPoin" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
        <div class="modal-content" style="border-radius: 35px; border: none; overflow: hidden;">
            <div class="modal-header border-0 px-4 pt-4 pb-0">
                <h5 class="modal-title font-black text-slate-800">Riwayat Poin Akhlak</h5>
                <button type="button" class="close" data-dismiss="modal">&times;</button>
            </div>
            <div class="modal-body p-4">
                <div class="bg-grad-green p-4 text-center text-white mb-4" style="border-radius: 25px;">
                    <span class="text-[10px] font-black opacity-70 tracking-widest uppercase">Skor Akhlak Terakumulasi</span>
                    <h2 class="font-black mb-0" style="font-size: 3rem;">{{ $netPoints }}</h2>
                </div>

                <div class="stu-history-list" style="max-height: 400px; overflow-y: auto;">
                    @foreach($behaviorLogs as $log)
                        <div class="p-3 mb-3 border-0 bg-light d-flex align-items-center justify-content-between" style="border-radius: 20px;">
                            <div class="d-flex align-items-center">
                                <div class="mr-3 {{ $log->type == 'positive' ? 'text-success' : 'text-danger' }}">
                                    <i class="fas {{ $log->type == 'positive' ? 'fa-plus-circle' : 'fa-minus-circle' }} fa-lg"></i>
                                </div>
                                <div>
                                    <h6 class="font-weight-bold mb-1">{{ $log->description }}</h6>
                                    <small class="text-muted">{{ $log->date->format('d M Y') }} &bull; {{ $log->category }}</small>
                                </div>
                            </div>
                            <div class="font-weight-bold {{ $log->type == 'positive' ? 'text-success' : 'text-danger' }}">
                                {{ $log->type == 'positive' ? '+' : '-' }}{{ $log->points }}
                            </div>
                        </div>
                    @endforeach
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
            Swal.fire({ title: 'Memproses...', allowOutsideClick: false, didOpen: () => Swal.showLoading() });
            $.ajax({
                url: '{{ route("siswa.store_permit") }}',
                type: 'POST',
                data: formData,
                processData: false,
                contentType: false,
                success: function(response) {
                    Swal.fire({ icon: 'success', title: 'Berhasil', text: response.message }).then(() => location.reload());
                },
                error: function(xhr) {
                    Swal.fire({ icon: 'error', title: 'Error', text: xhr.responseJSON?.message || 'Terjadi kesalahan' });
                }
            });
        });

        $('#formMutabaahModal').submit(function(e) {
            e.preventDefault();
            let formData = $(this).serialize();
            Swal.fire({ title: 'Menyimpan...', allowOutsideClick: false, didOpen: () => Swal.showLoading() });
            $.post('{{ route("siswa.store_mutabaah") }}', formData)
                .done(response => {
                    Swal.fire({ icon: 'success', title: 'Berhasil', text: response.message, timer: 1500, showConfirmButton: false });
                    setTimeout(() => location.reload(), 1500);
                })
                .fail(xhr => {
                    Swal.fire({ icon: 'error', title: 'Error', text: xhr.responseJSON?.message || 'Terjadi kesalahan' });
                });
        });

        $('.custom-file-input').on('change', function() {
            let fileName = $(this).val().split('\\').pop();
            $(this).next('.custom-file-label').addClass("selected").html(fileName);
        });
    });
</script>

<style>
    .bg-grad-green { background: linear-gradient(135deg, #065f46, #10b981); }
    .bg-grad-blue { background: linear-gradient(135deg, #1e3a8a, #3b82f6); }
    .bg-grad-orange { background: linear-gradient(135deg, #9a3412, #f97316); }
    .bg-grad-purple { background: linear-gradient(135deg, #581c87, #8b5cf6); }
</style>
