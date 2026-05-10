<div class="modal fade" id="modal-form" tabindex="-1" role="dialog">
    <div class="modal-dialog modal-lg" role="document">
        <form id="form-achievement" enctype="multipart/form-data">
            @csrf
            @method('post')
            <div class="modal-content border-0 shadow-lg" style="border-radius: 20px;">
                <div class="modal-header bg-warning text-white p-4" style="border-radius: 20px 20px 0 0;">
                    <h5 class="modal-title font-weight-bold">Tambah Prestasi</h5>
                    <button type="button" class="close text-white" data-dismiss="modal"><span>&times;</span></button>
                </div>
                <div class="modal-body p-4">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label class="font-weight-bold">Pilih Siswa <span class="text-danger">*</span></label>
                                <select name="student_id" id="student_id" class="form-control select2" style="width: 100%" required>
                                    <option value="">Pilih Siswa...</option>
                                    @foreach($students as $s)
                                        <option value="{{ $s->id }}">{{ $s->nama_lengkap }} ({{ $s->kelas_lengkap ?? '-' }})</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label class="font-weight-bold">Tahun Pelajaran <span class="text-danger">*</span></label>
                                <select name="academic_year_id" id="academic_year_id" class="form-control select2" style="width: 100%" required>
                                    <option value="">Pilih Tahun...</option>
                                    @foreach($academicYears as $ay)
                                        <option value="{{ $ay->id }}">{{ $ay->academic_year }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                    </div>

                    <div class="form-group">
                        <label class="font-weight-bold">Judul Prestasi <span class="text-danger">*</span></label>
                        <input type="text" name="title" class="form-control rounded-pill" placeholder="Contoh: Juara 1 Lomba Pidato" required>
                    </div>

                    <div class="form-group">
                        <label class="font-weight-bold">Nama Event/Kegiatan <span class="text-danger">*</span></label>
                        <input type="text" name="event_name" class="form-control rounded-pill" placeholder="Contoh: PORSENI Tingkat Kabupaten" required>
                    </div>

                    <div class="row">
                        <div class="col-md-4">
                            <div class="form-group">
                                <label class="font-weight-bold">Kategori <span class="text-danger">*</span></label>
                                <select name="category" class="form-control rounded-pill" required>
                                    <option value="Akademik">Akademik</option>
                                    <option value="Non-Akademik">Non-Akademik</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label class="font-weight-bold">Tingkat <span class="text-danger">*</span></label>
                                <select name="level" class="form-control rounded-pill" required>
                                    <option value="Sekolah">Sekolah</option>
                                    <option value="Kecamatan">Kecamatan</option>
                                    <option value="Kabupaten">Kabupaten</option>
                                    <option value="Provinsi">Provinsi</option>
                                    <option value="Nasional">Nasional</option>
                                    <option value="Internasional">Internasional</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label class="font-weight-bold">Peringkat <span class="text-danger">*</span></label>
                                <input type="text" name="rank" class="form-control rounded-pill" placeholder="Juara 1 / Harapan 2" required>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label class="font-weight-bold">Tanggal Prestasi <span class="text-danger">*</span></label>
                                <input type="date" name="date" class="form-control rounded-pill" required>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label class="font-weight-bold">Status Verifikasi</label>
                                <select name="status" class="form-control rounded-pill">
                                    <option value="approved">Disetujui</option>
                                    <option value="pending">Pending</option>
                                    <option value="rejected">Ditolak</option>
                                </select>
                            </div>
                        </div>
                    </div>

                    <div class="form-group">
                        <label class="font-weight-bold">Deskripsi Tambahan</label>
                        <textarea name="description" rows="2" class="form-control" style="border-radius: 15px;" placeholder="Tuliskan keterangan jika ada..."></textarea>
                    </div>

                    <div class="row">
                        <div class="col-md-4">
                            <div class="form-group">
                                <label class="font-weight-bold">Foto Dokumentasi</label>
                                <input type="file" name="image" class="form-control-file">
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label class="font-weight-bold">Scan Sertifikat</label>
                                <input type="file" name="certificate_path" class="form-control-file">
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label class="font-weight-bold">Foto Piala/Medali</label>
                                <input type="file" name="trophy_path" class="form-control-file">
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer border-0 p-4">
                    <button type="button" class="btn btn-light rounded-pill px-4" data-dismiss="modal">BATAL</button>
                    <button type="submit" class="btn btn-warning text-white rounded-pill px-4 font-weight-bold">SIMPAN DATA</button>
                </div>
            </div>
        </form>
    </div>
</div>
