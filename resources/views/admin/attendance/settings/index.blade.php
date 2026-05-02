@extends('layouts.app')

@section('title', 'Pengaturan Absensi')
@section('subtitle', 'Persuratan')

@section('content')
    <div class="row">
        <div class="col-md-8">
            <x-card>
                <x-slot name="header">
                    <h3 class="card-title"><i class="fas fa-clock mr-1"></i> Konfigurasi Waktu Kerja</h3>
                </x-slot>

                <form id="settingForm">
                    @csrf
                    <input type="hidden" name="id" value="{{ $settingAttendace->id }}">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>Jam Masuk (Mulai)</label>
                                <input type="time" name="check_in_start" class="form-control"
                                    value="{{ $settingAttendace->check_in_start }}">
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>Jam Masuk (Selesai/Batas)</label>
                                <input type="time" name="check_in_end" class="form-control"
                                    value="{{ $settingAttendace->check_in_end }}">
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>Jam Pulang (Mulai)</label>
                                <input type="time" name="check_out_start" class="form-control"
                                    value="{{ $settingAttendace->check_out_start }}">
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>Jam Pulang (Selesai/Batas)</label>
                                <input type="time" name="check_out_end" class="form-control"
                                    value="{{ $settingAttendace->check_out_end }}">
                            </div>
                        </div>
                    </div>

                    <div class="form-group">
                        <label>Hari Kerja</label>
                        <div class="d-flex flex-wrap">
                            @php
                                $days = [
                                    1 => 'Senin',
                                    2 => 'Selasa',
                                    3 => 'Rabu',
                                    4 => 'Kamis',
                                    5 => 'Jumat',
                                    6 => 'Sabtu',
                                    0 => 'Minggu',
                                ];
                                $workDays = (array) ($settingAttendace->work_days ?? []);
                            @endphp
                            @foreach ($days as $key => $day)
                                <div class="custom-control custom-checkbox mr-3">
                                    <input class="custom-control-input" type="checkbox" name="work_days[]"
                                        id="day_{{ $key }}" value="{{ $key }}"
                                        {{ in_array((string) $key, array_map('strval', $workDays)) ? 'checked' : '' }}>
                                    <label for="day_{{ $key }}"
                                        class="custom-control-label">{{ $day }}</label>
                                </div>
                            @endforeach
                        </div>
                    </div>

                    <div class="card-footer px-0">
                        <button type="button" onclick="submitSetting()" class="btn btn-primary" id="btnSave">
                            <i class="fas fa-save mr-1"></i> Simpan Perubahan
                        </button>
                    </div>
                </form>
            </x-card>
        </div>

        <div class="col-md-4">
            <div class="info-box bg-info">
                <span class="info-box-icon"><i class="fas fa-info-circle"></i></span>
                <div class="info-box-content">
                    <span class="info-box-text">Informasi</span>
                    <span class="info-box-number text-sm font-weight-normal">
                        Pastikan pengaturan jam sesuai dengan kebijakan Madrasah. Guru hanya dapat melakukan presensi pada
                        rentang waktu yang ditentukan.
                    </span>
                </div>
            </div>

            <x-card>
                <x-slot name="header">
                    <h3 class="card-title"><i class="fas fa-eye mr-1"></i> Ringkasan Pengaturan</h3>
                </x-slot>
                <table class="table table-sm table-striped">
                    <tr>
                        <td><strong>Jam Masuk</strong></td>
                        <td>: {{ $settingAttendace->check_in_start }} - {{ $settingAttendace->check_in_end }}</td>
                    </tr>
                    <tr>
                        <td><strong>Jam Pulang</strong></td>
                        <td>: {{ $settingAttendace->check_out_start }} - {{ $settingAttendace->check_out_end }}</td>
                    </tr>
                    <tr>
                        <td><strong>Hari Kerja</strong></td>
                        <td>:
                            @php
                                $dayNames = [
                                    1 => 'Senin',
                                    2 => 'Selasa',
                                    3 => 'Rabu',
                                    4 => 'Kamis',
                                    5 => 'Jumat',
                                    6 => 'Sabtu',
                                    0 => 'Minggu',
                                ];
                                $activeDays = [];
                                foreach ((array) ($settingAttendace->work_days ?? []) as $d) {
                                    if (isset($dayNames[$d])) {
                                        $activeDays[] = $dayNames[$d];
                                    }
                                }
                            @endphp
                            {{ implode(', ', $activeDays) }}
                        </td>
                    </tr>
                </table>
            </x-card>
        </div>
    </div>
@endsection

@push('scripts')
    <script>
        function submitSetting() {
            $('#btnSave').prop('disabled', true).html('<i class="fas fa-spinner fa-spin mr-1"></i> Menyimpan...');
            $.ajax({
                url: '{{ route('attendance-settings.update') }}',
                type: 'POST',
                data: $('#settingForm').serialize(),
                success: function(response) {
                    Swal.fire({
                        icon: 'success',
                        title: 'Berhasil',
                        text: response.message
                    });
                },
                error: function(xhr) {
                    Swal.fire({
                        icon: 'error',
                        title: 'Gagal',
                        text: xhr.responseJSON?.message || 'Terjadi kesalahan'
                    });
                },
                complete: function() {
                    $('#btnSave').prop('disabled', false).html(
                        '<i class="fas fa-save mr-1"></i> Simpan Perubahan');
                }
            });
        }
    </script>
@endpush
