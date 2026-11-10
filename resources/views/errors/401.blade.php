<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>401 Autentikasi Diperlukan - The Book Atelier</title>

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
            <div class="d-inline-flex align-items-center justify-content-center rounded-circle bg-warning bg-opacity-10 text-warning p-3 mb-2" style="width: 80px; height: 80px;">
                <i class="bi bi-person-lock fs-1 text-warning"></i>
            </div>
        </div>

        <span class="badge bg-warning-subtle text-dark border border-warning-subtle fw-bold px-3 py-2 rounded-pill small mb-3 align-self-center">
            HTTP 401 &bull; UNAUTHORIZED
        </span>

        <h1 class="h4 fw-bold font-serif text-dark mb-2">Autentikasi Diperlukan</h1>
        <p class="text-muted small mb-4">
            {{ $exception->getMessage() ?: 'Anda harus masuk (login) dengan akun yang valid terlebih dahulu untuk mengakses halaman ini.' }}
        </p>

        <div class="d-flex flex-column flex-sm-row justify-content-center gap-2">
            <a href="{{ route('login') }}" class="btn btn-maroon px-4 py-2 fw-semibold rounded-3">
                <i class="bi bi-box-arrow-in-right me-1"></i> Masuk ke Akun
            </a>
            <a href="{{ route('home') }}" class="btn btn-outline-secondary px-3 py-2 fw-semibold rounded-3">
                <i class="bi bi-house-door me-1"></i> Beranda
            </a>
        </div>
    </div>
</body>
</html>
