<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta property="og:image" content="{{ asset('/assets/images/logo.svg') }}" />
    <title>Life-U || Login</title>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js" crossorigin="anonymous"></script>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@fortawesome/fontawesome-free/css/all.min.css">
    <style>
        body {
            height: 100vh;
            display: flex;
            justify-content: center;
            align-items: center;
            background-color: #5b9c60;
        }

        .card {
            border-radius: 15px;
            box-shadow: 0px 0px 20px rgba(0, 0, 0, 0.2);
        }

        .card-header {
            background: #ffffff;
            border-top-left-radius: 15px;
            border-top-right-radius: 15px;
            text-align: center;
            padding: 20px;
        }

        .btn-primary {
            background: #4CAF50;
            border: none;
        }

        .btn-primary:hover {
            background: #388E3C;
        }

        .form-control {
            border-radius: 10px;
        }

        #particles-js {
            position: absolute;
            width: 100%;
            height: 100%;
            top: 0;
            left: 0;
            z-index: 0;
        }
    </style>
    <script src="{{ asset('plugins/particlejs/particles.min.js') }}"></script>
</head>

<body>
    <div id="particles-js"></div>
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-5">
                <div class="card">
                    <div class="card-header">
                        <h3 class="fw-bold text-dark">Life-U</h3>
                        <p class="text-muted">Silakan login untuk melanjutkan</p>
                    </div>
                    <div class="card-body p-4">
                        <form id="loginForm">
                            <div class="mb-3">
                                <label for="email" class="form-label">Alamat Email</label>
                                <input type="email" class="form-control" id="email" placeholder="johndoe@gmail.com"
                                    name="email" required>
                            </div>
                            <div class="mb-3">
                                <label for="password" class="form-label">Password</label>
                                <input type="password" class="form-control" id="password" placeholder="password"
                                    name="password" required>
                            </div>
                            <div class="d-grid">
                                <button type="submit" class="btn btn-primary">Login</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
        $(document).ready(function () {
            $('#loginForm').on('submit', function (e) {
                e.preventDefault();
                $('#email, #password').removeClass('is-invalid');
                $.ajaxSetup({
                    headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') }
                });
                $.ajax({
                    type: 'POST',
                    url: '{{route("login-action")}}',
                    data: {
                        email: $('input[name="email"]').val(),
                        password: $('input[name="password"]').val(),
                    },
                    success: function (response) {
                        Swal.fire({
                            icon: "success",
                            text: response.message,
                            confirmButtonText: 'Lanjutkan',
                        }).then((result) => {
                            if (result.isConfirmed) {
                                window.location.replace(response.return_url);
                            }
                        });
                    },
                    error: function (xhr) {
                        $('#email, #password').addClass('is-invalid');
                        Swal.fire({
                            icon: "error",
                            text: xhr.responseJSON.message,
                            confirmButtonText: 'Coba Lagi',
                        });
                    }
                });
            });
        });
    </script>
    <script>
        particlesJS("particles-js", {
            particles: {
                number: { value: 40 },
                shape: { type: "circle" },
                opacity: { value: 0.5 },
                size: { value: 3 },
                move: { speed: 4 }
            }
        });
    </script>
</body>

</html>