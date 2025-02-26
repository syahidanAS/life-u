@extends('layouts.app', ['title' => 'Profile'])

@section('content')
<h1>Smart Home Dashboard</h1>
<div class="content-body">
    <div class="container-fluid">
        <div class="card col-md-6">
            <div class="card-body">
                <form id="editForm" action="{{ route('setting.users.update') }}" method="PUT">
                    @csrf
                    <input type="text" value="{{ $data->id }}" hidden id="id" name="id">
                    <div class="form-group">
                        <label for="name">Nama</label>
                        <input type="text" class="form-control" id="name" name="name" value="{{ $data->name }}">
                    </div>
                    <div class="form-group mt-4 mb-4">
                        <label for="email">Email</label>
                        <input type="email" class="form-control" id="email" name="email" value="{{ $data->email }}">
                    </div>
                    <div class="form-group mt-4 mb-4">
                        <label for="password">Password</label>
                        <input type="password" class="form-control" id="password" name="password" value="">
                    </div>
                    <div class="form-group mt-4 mb-4">
                        <label for="retype">Ketik Ulang Password</label>
                        <input type="password" class="form-control" id="retype" name="retype" value="">
                    </div>
                    <button type="submit" id="btnEditUser" class="btn btn-primary btn-sm">Simpan Perubahan</button>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection

@section('scriptjs')
<script>
    $(document).on('click', '#btnEditUser', function () {
        $('#editForm').submit(function (e) {
            e.preventDefault();
            $.ajax({
                url: $(this).attr('action'),
                method: $(this).attr('method'),
                data: $(this).serialize(),
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                success: function (response) {
                    Swal.fire({
                        icon: "success",
                        text: response.message,
                        showCloseButton: true,
                        confirmButtonText: 'Oke',
                    }).then((result) => {
                        if (result.isConfirmed) {
                            location.reload();
                        }
                    })
                },
                error: function (xhr, status, error) {
                    Swal.fire({
                        icon: "error",
                        text: xhr.responseJSON.message,
                        showCloseButton: true,
                        confirmButtonText: 'Oke',
                    })
                }
            })
        })
    });
</script>
@endsection