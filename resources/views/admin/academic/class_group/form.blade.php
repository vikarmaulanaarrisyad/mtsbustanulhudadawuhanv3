<x-modal data-backdrop="static" data-keyboard="false" size="modal-md">
    <x-slot name="title">
        Tambah
    </x-slot>

    @method('POST')

    <div class="row">
        <div class="col-lg-12 col-md-12 col-12">
            <div class="form-group">
                <label for="academic_year_id">Tahun Pelajaran <span class="text-danger">*</span></label>
                <select name="academic_year_id" id="academic_year_id" class="form-control select2">
                    @foreach($academicYears as $ay)
                        <option value="{{ $ay->id }}" {{ $ay->current_semester ? 'selected' : '' }}>
                            {{ $ay->academic_year }} - {{ $ay->semester->semester_name }}
                        </option>
                    @endforeach
                </select>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-lg-12 col-md-12 col-12">
            <div class="form-group">
                <label for="class_group">Nama Kelas <span class="text-danger">*</span></label>
                <input id="class_group" class="form-control" type="text" name="class_group" placeholder="Kelas 7"
                    autocomplete="off">
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-lg-12 col-md-12 col-12">
            <div class="form-group">
                <label for="sub_class_group">Rombel <span class="text-danger">*</span></label>
                <input id="sub_class_group" class="form-control" type="text" name="sub_class_group" placeholder="A"
                    autocomplete="off">
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-lg-12 col-md-12 col-12">
            <div class="form-group">
                <label for="class_level">Tingkat Kelas <span class="text-danger">*</span></label>
                <select name="class_level" id="class_level" class="form-control">
                    <option disabled selected>Pilih salah satu</option>
                    @for($i=1; $i<=12; $i++)
                        <option value="{{ $i }}">{{ $i }}</option>
                    @endfor
                </select>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-lg-12 col-md-12 col-12">
            <div class="form-group">
                <label for="teacher_id">Wali Kelas</label>
                <select name="teacher_id" id="teacher_id" class="form-control select2">
                    <option value="">-- Pilih Wali Kelas --</option>
                    @foreach($teachers as $teacher)
                        <option value="{{ $teacher->id }}">{{ $teacher->name }}</option>
                    @endforeach
                </select>
            </div>
        </div>
    </div>

    <x-slot name="footer">
        <button type="button" onclick="submitForm(this.form)" class="btn btn-sm btn-outline-primary" id="submitBtn">
            <span id="spinner-border" class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span>
            <i class="fas fa-save mr-1"></i>
            Simpan</button>
        <button type="button" data-dismiss="modal" class="btn btn-sm btn-outline-danger">
            <i class="fas fa-times"></i>
            Close
        </button>
    </x-slot>
</x-modal>
