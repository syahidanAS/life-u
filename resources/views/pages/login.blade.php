<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>SmartHome || Login</title>

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" rel="stylesheet">

    <style>
        body {
            height: 100vh;
            margin: 0;
            display: flex;
            justify-content: center;
            align-items: center;
            background: linear-gradient(135deg, #eef2ff, #f8fafc);
            font-family: 'Segoe UI', sans-serif;
        }

        .login-container {
            width: 100%;
            max-width: 420px;
        }

        .card {
            background: #ffffff;
            border: none;
            border-radius: 20px;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.08);
        }

        .card-header {
            background: transparent;
            border: none;
            text-align: center;
            padding: 30px 20px 10px;
        }

        .brand {
            font-size: 28px;
            font-weight: bold;
            color: #3b82f6;
        }

        .form-control {
            border-radius: 12px;
            padding: 12px;
            border: 1px solid #e2e8f0;
        }

        .form-control:focus {
            border-color: #3b82f6;
            box-shadow: 0 0 0 2px rgba(59, 130, 246, 0.2);
        }

        .btn-primary {
            background: linear-gradient(135deg, #3b82f6, #6366f1);
            border: none;
            border-radius: 12px;
            padding: 12px;
            font-weight: 600;
        }

        .btn-primary:hover {
            opacity: 0.9;
        }

        .text-muted {
            color: #64748b !important;
        }
    </style>
</head>

<body>

    <div class="login-container">
        <div class="card">
            <div class="card-header">
                <div class="brand">SmartHome</div>
                <p class="text-muted mt-2">Masuk untuk mengontrol rumah Anda</p>
            </div>

            <div class="card-body p-4">
                <form id="loginForm">
                    <div class="mb-3">
                        <label class="form-label">Email</label>
                        <input type="email" class="form-control" name="email" placeholder="email@example.com" required>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Password</label>
                        <input type="password" class="form-control" name="password" placeholder="••••••••" required>
                    </div>

                    <div class="d-grid">
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-sign-in-alt me-1"></i> Login
                        </button>
                    </div>

                    <div class="text-center mt-3">
                        <small class="text-muted">© {{ date('Y') }} SmartHome System</small>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <script>
        $(document).ready(function () {
            $('#loginForm').on('submit', function (e) {
                e.preventDefault();

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
                            confirmButtonText: 'Masuk'
                        }).then(() => {
                            window.location.replace(response.return_url);
                        });
                    },
                    error: function (xhr) {
                        Swal.fire({
                            icon: "error",
                            text: xhr.responseJSON.message,
                            confirmButtonText: 'Coba Lagi'
                        });
                    }
                });
            });
        });
    </script>

</body>

</html>