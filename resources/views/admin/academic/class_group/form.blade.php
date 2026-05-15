<div class="modal fade animate__animated animate__zoomIn" id="modal-form" tabindex="-1" role="dialog" aria-labelledby="modal-form">
    <div class="modal-dialog modal-md" role="document">
        <form action="" method="post" class="form-horizontal">
            @csrf
            @method('POST')

            <div class="modal-content border-0 shadow-lg-premium" style="border-radius: 20px; overflow: hidden;">
                <!-- MODAL HEADER WITH TEAL GRADIENT -->
                <div class="modal-header bg-gradient-info text-white border-0 py-4 position-relative">
                    <div class="position-relative" style="z-index: 1;">
                        <h5 class="modal-title font-weight-bold mb-0">
                            <i class="fas fa-school mr-2"></i> 
                            Pengaturan Rombongan Belajar
                        </h5>
                        <p class="mb-0 opacity-8 small">Kelola data kelas dan wali kelas untuk tahun ajaran aktif.</p>
                    </div>
                    <button type="button" class="close text-white" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                    <!-- Decorative Circle -->
                    <div class="bg-circle-header"></div>
                </div>

                <div class="modal-body p-4 bg-light-soft">
                    <div class="card border-0 shadow-sm rounded-20 p-4">
                        <div class="form-group mb-4">
                            <label class="text-xs font-weight-bold text-muted uppercase">Tahun Pelajaran <span class="text-danger">*</span></label>
                            <select name="academic_year_id" id="academic_year_id" class="form-control select2 rounded-pill px-3">
                                @foreach($academicYears as $ay)
                                    <option value="{{ $ay->id }}" {{ $ay->current_semester ? 'selected' : '' }}>
                                        {{ $ay->academic_year }} - {{ $ay->semester->semester_name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <div class="row">
                            <div class="col-md-7">
                                <div class="form-group mb-4">
                                    <label class="text-xs font-weight-bold text-muted uppercase">Nama Kelas <span class="text-danger">*</span></label>
                                    <div class="input-group-premium">
                                        <i class="fas fa-door-open"></i>
                                        <input type="text" name="class_group" id="class_group" class="form-control" placeholder="Contoh: Kelas 7" required>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-5">
                                <div class="form-group mb-4">
                                    <label class="text-xs font-weight-bold text-muted uppercase">Rombel / Sub <span class="text-danger">*</span></label>
                                    <div class="input-group-premium">
                                        <i class="fas fa-layer-group"></i>
                                        <input type="text" name="sub_class_group" id="sub_class_group" class="form-control" placeholder="Contoh: A" required>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="form-group mb-4">
                            <label class="text-xs font-weight-bold text-muted uppercase">Tingkat Pendidikan <span class="text-danger">*</span></label>
                            <select name="class_level" id="class_level" class="form-control rounded-pill px-3 border-2">
                                <option disabled selected>-- Pilih Tingkat --</option>
                                <optgroup label="Madrasah Ibtidaiyah (MI)">
                                    <option value="1">Tingkat 1</option>
                                    <option value="2">Tingkat 2</option>
                                    <option value="3">Tingkat 3</option>
                                    <option value="4">Tingkat 4</option>
                                    <option value="5">Tingkat 5</option>
                                    <option value="6">Tingkat 6</option>
                                </optgroup>
                                <optgroup label="Madrasah Tsanawiyah (MTs)">
                                    <option value="7">Tingkat 7</option>
                                    <option value="8">Tingkat 8</option>
                                    <option value="9">Tingkat 9</option>
                                </optgroup>
                                <optgroup label="Madrasah Aliyah (MA)">
                                    <option value="10">Tingkat 10</option>
                                    <option value="11">Tingkat 11</option>
                                    <option value="12">Tingkat 12</option>
                                </optgroup>
                            </select>
                        </div>

                        <div class="form-group mb-0">
                            <label class="text-xs font-weight-bold text-muted uppercase">Wali Kelas</label>
                            <select name="teacher_id" id="teacher_id" class="form-control select2 rounded-pill px-3">
                                <option value="">-- Pilih Wali Kelas --</option>
                                @foreach($teachers as $teacher)
                                    <option value="{{ $teacher->id }}">{{ $teacher->name }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                </div>

                <div class="modal-footer border-0 p-4 bg-white">
                    <button type="button" class="btn btn-light rounded-pill px-4 font-weight-bold shadow-xs" data-dismiss="modal">BATAL</button>
                    <button type="button" onclick="submitForm(this.form)" id="submitBtn" class="btn btn-info rounded-pill px-5 font-weight-bold shadow-info-light">
                        <i class="fas fa-save mr-2"></i> SIMPAN KELAS
                    </button>
                </div>
            </div>
        </form>
    </div>
</div>

<style>
    /* Premium Modal Styling */
    .shadow-lg-premium { box-shadow: 0 15px 50px rgba(0,0,0,0.15) !important; }
    .bg-gradient-info { background: linear-gradient(135deg, #17a2b8 0%, #117a8b 100%) !important; }
    .bg-circle-header { position: absolute; width: 150px; height: 150px; background: rgba(255,255,255,0.1); border-radius: 50%; top: -40px; right: -40px; z-index: 0; }
    .bg-light-soft { background: #f4f7f9; }
    .rounded-20 { border-radius: 20px; }
    .rounded-15 { border-radius: 15px; }
    
    /* Input Group Premium */
    .input-group-premium { 
        display: flex; align-items: center; background: #fff; border: 2px solid #e1e8ef; 
        border-radius: 12px; padding: 0 15px; transition: all 0.3s ease;
    }
    .input-group-premium i { color: #adb5bd; font-size: 16px; margin-right: 12px; }
    .input-group-premium input { 
        border: none !important; padding: 12px 0 !important; background: transparent !important; 
        box-shadow: none !important; font-weight: 600; color: #2d4154; width: 100%;
    }
    .input-group-premium:focus-within { border-color: #17a2b8; box-shadow: 0 0 15px rgba(23,162,184,0.1); }
    .input-group-premium:focus-within i { color: #17a2b8; }

    .shadow-info-light { box-shadow: 0 4px 15px rgba(23,162,184,0.3); }
    .uppercase { text-transform: uppercase; letter-spacing: 0.5px; }
</style>
