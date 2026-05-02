<x-modal data-backdrop="static" data-keyboard="false" size="modal-xl">
    <x-slot name="title">Tambah Siswa</x-slot>

    @method('POST')

    {{-- TABS --}}
    <ul class="nav nav-tabs" role="tablist">
        <li class="nav-item">
            <a class="nav-link active" data-toggle="tab" href="#tab-identitas"><i class="fas fa-user mr-1"></i> Identitas</a>
        </li>
        <li class="nav-item">
            <a class="nav-link" data-toggle="tab" href="#tab-akademik"><i class="fas fa-graduation-cap mr-1"></i> Akademik</a>
        </li>
        <li class="nav-item">
            <a class="nav-link" data-toggle="tab" href="#tab-alamat"><i class="fas fa-map-marker-alt mr-1"></i> Alamat & Kontak</a>
        </li>
        <li class="nav-item">
            <a class="nav-link" data-toggle="tab" href="#tab-ortu"><i class="fas fa-users mr-1"></i> Orang Tua</a>
        </li>
    </ul>

    <div class="tab-content pt-3">
        {{-- TAB 1: IDENTITAS --}}
        <div class="tab-pane fade show active" id="tab-identitas">
            <div class="row">
                <div class="col-md-4">
                    <div class="form-group">
                        <label for="nis">NIS <span class="text-danger">*</span></label>
                        <input id="nis" class="form-control" type="text" name="nis" autocomplete="off">
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="form-group">
                        <label for="nisn">NISN</label>
                        <input id="nisn" class="form-control" type="text" name="nisn" autocomplete="off">
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="form-group">
                        <label for="nik">NIK</label>
                        <input id="nik" class="form-control" type="text" name="nik" autocomplete="off">
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-md-6">
                    <div class="form-group">
                        <label for="nama_lengkap">Nama Lengkap <span class="text-danger">*</span></label>
                        <input id="nama_lengkap" class="form-control" type="text" name="nama_lengkap" autocomplete="off">
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="form-group">
                        <label for="nama_panggilan">Nama Panggilan</label>
                        <input id="nama_panggilan" class="form-control" type="text" name="nama_panggilan" autocomplete="off">
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="form-group">
                        <label for="jenis_kelamin">Jenis Kelamin <span class="text-danger">*</span></label>
                        <select name="jenis_kelamin" id="jenis_kelamin" class="form-control">
                            <option disabled selected>Pilih</option>
                            <option value="L">Laki-laki</option>
                            <option value="P">Perempuan</option>
                        </select>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-md-4">
                    <div class="form-group">
                        <label for="tempat_lahir">Tempat Lahir</label>
                        <input id="tempat_lahir" class="form-control" type="text" name="tempat_lahir" autocomplete="off">
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="form-group">
                        <label for="tanggal_lahir">Tanggal Lahir <span class="text-danger">*</span></label>
                        <input id="tanggal_lahir" class="form-control" type="date" name="tanggal_lahir">
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="form-group">
                        <label for="no_kk">No. KK</label>
                        <input id="no_kk" class="form-control" type="text" name="no_kk" autocomplete="off">
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-md-3">
                    <div class="form-group">
                        <label for="anak_ke">Anak Ke</label>
                        <input id="anak_ke" class="form-control" type="number" name="anak_ke" min="1">
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="form-group">
                        <label for="jumlah_saudara">Jumlah Saudara</label>
                        <input id="jumlah_saudara" class="form-control" type="number" name="jumlah_saudara" min="0">
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group">
                        <label for="foto">Foto Siswa</label>
                        <input id="foto" class="form-control" type="file" name="foto" accept="image/*">
                    </div>
                </div>
            </div>
        </div>

        {{-- TAB 2: AKADEMIK --}}
        <div class="tab-pane fade" id="tab-akademik">
            <div class="row">
                <div class="col-md-4">
                    <div class="form-group">
                        <label for="academic_year_id">Tahun Pelajaran</label>
                        <select name="academic_year_id" id="academic_year_id" class="form-control">
                            <option value="">Pilih</option>
                            @foreach ($academicYears as $ay)
                                <option value="{{ $ay->id }}">{{ $ay->academic_year }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="form-group">
                        <label for="student_class_group_id">Kelas</label>
                        <select name="student_class_group_id" id="student_class_group_id" class="form-control">
                            <option value="">Pilih</option>
                            @foreach ($classGroups as $cg)
                                <option value="{{ $cg->id }}">{{ $cg->class_group }} {{ $cg->sub_class_group }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="form-group">
                        <label for="student_status_id">Status Siswa</label>
                        <select name="student_status_id" id="student_status_id" class="form-control">
                            <option value="">Pilih</option>
                            @foreach ($studentStatuses as $ss)
                                <option value="{{ $ss->id }}">{{ $ss->student_status_name }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-md-4">
                    <div class="form-group">
                        <label for="student_residence_id">Tempat Tinggal</label>
                        <select name="student_residence_id" id="student_residence_id" class="form-control">
                            <option value="">Pilih</option>
                            @foreach ($residences as $r)
                                <option value="{{ $r->id }}">{{ $r->residences_name }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="form-group">
                        <label for="tanggal_masuk">Tanggal Masuk</label>
                        <input id="tanggal_masuk" class="form-control" type="date" name="tanggal_masuk">
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="form-group">
                        <label for="asal_sekolah">Asal Sekolah</label>
                        <input id="asal_sekolah" class="form-control" type="text" name="asal_sekolah" autocomplete="off">
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-md-4">
                    <div class="form-group">
                        <label for="no_ijazah">No. Ijazah</label>
                        <input id="no_ijazah" class="form-control" type="text" name="no_ijazah" autocomplete="off">
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="form-group">
                        <label>Status Aktif</label><br>
                        <div class="custom-control custom-switch">
                            <input type="checkbox" class="custom-control-input" id="is_active" name="is_active" checked>
                            <label class="custom-control-label" for="is_active">Aktif</label>
                        </div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="form-group">
                        <label for="keterangan">Keterangan</label>
                        <textarea id="keterangan" class="form-control" name="keterangan" rows="2"></textarea>
                    </div>
                </div>
            </div>
        </div>

        {{-- TAB 3: ALAMAT & KONTAK --}}
        <div class="tab-pane fade" id="tab-alamat">
            <div class="row">
                <div class="col-md-12">
                    <div class="form-group">
                        <label for="alamat">Alamat Lengkap</label>
                        <textarea id="alamat" class="form-control" name="alamat" rows="2"></textarea>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-md-3">
                    <div class="form-group">
                        <label for="rt">RT</label>
                        <input id="rt" class="form-control" type="text" name="rt" autocomplete="off">
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="form-group">
                        <label for="rw">RW</label>
                        <input id="rw" class="form-control" type="text" name="rw" autocomplete="off">
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="form-group">
                        <label for="desa">Desa/Kelurahan</label>
                        <input id="desa" class="form-control" type="text" name="desa" autocomplete="off">
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="form-group">
                        <label for="kecamatan">Kecamatan</label>
                        <input id="kecamatan" class="form-control" type="text" name="kecamatan" autocomplete="off">
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-md-4">
                    <div class="form-group">
                        <label for="kabupaten">Kabupaten/Kota</label>
                        <input id="kabupaten" class="form-control" type="text" name="kabupaten" autocomplete="off">
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="form-group">
                        <label for="provinsi">Provinsi</label>
                        <input id="provinsi" class="form-control" type="text" name="provinsi" autocomplete="off">
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="form-group">
                        <label for="kode_pos">Kode Pos</label>
                        <input id="kode_pos" class="form-control" type="text" name="kode_pos" autocomplete="off">
                    </div>
                </div>
            </div>
            <hr>
            <h6 class="text-muted"><i class="fas fa-phone mr-1"></i> Kontak & Data Fisik</h6>
            <div class="row">
                <div class="col-md-3">
                    <div class="form-group">
                        <label for="no_hp">No. HP</label>
                        <input id="no_hp" class="form-control" type="text" name="no_hp" autocomplete="off">
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="form-group">
                        <label for="email">Email</label>
                        <input id="email" class="form-control" type="email" name="email" autocomplete="off">
                    </div>
                </div>
                <div class="col-md-2">
                    <div class="form-group">
                        <label for="tinggi_badan">Tinggi (cm)</label>
                        <input id="tinggi_badan" class="form-control" type="text" name="tinggi_badan">
                    </div>
                </div>
                <div class="col-md-2">
                    <div class="form-group">
                        <label for="berat_badan">Berat (kg)</label>
                        <input id="berat_badan" class="form-control" type="text" name="berat_badan">
                    </div>
                </div>
                <div class="col-md-2">
                    <div class="form-group">
                        <label for="golongan_darah">Gol. Darah</label>
                        <select name="golongan_darah" id="golongan_darah" class="form-control">
                            <option value="">Pilih</option>
                            <option value="A">A</option>
                            <option value="B">B</option>
                            <option value="AB">AB</option>
                            <option value="O">O</option>
                        </select>
                    </div>
                </div>
            </div>
        </div>

        {{-- TAB 4: DATA ORANG TUA --}}
        <div class="tab-pane fade" id="tab-ortu">
            <h6 class="text-primary"><i class="fas fa-male mr-1"></i> Data Ayah</h6>
            <div class="row">
                <div class="col-md-4">
                    <div class="form-group">
                        <label for="father_name">Nama Ayah</label>
                        <input id="father_name" class="form-control" type="text" name="father_name" autocomplete="off">
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="form-group">
                        <label for="father_nik">NIK Ayah</label>
                        <input id="father_nik" class="form-control" type="text" name="father_nik" autocomplete="off">
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="form-group">
                        <label for="father_phone">No. HP Ayah</label>
                        <input id="father_phone" class="form-control" type="text" name="father_phone" autocomplete="off">
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-md-6">
                    <div class="form-group">
                        <label for="father_education_id">Pendidikan Ayah</label>
                        <select name="father_education_id" id="father_education_id" class="form-control">
                            <option value="">Pilih</option>
                            @foreach ($educations as $edu)
                                <option value="{{ $edu->id }}">{{ $edu->education_name }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group">
                        <label for="father_income_id">Penghasilan Ayah</label>
                        <select name="father_income_id" id="father_income_id" class="form-control">
                            <option value="">Pilih</option>
                            @foreach ($monthlyIncomes as $mi)
                                <option value="{{ $mi->id }}">{{ $mi->monthly_incomes_name }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
            </div>

            <hr>
            <h6 class="text-danger"><i class="fas fa-female mr-1"></i> Data Ibu</h6>
            <div class="row">
                <div class="col-md-4">
                    <div class="form-group">
                        <label for="mother_name">Nama Ibu</label>
                        <input id="mother_name" class="form-control" type="text" name="mother_name" autocomplete="off">
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="form-group">
                        <label for="mother_nik">NIK Ibu</label>
                        <input id="mother_nik" class="form-control" type="text" name="mother_nik" autocomplete="off">
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="form-group">
                        <label for="mother_phone">No. HP Ibu</label>
                        <input id="mother_phone" class="form-control" type="text" name="mother_phone" autocomplete="off">
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-md-6">
                    <div class="form-group">
                        <label for="mother_education_id">Pendidikan Ibu</label>
                        <select name="mother_education_id" id="mother_education_id" class="form-control">
                            <option value="">Pilih</option>
                            @foreach ($educations as $edu)
                                <option value="{{ $edu->id }}">{{ $edu->education_name }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group">
                        <label for="mother_income_id">Penghasilan Ibu</label>
                        <select name="mother_income_id" id="mother_income_id" class="form-control">
                            <option value="">Pilih</option>
                            @foreach ($monthlyIncomes as $mi)
                                <option value="{{ $mi->id }}">{{ $mi->monthly_incomes_name }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- Hidden fields for profile --}}
    <input type="hidden" name="profile_nik" id="profile_nik">
    <input type="hidden" name="profile_no_kk" id="profile_no_kk">

    <x-slot name="footer">
        <button type="button" onclick="submitForm(this.form)" class="btn btn-sm btn-outline-primary" id="submitBtn">
            <span id="spinner-border" class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span>
            <i class="fas fa-save mr-1"></i> Simpan
        </button>
        <button type="button" data-dismiss="modal" class="btn btn-sm btn-outline-danger">
            <i class="fas fa-times"></i> Close
        </button>
    </x-slot>
</x-modal>
