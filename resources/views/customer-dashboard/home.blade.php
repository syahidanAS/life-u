@extends('layouts.app', ['title' => 'Life-U Panel'])

@section('content')
<h1>Smart Home Dashboard</h1>
<style>
    /* Styling for toggle slider */
    .switch {
        position: relative;
        display: inline-block;
        width: 60px;
        height: 34px;
    }

    .switch input {
        opacity: 0;
        width: 0;
        height: 0;
    }

    .slider {
        position: absolute;
        cursor: pointer;
        top: 0;
        left: 0;
        right: 0;
        bottom: 0;
        background-color: #ccc;
        transition: .4s;
        border-radius: 34px;
    }

    .slider:before {
        position: absolute;
        content: "";
        height: 26px;
        width: 26px;
        border-radius: 50%;
        left: 4px;
        bottom: 4px;
        background-color: white;
        transition: .4s;
    }

    input:checked+.slider {
        background-color: #4CAF50;
    }

    input:checked+.slider:before {
        transform: translateX(26px);
    }

    /* Styling for device status */
    .status-online,
    .status-offline {
        display: inline-block;
        width: 20px;
        height: 20px;
        border-radius: 50%;
    }

    .status-online {
        background-color: green;
    }

    .status-offline {
        background-color: red;
    }

    #device-status-indicator {
        padding: 10px;
        border-radius: 50%;
        font-weight: bold;
        font-size: 14px;
        display: flex;
        justify-content: center;
        align-items: center;
        text-align: center;
    }
</style>

<div class="content-body">
    <div class="container-fluid">
        <h2 class="mb-4">Hai, Selamat Datang {{ auth()->user()->name }}</h2>
        @if(auth()->user()->getRoleNames()->first() == 'Customer')
        <table>
            <tr>
                <td class="text-center" style="padding-right: 4px;">
                    <span id="device-status-indicator" class="status-offline mb-2"></span>
                </td>
                <td class="text-center">
                    <span class=" mb-2 col-md-4" id="textStatus"></span>
                </td>
            </tr>
        </table>
        <div class="row" id="lamp-container">
        </div>
        @endif
    </div>
</div>

@endsection

@section('scriptjs')
<script>
    // Fungsi untuk memuat data lampu dan status perangkat
    function loadLamps() {
        showLoading();
        $.ajax({
            url: "{{ route('customer.get-pins') }}",
            method: "GET",
            timeout: 10000,
            success: function (response) {
                let container = $('#lamp-container');
                container.empty();
                hideLoading();
                $.each(response.data, function (index, value) {
                    let status = value == 0 ? false : true;
                    let card = `
                        <div class="col-md-4 mb-4">
                            <div class="card shadow-sm">
                                <div class="card-body text-center">
                                    <h5 class="card-title">Lamp ${index}</h5>
                                    <p class="card-text">Status: <span id="status-lamp-${index}">${status ? 'ON' : 'OFF'}</span></p>
                                    <label class="switch">
                                        <input type="checkbox" class="toggle-light" id="toggle-lamp-${index}" ${status ? 'checked' : ''} onchange="toggleLight('${index}', this)">
                                        <span class="slider"></span>
                                    </label>
                                </div>
                            </div>
                        </div>
                    `;
                    container.append(card);
                });
            },
            error: function (xhr, status, error) {
                hideLoading();
                Swal.fire({
                    icon: "error",
                    text: xhr.responseJSON.message,
                    showCloseButton: true,
                    confirmButtonText: 'Oke',
                })
            }
        });
    }

    // Fungsi untuk mengubah status lampu
    function toggleLight(pin, value) {
        let isChecked = $(value).is(':checked') ? 1 : 0; // Menentukan nilai berdasarkan state checkbox
        showLoading();
        $.ajax({
            url: "{{ route('customer.update-pins') }}",
            method: "POST",
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            data: {
                pin: pin,
                value: isChecked
            },
            timeout: 20000,
            success: function (response) {
                // Jika berhasil, perbarui status UI
                let statusText = isChecked == 1 ? 'ON' : 'OFF';
                $(`#status-lamp-${pin}`).text(statusText); // Update status text
                hideLoading();

                Swal.fire({
                    icon: "success",
                    text: response.message,
                    showCloseButton: true,
                    confirmButtonText: 'Oke',
                });
            },
            error: function (xhr, status, error) {
                // Jika gagal, rollback perubahan toggle
                $(value).prop('checked', !isChecked);
                hideLoading();

                Swal.fire({
                    icon: "error",
                    text: xhr.responseJSON.message,
                    showCloseButton: true,
                    confirmButtonText: 'Coba Lagi',
                })
            }
        })
    }

    // Fungsi untuk menampilkan status perangkat (Online/Offline)
    function updateDeviceStatus(isOnline) {

        let statusText = isOnline ? 'Online' : 'Offline ';
        let statusClass = isOnline ? 'status-online' : 'status-offline';

        // Update indicator status
        $('#device-status-indicator')
            .removeClass('status-online status-offline')
            .addClass(statusClass)
            .fadeIn();  // Fade in to show the indicator

        $('#textStatus').text(statusText);
    }

    function showLoading() {
        Swal.fire({
            title: 'Loading...',
            text: 'Mohon tunggu...',
            allowOutsideClick: false,  // Mencegah interaksi luar
            showConfirmButton: false,  // Menyembunyikan tombol konfirmasi
            didOpen: () => {
                Swal.showLoading();  // Menampilkan spinner loading
            }
        });
    }

    // Fungsi untuk menutup SweetAlert setelah selesai
    function hideLoading() {
        Swal.close();  // Menutup SweetAlert
    }

    // Ketika halaman siap, muat lampu
    $(document).ready(function () {
        $.ajax({
            url: "{{ route('customer.get-device-status') }}",
            method: "GET",
            timeout: 10000,
            success: function (response) {
                updateDeviceStatus(response.data);
            }
        })
        loadLamps();
    });
</script>
@endsection