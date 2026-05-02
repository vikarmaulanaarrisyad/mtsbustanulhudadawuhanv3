<div class="modal fade" id="modalForm" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <form id="formAcceptance">
                @csrf
                <input type="hidden" name="id" id="id">
                <div class="modal-header">
                    <h5 class="modal-title"></h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-8">
                            <div class="form-group">
                                <label>Pilih Siswa</label>
                                <select name="student_id" id="student_id" class="form-control select2" style="width: 100%;">
                                    <option value="">-- Pilih Siswa --</option>
                                    @foreach($students as $s)
                                        <option value="{{ $s->id }}">{{ $s->nis }} - {{ $s->nama_lengkap }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label>Tanggal Surat</label>
                                <input type="date" name="acceptance_date" id="acceptance_date" class="form-control" value="{{ date('Y-m-d') }}">
                            </div>
                        </div>
                    </div>

                    <div class="form-group">
                        <label>Nomor Surat</label>
                        <div class="input-group">
                            <input type="text" name="acceptance_number" id="acceptance_number" class="form-control" placeholder="Contoh: 001/MTs-BH/V/2026">
                            <div class="input-group-append">
                                <button type="button" class="btn btn-outline-primary" onclick="generateNumber('StudentAcceptance', 'MT', '#acceptance_number', 'acceptance_number')">
                                    <i class="fas fa-magic mr-1"></i> Generate
                                </button>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-8">
                            <div class="form-group">
                                <label>Sekolah Asal</label>
                                <input type="text" name="origin_school" id="origin_school" class="form-control" placeholder="Nama Madrasah/Sekolah Asal">
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label>Kelas Asal</label>
                                <input type="text" name="origin_class" id="origin_class" class="form-control" placeholder="Contoh: VII (Tujuh)">
                            </div>
                        </div>
                    </div>

                    <hr>
                    <h6 class="text-primary font-weight-bold"><i class="fas fa-signature mr-1"></i> Penandatangan (Opsional)</h6>
                    <div class="row">
                        <div class="col-md-4">
                            <div class="form-group">
                                <label>Nama</label>
                                <input type="text" name="signer_name" id="signer_name" class="form-control" value="{{ $mailSetting->default_signer_name }}">
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label>Jabatan</label>
                                <input type="text" name="signer_position" id="signer_position" class="form-control" value="{{ $mailSetting->default_signer_position }}">
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label>NIP</label>
                                <input type="text" name="signer_nip" id="signer_nip" class="form-control" value="{{ $mailSetting->default_signer_nip }}">
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-primary">Simpan Surat</button>
                </div>
            </form>
        </div>
    </div>
</div>
