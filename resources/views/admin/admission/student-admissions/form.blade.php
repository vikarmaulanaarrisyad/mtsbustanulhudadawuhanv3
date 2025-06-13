<x-modal data-backdrop="static" data-keyboard="false" size="modal-md">
    <x-slot name="title">
        Tambah Pendaftaran
    </x-slot>

    @method('POST')

    <div class="row">
        <div class="col-lg-12">
            <div class="form-group">
                <label for="admission_status">Status Pendaftaran <span class="text-danger">*</span></label>
                <select id="admission_status" name="admission_status" class="form-control" required>
                    <option value="open">Open</option>
                    <option value="close" selected>Close</option>
                </select>
            </div>
        </div>

        <div class="col-lg-12">
            <div class="form-group">
                <label for="admission_year">Tahun Pendaftaran <span class="text-danger">*</span></label>
                <input id="admission_year" name="admission_year" type="number" class="form-control" min="2000"
                    max="2100" placeholder="Contoh: 2025" required>
            </div>
        </div>

        <div class="col-lg-12">
            <div class="form-group">
                <label for="admission_start_date">Tanggal Mulai Pendaftaran <span class="text-danger">*</span></label>
                <div class="input-group datepicker" id="admission_start_date" data-target-input="nearest">
                    <input type="text" name="admission_start_date" class="form-control datetimepicker-input"
                        data-target="#admission_start_date" data-toggle="datetimepicker" autocomplete="off" />

                    <div class="input-group-append" data-target="#admission_start_date" data-toggle="datetimepicker">
                        <div class="input-group-text"><i class="fa fa-calendar"></i></div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-lg-12">
            <div class="form-group">
                <label for="admission_end_date">Tanggal Akhir Pendaftaran <span class="text-danger">*</span></label>
                <div class="input-group datepicker" id="admission_end_date" data-target-input="nearest">
                    <input type="text" name="admission_end_date" class="form-control datetimepicker-input"
                        data-target="#admission_end_date" data-toggle="datetimepicker" autocomplete="off" />

                    <div class="input-group-append" data-target="#admission_end_date" data-toggle="datetimepicker">
                        <div class="input-group-text"><i class="fa fa-calendar"></i></div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-lg-12">
            <div class="form-group">
                <label for="announcement_start_date">Tanggal Awal Pengumuman <span class="text-danger">*</span></label>
                <div class="input-group datepicker" id="announcement_start_date" data-target-input="nearest">
                    <input type="text" name="announcement_start_date" class="form-control datetimepicker-input"
                        data-target="#announcement_start_date" data-toggle="datetimepicker" autocomplete="off" />

                    <div class="input-group-append" data-target="#announcement_start_date" data-toggle="datetimepicker">
                        <div class="input-group-text"><i class="fa fa-calendar"></i></div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-lg-12">
            <div class="form-group">
                <label for="announcement_end_date">Tanggal Akhir Pengumuman <span class="text-danger">*</span></label>
                <div class="input-group datepicker" id="announcement_end_date" data-target-input="nearest">
                    <input type="text" name="announcement_end_date" class="form-control datetimepicker-input"
                        data-target="#announcement_end_date" data-toggle="datetimepicker" autocomplete="off" />

                    <div class="input-group-append" data-target="#announcement_end_date" data-toggle="datetimepicker">
                        <div class="input-group-text"><i class="fa fa-calendar"></i></div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <x-slot name="footer">
        <button type="button" onclick="submitForm(this.form)" class="btn btn-sm btn-outline-primary" id="submitBtn">
            <span id="spinner-border" class="spinner-border spinner-border-sm" role="status"
                aria-hidden="true"></span>
            <i class="fas fa-save mr-1"></i>
            Simpan
        </button>
        <button type="button" data-dismiss="modal" class="btn btn-sm btn-outline-danger">
            <i class="fas fa-times"></i>
            Close
        </button>
    </x-slot>
</x-modal>
