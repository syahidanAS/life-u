@extends('layouts.app', ['title' => 'Konfigurasi Perangkat'])

@section('content')
<div class="content-body">
    <div class="container-fluid">
        <div class="row page-titles">
            <ol class="breadcrumb">
                @php
                $currentRouteName = Route::current()->uri();
                @endphp
                <li class="breadcrumb-item active"><a href="javascript:void(0)">{{ $currentRouteName }}</a>
                </li>
            </ol>
        </div>

        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h4 class="card-title">Manajemen Perangkat</h4>
                    <button class="btn btn-primary btn-sm col-sm-2" data-bs-toggle="modal"
                        data-bs-target="#modalTambahData">+ Tambah Data</button>
                </div>
                <div class="card-body table-responsive">
                    <div class="d-flex justify-content-between mb-3"></div>
                    <table class="table table-striped table-bordered" id="datatable">
                        <thead>
                            <tr>
                                <th>No</th>
                                <th>Pengguna</th>
                                <th>Token</th>
                                <th>Email Blynk</th>
                                <th>Tindakan</th>
                            </tr>
                        </thead>
                        <tbody></tbody>
                    </table>
                </div>
                <div class="card-footer">
                    <!-- Optional footer content -->
                </div>
            </div>
        </div>
    </div>
</div>



<!-- Tambah User -->
<div class="modal fade" id="modalTambahData" data-bs-backdrop="static" data-bs-keyboard="false"
    aria-labelledby="staticBackdropLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-md">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="staticBackdropLabel">Tambah Perangkat</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form method="POST" id="storeForm" action="{{ route('device.config.store') }}">
                    @csrf
                    <div class="row mb-2">
                        <label for="user_id" class="form-label" required>Nama Pengguna<span
                                class="text-danger">*</span></label>
                        <select class="form-select user_id" id="user_id" name="user_id" aria-label="user_id">
                            <option value="">Pilih Pengguna</option>
                        </select>
                    </div>

                    <div class="mb-2">
                        <label for="token" class="form-label" required>Token<span class="text-danger">*</span></label>
                        <input type="text" class="form-control" id="token" name="token"
                            placeholder="Masukkan token dari blynk" autocomplete="off">
                    </div>

                    <div class="mb-2">
                        <label for="blynk_email" class="form-label" required>Email Blynk<span
                                class="text-danger">*</span></label>
                        <input type="text" class="form-control" id="blynk_email" name="blynk_email"
                            placeholder="Masukkan email akun blynk" autocomplete="off">
                    </div>

                    <div class="mb-2">
                        <label for="blynk_password" class="form-label" required>Password Blynk<span
                                class="text-danger">*</span></label>
                        <input type="text" class="form-control" id="blynk_password" name="blynk_password"
                            placeholder="Masukkan password akun blynk" autocomplete="off">
                    </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batalkan</button>
                <button id="saveBtn" type="submit" class="btn btn-primary">Simpan</button>
            </div>
            </form>
        </div>
    </div>
</div>
</div>
@include('pages.device.configuration.edit')
@endsection

