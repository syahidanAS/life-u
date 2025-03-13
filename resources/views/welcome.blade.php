<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Life-U Catalogue</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            background-color: #f2fbf3;
        }

        .card {
            transition: transform 0.3s, box-shadow 0.3s;
            border-radius: 15px;
            overflow: hidden;
            background-color: #ffffff;
        }

        .card:hover {
            transform: scale(1.05);
            box-shadow: 0px 8px 20px rgba(0, 0, 0, 0.2);
        }

        .card-img-top {
            height: 200px;
            object-fit: cover;
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
    <!-- Navbar -->
    <nav class="navbar navbar-expand-lg navbar-light bg-light shadow-sm">
        <div class="container">
            <a class="navbar-brand text-success fw-bold" href="#">Life-U</a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav"
                aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav ms-auto">
                    <li class="nav-item">
                        <a class="nav-link text-success" href="#">Beranda</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link text-success" href="#products">Produk</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link text-success" href="#" data-bs-toggle="modal"
                            data-bs-target="#caraPemesananModal">Cara Pemesanan</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link text-success" href="#" data-bs-toggle="modal"
                            data-bs-target="#termsConditionModal">Syarat & Ketentuan</a>
                    </li>
                    <li class="nav-item">
                        <a class="btn btn-outline-success ms-3" href="{{  route('login') }}">
                            <i class="bi bi-whatsapp"></i> Login Panel
                        </a>
                    </li>
                </ul>
            </div>
        </div>
    </nav>

    <!-- Konten Produk -->
    <div class="container py-5" id="products">
        <h2 class="text-center mb-2 text-success"><b>Life-U</b></h2>
        <p class="text-center text-secondary">Internet of Things Makes Your Life Easier</p>
        <div class="row row-cols-1 row-cols-md-4 g-4 mt-4">
            @foreach ($products as $product)
            <div class="col">
                <div class="card h-100">
                    <img src="{{ $product['img'] }}" class="card-img-top" alt="{{ $product['name'] }}">
                    <div class="card-body text-center">
                        <h5 class="card-title">{{ $product['name'] }}</h5>
                        <p class="fw-bold">{{ $product['price'] }}</p>
                        <p style="text-align: justify;">{{ $product['description'] }}</p>
                        <div>
                            @foreach ($product['tags'] as $tag)
                            <span class="badge bg-success me-1">{{ $tag }}</span>
                            @endforeach
                        </div>
                        <a href="https://wa.me/6282246297995?text=Halo,%20saya%20ingin%20memesan%20{{ urlencode($product['name']) }}%20seharga%20{{ urlencode($product['price']) }}"
                            target="_blank" class="btn btn-sm btn-outline-success mt-2">
                            Pesan via WhatsApp
                        </a>
                    </div>
                </div>
            </div>
            @endforeach
        </div>
    </div>

    <!-- Modal Cara Pemesanan -->
    <div class="modal fade" id="caraPemesananModal" tabindex="-1" aria-labelledby="caraPemesananLabel"
        aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="caraPemesananLabel">Cara Pemesanan</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <p>1. Pilih produk yang diinginkan.</p>
                    <p>2. Klik tombol "Pesan via WhatsApp".</p>
                    <p>3. Kirim pesan otomatis yang telah disediakan ke WhatsApp kami.</p>
                    <p>4. Tim kami akan mengkonfirmasi pesanan Anda.</p>
                    <p>5. Lakukan pembayaran dan tunggu produk dikirim.</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal Syarat & Ketentuan -->
    <div class="modal fade" id="termsConditionModal" tabindex="-1" aria-labelledby="termsConditionLabel"
        aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="termsConditionLabel">Syarat & Ketentuan</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <p>1. Semua harga sudah NETT.</p>
                    <p>2. Pengiriman dilakukan setelah pembayaran diterima.</p>
                    <p>3. Barang yang telah dibeli tidak dapat dikembalikan kecuali ada kerusakan.</p>
                    <p>4. Dengan melakukan pemesanan, Anda setuju dengan ketentuan ini.</p>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

    <footer class="text-center py-3 mt-4 text-secondary">
        &copy; 2025 Life-U. All Rights Reserved.
    </footer>
    <script>
        particlesJS("particles-js", {
            particles: {
                number: { value: 60 },
                color: { value: "#54bb60" },
                shape: { type: "circle" },
                opacity: { value: 0.5 },
                size: { value: 3 },
                move: { speed: 4 },
                line_linked: {
                    enable: true,
                    distance: 150,
                    color: "#54bb60", // Ganti dengan warna garis
                    opacity: 0.4,
                    width: 1
                }
            }
        });
    </script>
</body>

</html>