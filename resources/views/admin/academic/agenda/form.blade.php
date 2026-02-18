<x-modal data-backdrop="static" data-keyboard="false" size="modal-md">
    <x-slot name="title">
        Add School Agenda
    </x-slot>

    @method('POST')

    <div class="row">

        {{-- Title --}}
        <div class="col-12">
            <div class="form-group">
                <label for="title">Title <span class="text-danger">*</span></label>
                <input type="text" name="title" id="title"
                    class="form-control"
                    placeholder="Agenda title"
                    autocomplete="off" required>
            </div>
        </div>

        {{-- Description --}}
        <div class="col-12">
            <div class="form-group">
                <label for="description">Description</label>
                <textarea name="description" id="description"
                    class="form-control"
                    rows="3"
                    placeholder="Agenda description"></textarea>
            </div>
        </div>

        {{-- Start Date --}}
        <div class="col-md-6">
            <div class="form-group">
                <label for="start_date">Start Date <span class="text-danger">*</span></label>
                <input type="date" name="start_date" id="start_date"
                    class="form-control" required>
            </div>
        </div>

        {{-- End Date --}}
        <div class="col-md-6">
            <div class="form-group">
                <label for="end_date">End Date</label>
                <input type="date" name="end_date" id="end_date"
                    class="form-control">
            </div>
        </div>

        {{-- Start Time --}}
        <div class="col-md-6">
            <div class="form-group">
                <label for="start_time">Start Time</label>
                <input type="time" name="start_time" id="start_time"
                    class="form-control">
            </div>
        </div>

        {{-- End Time --}}
        <div class="col-md-6">
            <div class="form-group">
                <label for="end_time">End Time</label>
                <input type="time" name="end_time" id="end_time"
                    class="form-control">
            </div>
        </div>

        {{-- Location --}}
        <div class="col-12">
            <div class="form-group">
                <label for="location">Location</label>
                <input type="text" name="location" id="location"
                    class="form-control"
                    placeholder="School hall / classroom">
            </div>
        </div>

        {{-- Responsible Person --}}
        <div class="col-12">
            <div class="form-group">
                <label for="responsible_person">Responsible Person</label>
                <input type="text" name="responsible_person" id="responsible_person"
                    class="form-control"
                    placeholder="Teacher / Committee">
            </div>
        </div>

        {{-- Category --}}
        <div class="col-md-6">
            <div class="form-group">
                <label for="category">Category <span class="text-danger">*</span></label>
                <select name="category" id="category" class="form-control" required>
                    <option value="">-- Select --</option>
                    <option value="academic">Academic</option>
                    <option value="non_academic">Non Academic</option>
                    <option value="holiday">Holiday</option>
                </select>
            </div>
        </div>

        {{-- Status --}}
        <div class="col-md-6">
            <div class="form-group">
                <label for="status">Status <span class="text-danger">*</span></label>
                <select name="status" id="status" class="form-control" required>
                    <option value="draft">Draft</option>
                    <option value="active">Active</option>
                    <option value="completed">Completed</option>
                </select>
            </div>
        </div>

    </div>

    <x-slot name="footer">
        <button type="button"
            onclick="submitForm(this.form)"
            class="btn btn-sm btn-outline-primary"
            id="submitBtn">
            <span id="spinner-border"
                class="spinner-border spinner-border-sm d-none"
                role="status"
                aria-hidden="true"></span>
            <i class="fas fa-save mr-1"></i>
            Save
        </button>

        <button type="button"
            data-dismiss="modal"
            class="btn btn-sm btn-outline-danger">
            <i class="fas fa-times"></i>
            Close
        </button>
    </x-slot>
</x-modal>
