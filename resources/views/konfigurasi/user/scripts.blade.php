@include('includes.datatable')
@include('includes.select2')

@push('scripts')
    <script>
        let table;
        let modal = '#modal-form';
        let button = '#submitBtn';

        $(function() {
            $('#spinner-border').hide();
        });

        table = $('#userTable').DataTable({
            processing: false,
            serverSide: true,
            autoWidth: false,
            responsive: true,
            ajax: {
                url: '{{ route('users.data') }}',
                data: function(d) {
                    d.role = $('#roleTabs .nav-link.active').attr('data-role');
                }
            },
            columns: [{
                    data: 'DT_RowIndex',
                    name: 'DT_RowIndex',
                    orderable: false,
                    searchable: false
                },
                {
                    data: 'name',
                    name: 'name'
                },
                {
                    data: 'email',
                    name: 'email'
                },
                {
                    data: 'role_names',
                    name: 'role_names',
                    orderable: false,
                    searchable: false
                },
                {
                    data: 'action',
                    name: 'action',
                    orderable: false,
                    searchable: false
                },
            ]
        });
        
        // Tab Filtering Logic
        $('#roleTabs a[data-toggle="pill"]').on('shown.bs.tab', function (e) {
            table.ajax.reload();
        });

        $('#roles').select2({
            placeholder: 'Pilih Role User',
            theme: 'bootstrap4',
            closeOnSelect: true,
            allowClear: true,
            ajax: {
                url: '{{ route('users.role_search') }}',
                processResults: function(data) {
                    return {
                        results: data.map(function(item) {
                            return {
                                id: item.id,
                                text: item.name
                            }
                        })
                    }
                }
            }
        });
 
        $('#permission_ids').select2({
            placeholder: 'Pilih Izin Langsung',
            theme: 'bootstrap4',
            closeOnSelect: false,
            allowClear: true,
        });
 
        // Sync toggles with select2
        $('.menu-permission-check').on('change', function() {
            const permissionName = $(this).val();
            const isChecked = $(this).is(':checked');
            const select2 = $('#permission_ids');
            let currentValues = select2.val() || [];
 
            // Find the ID for this permission name
            const option = select2.find(`option[data-name="${permissionName}"]`);
            if (option.length) {
                const id = option.val();
                if (isChecked) {
                    if (!currentValues.includes(id)) {
                        currentValues.push(id);
                    }
                } else {
                    currentValues = currentValues.filter(v => v !== id);
                }
                select2.val(currentValues).trigger('change');
            }
        });
 
        $('#permission_ids').on('change', function() {
            const currentValues = $(this).val() || [];
            
            $('.menu-permission-check').each(function() {
                const permissionName = $(this).val();
                const option = $('#permission_ids').find(`option[data-name="${permissionName}"]`);
                if (option.length) {
                    const id = option.val();
                    $(this).prop('checked', currentValues.includes(id.toString()));
                }
            });
            
            // Sync check all button
            const totalChecks = $('.menu-permission-check').length;
            const checkedCount = $('.menu-permission-check:checked').length;
            $('#checkAllPermissions').prop('checked', totalChecks === checkedCount && totalChecks > 0);
            
            // Update summary count display
            $('#checkedCountDisplay').text(currentValues.length);
        });

        $('#checkAllPermissions').on('change', function() {
            const isChecked = $(this).is(':checked');
            $('.menu-permission-check').prop('checked', isChecked).trigger('change');
        });

        function addForm(url, title = 'Form Tambah Data User') {
            $(modal).modal('show');
            $(`${modal} .modal-title`).text(title);
            $(`${modal} form`).attr('action', url);
            $(`${modal} [name=_method]`).val('post');

            $(`${modal} #name`).prop('disabled', false);
            $(`${modal} #username`).prop('disabled', false);
            $(`${modal} #email`).prop('disabled', false);
            $(`${modal} #roles`).prop('disabled', false);
            $(`${modal} #permission_ids`).prop('disabled', false);
            $(`${modal} #passwordRow`).show();
            $(`${modal} #submitBtn`).show();
 
            $('#roles').empty().trigger('change');
            $('#permission_ids').val(null).trigger('change');
            $('.menu-permission-check').prop('checked', false);
            $('.menu-permission-check').prop('disabled', false);
            $('#checkAllPermissions').prop('checked', false).prop('disabled', false);

            resetForm(`${modal} form`);
        }

        function detailForm(url, title = 'Form Detail User') {
            $.ajax({
                url: url,
                dataType: 'JSON',
                type: 'GET',
                success: function(response) {
                    console.log('roles ', response.data.roles);
                    $(modal).modal('show');
                    $(`${modal} .modal-title`).text(title);
                    $(`${modal} #submitBtn`).hide();

                    $(`${modal} #name`).prop('disabled', true);
                    $(`${modal} #username`).prop('disabled', true);
                    $(`${modal} #email`).prop('disabled', true);
                    $(`${modal} #roles`).prop('disabled', true);
                    $(`${modal} #permission_ids`).prop('disabled', true);
                    $('.menu-permission-check').prop('disabled', true);
                    $('#checkAllPermissions').prop('disabled', true);
                    $(`${modal} #passwordRow`).hide();

                    resetForm(`${modal} form`);
                    loopForm(response.data);

                    $('#roles').empty(); // Clear previous options
                    for (let role of response.data.roles) { // Assuming roles is an array
                        var option = new Option(role.name, role.id, true, true);
                        $('#roles').append(option);
                    }
                    $('#roles').trigger('change');

                    if (response.data.permissions) {
                        let permissionIds = response.data.permissions.map(p => p.id);
                        $('#permission_ids').val(permissionIds).trigger('change');
                    }
                },
                error: function(errors) {
                    Swal.fire({
                        icon: 'error',
                        title: 'Opps! Gagal',
                        text: errors.responseJSON.message,
                        showConfirmButton: true,
                    });
                }
            });
        }

        function editForm(url, idUser, title = 'Form Edit Data User') {
            $.ajax({
                url: url,
                type: 'GET', // Ubah metode menjadi GET untuk mendapatkan data peran
                dataType: 'JSON',
                success: function(response) {
                    $(modal).modal('show');
                    $(`${modal} .modal-title`).text(title);
                    $(`${modal} form`).attr('action', `${url}/update`);
                    $(`${modal} [name=_method]`).val('PUT');

                    $(`${modal} #name`).prop('disabled', false);
                    $(`${modal} #username`).prop('disabled', false);
                    $(`${modal} #email`).prop('disabled', false);
                    $(`${modal} #roles`).prop('disabled', false);
                    $(`${modal} #permission_ids`).prop('disabled', false);
                    $('.menu-permission-check').prop('disabled', false);
                    $('#checkAllPermissions').prop('disabled', false);
                    $(`${modal} #submitBtn`).show();
                    $(`${modal} #passwordRow`).hide();

                    resetForm(`${modal} form`);
                    loopForm(response.data);

                    $('#roles').empty(); // Clear previous options
                    for (let role of response.data.roles) { // Assuming roles is an array
                        var option = new Option(role.name, role.id, true, true);
                        $('#roles').append(option);
                    }
                    $('#roles').trigger('change');

                    if (response.data.permissions) {
                        let permissionIds = response.data.permissions.map(p => p.id);
                        $('#permission_ids').val(permissionIds).trigger('change');
                    }
                },
                error: function(xhr, status, error) {
                    var errorMessage = xhr.status + ': ' + xhr.statusText;
                    Swal.fire({
                        icon: 'error',
                        title: 'Opps! Gagal',
                        text: errorMessage,
                        showConfirmButton: true,
                    });
                }
            })
        }

        function deleteData(url, name, title = 'Delete User') {
            const swalWithBootstrapButtons = Swal.mixin({
                customClass: {
                    confirmButton: 'btn btn-success',
                    cancelButton: 'btn btn-danger'
                },
                buttonsStyling: true,
            })
            swalWithBootstrapButtons.fire({
                title: 'Delete Data!',
                text: 'Apakah anda yakin ingin menghapus ' + name + ' ?',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#aaa',
                confirmButtonText: 'Iya !',
                cancelButtonText: 'Batalkan',
                reverseButtons: true
            }).then((result) => {
                if (result.isConfirmed) {
                    $.ajax({
                        type: "delete",
                        url: url,
                        dataType: "json",
                        success: function(response) {
                            if (response.status = 200) {
                                Swal.fire({
                                    icon: 'success',
                                    title: 'Berhasil',
                                    text: response.message,
                                    showConfirmButton: false,
                                    timer: 3000
                                }).then(() => {
                                    table.ajax.reload();
                                })
                            }
                        },
                        error: function(xhr, status, error) {
                            // Menampilkan pesan error
                            Swal.fire({
                                icon: 'error',
                                title: 'Opps! Gagal',
                                text: xhr.responseJSON.message,
                                showConfirmButton: true,
                            }).then(() => {
                                // Refresh tabel atau lakukan operasi lain yang diperlukan
                                table.ajax.reload();
                            });
                        }
                    });
                }
            });
        }

        function submitForm(originalForm) {
            $(button).prop('disabled', true);
            $('#spinner-border').show();

            $.post({
                    url: $(originalForm).attr('action'),
                    data: new FormData(originalForm),
                    dataType: 'JSON',
                    contentType: false,
                    cache: false,
                    processData: false
                })
                .done(response => {
                    $(modal).modal('hide');
                    if (response.status = 200) {
                        Swal.fire({
                            icon: 'success',
                            title: 'Berhasil',
                            text: response.message,
                            showConfirmButton: false,
                            timer: 3000
                        }).then(() => {
                            $(button).prop('disabled', false);
                            $('#spinner-border').hide();

                            table.ajax.reload();
                        })
                    }
                })
                .fail(errors => {
                    $('#spinner-border').hide();
                    $(button).prop('disabled', false);
                    Swal.fire({
                        icon: 'error',
                        title: 'Opps! Gagal',
                        text: errors.responseJSON.message,
                        showConfirmButton: true,
                    });
                    if (errors.status == 422) {
                        $('#spinner-border').hide()
                        $(button).prop('disabled', false);
                        loopErrors(errors.responseJSON.errors);
                        return;
                    }
                });
        }

        function resetPassword(url, name) {
            $('#modalResetPassword').modal('show');
            $('#resetUserName').text(name);
            $('#formResetPassword').attr('action', url);
            $('#formResetPassword')[0].reset();
        }

        function submitResetPassword(form) {
            let url = $(form).attr('action');
            let data = $(form).serialize();
            
            $('#btnSubmitReset').prop('disabled', true).text('Sedang Memproses...');

            $.post(url, data)
                .done(response => {
                    $('#modalResetPassword').modal('hide');
                    Swal.fire({
                        icon: 'success',
                        title: 'Berhasil',
                        text: response.message,
                    });
                })
                .fail(errors => {
                    let message = errors.responseJSON.message || 'Gagal mereset password';
                    if (errors.status == 422) {
                        message = 'Password minimal 6 karakter dan harus sama dengan konfirmasi';
                    }
                    Swal.fire({
                        icon: 'error',
                        title: 'Gagal',
                        text: message,
                    });
                })
                .always(() => {
                    $('#btnSubmitReset').prop('disabled', false).text('Simpan Password');
                });
        }
    </script>
@endpush
