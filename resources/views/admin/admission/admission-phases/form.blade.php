<x-modal data-backdrop="static" data-keyboard="false" size="modal-md">
    <x-slot name="title">
        Tambah Pendaftaran
    </x-slot>

    @method('POST')

    <div class="row">
        <div class="col-lg-12">
            <div class="form-group">
                <label for="phase_name">Gelombang Pendaftaran</label>
                <input id="phase_name" class="form-control" type="text" name="phase_name" autocomplete="off"
                    placeholder="contoh: Gelombang 1">
            </div>
        </div>
        <div class="col-lg-12">
            <div class="form-group">
                <label for="phase_start_date">Tanggal Mulai Gelombang Pendaftaran <span
                        class="text-danger">*</span></label>
                <div class="input-group datepicker" id="phase_start_date" data-target-input="nearest">
                    <input type="text" name="phase_start_date" class="form-control datetimepicker-input"
                        data-target="#phase_start_date" data-toggle="datetimepicker" autocomplete="off" />

                    <div class="input-group-append" data-target="#phase_start_date" data-toggle="datetimepicker">
                        <div class="input-group-text"><i class="fa fa-calendar"></i></div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-lg-12">
            <div class="form-group">
                <label for="phase_end_date">Tanggal Akhir Gelombang Pendaftaran <span
                        class="text-danger">*</span></label>
                <div class="input-group datepicker" id="phase_end_date" data-target-input="nearest">
                    <input type="text" name="phase_end_date" class="form-control datetimepicker-input"
                        data-target="#phase_end_date" data-toggle="datetimepicker" autocomplete="off" />

                    <div class="input-group-append" data-target="#phase_end_date" data-toggle="datetimepicker">
                        <div class="input-group-text"><i class="fa fa-calendar"></i></div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <x-slot name="footer">
        <button type="button" onclick="submitForm(this.form)" class="btn btn-sm btn-outline-primary" id="submitBtn">
            <span id="spinner-border" class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span>
            <i class="fas fa-save mr-1"></i>
            Simpan
        </button>
        <button type="button" data-dismiss="modal" class="btn btn-sm btn-outline-danger">
            <i class="fas fa-times"></i>
            Close
        </button>
    </x-slot>
</x-modal>
