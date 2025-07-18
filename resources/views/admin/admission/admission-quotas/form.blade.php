<x-modal data-backdrop="static" data-keyboard="false" size="modal-md">
    <x-slot name="title">
        Tambah Pendaftaran
    </x-slot>

    @method('POST')

    <div class="row">
        <div class="col-lg-12">
            <div class="form-group">
                <label for="admission_type_name">Jalur Pendaftaran</label>
                <select name="admission_types_id" id="admission_types_id" class="form-control">
                    <option disabled selected>Pilih salah satu</option>
                    @foreach ($admissionTypes as $admissionType)
                        <option value="{{ $admissionType->id }}">{{ $admissionType->admission_type_name }}</option>
                    @endforeach
                </select>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-lg-12">
            <div class="form-group">
                <label for="quota">Kuota Pendaftaran</label>
                <input id="quota" class="form-control" type="number" min="0" name="quota"
                    autocomplete="off" value="0">
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
