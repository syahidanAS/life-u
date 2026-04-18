<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>SmartHome Portal</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" rel="stylesheet">
    <style>
        body {
            background: #0f172a;
            color: #e2e8f0;
        }

        .navbar {
            background: rgba(15, 23, 42, 0.9);
            backdrop-filter: blur(10px);
        }

        .hero {
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            text-align: center;
        }

        .btn-primary {
            background: linear-gradient(135deg, #3b82f6, #6366f1);
            border: none;
            padding: 12px 30px;
            font-size: 18px;
        }

        .feature-card {
            background: #1e293b;
            border-radius: 16px;
            padding: 20px;
            transition: 0.3s;
        }

        .feature-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.4);
        }
    </style>
</head>

<body>

    <nav class="navbar navbar-expand-lg navbar-dark px-4">
        <a class="navbar-brand fw-bold" href="#">SmartHome</a>
        <div class="ms-auto">
            @auth
                <a href="/dashboard" class="btn btn-outline-light btn-sm">Dashboard</a>
            @else
                <a href="/login" class="btn btn-outline-light btn-sm">Login</a>
            @endauth
        </div>
    </nav>

    <!-- HERO / WELCOME -->
    <section class="hero">
        <div class="container">
            <h1 class="fw-bold display-4">Selamat Datang di SmartHome</h1>
            <p class="text-secondary mt-3 mb-4">
                Kendalikan seluruh perangkat rumah Anda dengan mudah, aman, dan real-time dalam satu platform modern.
            </p>

            @auth
                <a href="/dashboard" class="btn btn-primary">
                    Masuk ke Dashboard
                </a>
            @else
                <a href="/login" class="btn btn-primary">
                    Mulai Sekarang
                </a>
            @endauth
        </div>
    </section>

    <!-- FEATURES -->
    <section class="pb-5">
        <div class="container">
            <div class="row g-4">
                <div class="col-md-4">
                    <div class="feature-card text-center">
                        <i class="fas fa-lightbulb fa-2x mb-3"></i>
                        <h5>Kontrol Lampu</h5>
                        <p class="text-secondary">Nyalakan dan matikan lampu dari mana saja.</p>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="feature-card text-center">
                        <i class="fas fa-video fa-2x mb-3"></i>
                        <h5>Monitoring CCTV</h5>
                        <p class="text-secondary">Pantau rumah Anda secara real-time.</p>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="feature-card text-center">
                        <i class="fas fa-bolt fa-2x mb-3"></i>
                        <h5>Statistik Energi</h5>
                        <p class="text-secondary">Lihat penggunaan listrik secara detail.</p>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>