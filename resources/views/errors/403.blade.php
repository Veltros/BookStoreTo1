<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>403 Akses Ditolak - The Book Atelier</title>

    <!-- Google Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700&family=Playfair+Display:wght@600;700&display=swap" rel="stylesheet">

    <!-- Bootstrap 5.3 & Icons -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" rel="stylesheet">

    <style>
        body {
            font-family: 'Plus Jakarta Sans', -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, sans-serif;
            background-color: #f8fafc;
            color: #1e293b;
            min-height: 100vh;
            margin: 0;
            display: flex;
            align-items: center;
            justify-content: center;
        }
        .font-serif {
            font-family: 'Playfair Display', Georgia, serif;
        }
        .btn-maroon {
            background-color: #800020;
            color: #ffffff;
            border: none;
            transition: all 0.2s ease-in-out;
        }
        .btn-maroon:hover {
            background-color: #5d0017;
            color: #ffffff;
        }
    </style>
</head>
<body class="p-3">
    <div class="card border-0 shadow-sm rounded-4 p-4 p-md-5 bg-white text-center" style="max-width: 520px; width: 100%;">
        <div class="mb-3">
            <div class="d-inline-flex align-items-center justify-content-center rounded-circle bg-danger bg-opacity-10 text-danger p-3 mb-2" style="width: 80px; height: 80px;">
                <i class="bi bi-shield-slash-fill fs-1 text-danger"></i>
            </div>
        </div>

        <span class="badge bg-danger-subtle text-danger fw-bold px-3 py-2 rounded-pill small mb-3 align-self-center">
            HTTP 403 &bull; FORBIDDEN
        </span>

        <h1 class="h4 fw-bold font-serif text-dark mb-2">Akses Tidak Diizinkan</h1>
        <p class="text-muted small mb-4">
            {{ $exception->getMessage() ?: 'Anda tidak memiliki izin (hak akses) untuk membuka halaman atau direktori ini.' }}
        </p>

        <div class="d-flex flex-column flex-sm-row justify-content-center gap-2">
            @auth
                @if (auth()->user()->isAdmin())
                    <a href="{{ route('admin.dashboard') }}" class="btn btn-maroon px-4 py-2 fw-semibold rounded-3">
                        <i class="bi bi-speedometer2 me-1"></i> Dashboard Admin
                    </a>
                @else
                    <a href="{{ route('home') }}" class="btn btn-maroon px-4 py-2 fw-semibold rounded-3">
                        <i class="bi bi-house-door me-1"></i> Beranda
                    </a>
                    <a href="{{ route('orders.index') }}" class="btn btn-outline-secondary px-3 py-2 fw-semibold rounded-3">
                        <i class="bi bi-bag-check me-1"></i> Pesanan Saya
                    </a>
                @endif
            @else
                <a href="{{ route('login') }}" class="btn btn-maroon px-4 py-2 fw-semibold rounded-3">
                    <i class="bi bi-box-arrow-in-right me-1"></i> Masuk (Login)
                </a>
                <a href="{{ route('home') }}" class="btn btn-outline-secondary px-3 py-2 fw-semibold rounded-3">
                    <i class="bi bi-house-door me-1"></i> Beranda
                </a>
            @endauth
        </div>
    </div>
</body>
</html>