@section('scriptjs')
<script>
    $('document').ready(function () {
        let table = $('#datatable').DataTable({
            processing: true,
            serverSide: true,
            ajax: "{{ route('device.config') }}",
            dom: "<'row'<'col-sm-9 mb-4 gap-2'l><'col-sm-3'f>>" +
                "<'row'<'col-sm-12'tr>>" +
                "<'row'<'col-sm-9 mt-4'i><'col-sm-3 mt-4'p>>",
            columns: [
                { data: 'DT_RowIndex', name: 'DT_RowIndex' },
                { data: 'user_name', name: 'user_name' },
                { data: 'token', name: 'token' },
                { data: 'blynk_email', name: 'blynk_email' },
                { data: 'action', name: 'action', orderable: false, searchable: false },
            ],
            responsive: true,
            lengthChange: true,
            autoWidth: false,
            language: {
                searchPlaceholder: "Cari Data..."
            }
        });
        let userUrl = "{{ route('get-users') }}";
        $(".user_id").select2({
            dropdownParent: $("#modalTambahData"),
            ajax: {
                url: userUrl,
                dataType: 'json',
                delay: 250,
                processResults: function (data) {
                    return {
                        results: data.map(item => ({
                            id: item.id,
                            text: item.name
                        }))
                    };
                },
                cache: true
            }
        });
        $('#editForm').on('submit', function (event) {
            event.preventDefault();
            $('#editBtn').addClass('disabled');
            $.ajax({
                type: 'POST',
                url: $(this).attr('action'),
                data: JSON.stringify({
                    id: $('#id_edit').val(),
                    user_id: $('#user_id_edit').val(),
                    token: $('#token_edit').val(),
                    blynk_email: $('#blynk_email_edit').val(),
                    blynk_password: $('#blynk_password_edit').val(),
                }),
                processData: false,
                contentType: 'application/json',
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                success: function (response) {
                    Swal.fire({
                        icon: "success",
                        text: response.message,
                        showCloseButton: true,
                        confirmButtonText: 'Lanjutkan',
                    });
                    $('#editBtn').removeClass('disabled');
                    $('#modalEditData').modal('hide');
                    table.ajax.reload();
                },
                error: function (xhr, status, error) {
                    Swal.fire({
                        icon: "error",
                        text: xhr.responseJSON ? xhr.responseJSON.message : 'Terjadi kesalahan.',
                        showCloseButton: true,
                        confirmButtonText: 'Coba Lagi',
                    });
                    $('#editBtn').removeClass('disabled');
                }
            });
        });
        $('#storeForm').submit(function (e) {
            e.preventDefault();
            var formData = $(this).serialize();
            $('#saveBtn').addClass('disabled');
            $.ajax({
                url: $(this).attr('action'),
                type: 'POST',
                data: formData,
                dataType: 'json',
                success: function (response) {
                    Swal.fire({
                        icon: "success",
                        text: response.message,
                        showCloseButton: true,
                        confirmButtonText: 'Oke'
                    });
                    $('#saveBtn').removeClass('disabled');
                    $('#modalTambahData').modal('hide');
                    $('#datatable').DataTable().ajax.reload();
                },
                error: function (xhr, status, error) {
                    Swal.fire({
                        icon: "error",
                        text: xhr.responseJSON.message,
                        showCloseButton: true,
                        confirmButtonText: 'Coba Lagi',
                    });
                    $('#saveBtn').removeClass('disabled');
                    if (xhr.status === 422) {
                        let errors = xhr.responseJSON.errors;
                        if (errors.user_id) {
                            $('#user_id').addClass('is-invalid');
                        }
                        if (errors.token) {
                            $('#token').addClass('is-invalid');
                        }
                    }
                }
            });
        });
        $(document).on('click', '.delete-item', function (event) {
            let id = $(this).data('id');
            let name = $(this).data('name');
            var url = '{{ route("device.config.destroy", ":id") }}';
            url = url.replace(':id', id);

            Swal.fire({
                title: 'Peringatan!',
                text: `Apakah anda yakin ingin menghapus role ${name}?`,
                showDenyButton: true,
                confirmButtonText: "Ya, lanjutkan",
                denyButtonText: `Batalkan`,
            }).then((result) => {
                if (result.isConfirmed) {
                    $.ajax({
                        type: 'GET',
                        url: url,
                        headers: {
                            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                        },
                        contentType: 'application/json',
                        processData: false, contentType: false,
                        success: function (response) {
                            Swal.fire({
                                icon: "success",
                                text: response.message,
                                showCloseButton: true,
                                confirmButtonText: 'Oke',
                            })
                            table.ajax.reload();
                        },
                        error: function (xhr, status, error) {
                            Swal.fire({
                                icon: "error",
                                text: xhr.responseJSON.message,
                                showCloseButton: true,
                                confirmButtonText: 'Coba Lagi',
                            });
                        }
                    })
                }
            });
        });

        $(document).on('click', '.edit-item', function (event) {
            let id = $(this).data('id');
            let url = '{{ route("device.config.edit", ":id") }}';
            url = url.replace(':id', id);
            $.ajax({
                type: 'GET',
                url: url,
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                success: function (response) {
                    let userUrl = "{{ route('get-users') }}";
                    $(".user_id_edit").select2({
                        dropdownParent: $("#modalEditData"),
                        ajax: {
                            url: userUrl,
                            dataType: 'json',
                            delay: 250,
                            processResults: function (data) {
                                return {
                                    results: data.map(item => ({
                                        id: item.id,
                                        text: item.name
                                    }))
                                };
                            },
                            cache: true
                        }
                    });

                    let selectedUser = response.data.user.id;
                    let newOption = new Option(response.data.user.name, selectedUser, true, true);
                    $("#user_id_edit").append(newOption).trigger('change');

                    $('#id_edit').val(response.data.id);
                    $('#token_edit').val(response.data.token);
                    $('#blynk_email_edit').val(response.data.blynk_email);
                    $('#blynk_password_edit').val(response.data.blynk_password);
                },
                error: function (xhr, status, error) {
                    Swal.fire({
                        icon: "error",
                        text: xhr.responseJSON.message,
                        showCloseButton: true,
                        confirmButtonText: 'Coba Lagi',
                    });
                }
            });
            $('#modalEditData').modal('show');
        });
    });
</script>
@endsection