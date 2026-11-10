<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'The Book Atelier - Dunia Cerita, Dikurasi Khusus Untuk Anda')</title>

    <!-- Google Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:ital,wght@0,400;0,500;0,600;0,700;0,800;1,400;1,600&family=Plus+Jakarta+Sans:wght@300;400;500;600;700&display=swap" rel="stylesheet">

    <!-- Bootstrap 5.3 & Icons -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" rel="stylesheet">

    <!-- Custom CSS -->
    <link href="{{ asset('assets/css/style.css') }}" rel="stylesheet">

    <style>
        :root {
            --color-maroon: #800020;
            --color-maroon-hover: #5d0017;
            --color-black: #111111;
            --color-offwhite: #fcfbf9;
            --color-cream: #f8f6f0;
            --color-border: #e8e8e8;
            --color-text-main: #1f2937;
            --color-text-muted: #596780;
            --font-serif: 'Playfair Display', Georgia, Cambria, 'Times New Roman', serif;
            --font-sans: 'Plus Jakarta Sans', -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, sans-serif;
        }

        body {
            font-family: var(--font-sans);
            background-color: #ffffff;
            color: var(--color-text-main);
            margin: 0;
            padding: 0;
            -webkit-font-smoothing: antialiased;
            min-height: 100vh;
            display: flex;
            flex-direction: column;
        }

        /* Responsive Global Image Constraint */
        img {
            max-width: 100%;
            height: auto;
        }

        a {
            color: inherit;
            text-decoration: none !important;
            transition: color 0.18s ease-in-out;
        }
        a:hover {
            color: var(--color-maroon) !important;
        }

        .text-maroon { color: #800020 !important; }
        .bg-maroon { background-color: #800020 !important; color: #ffffff !important; }
        .border-maroon { border-color: #800020 !important; }

        .atelier-brand {
            display: inline-flex;
            align-items: center;
            gap: 0.85rem;
        }
        .atelier-crest {
            width: 44px;
            height: 44px;
            background-color: #800020 !important;
            color: #ffffff !important;
            border-radius: 4px;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            font-family: var(--font-serif);
            font-size: 1.35rem;
            font-weight: 700;
            flex-shrink: 0;
            box-shadow: 0 2px 8px rgba(128, 0, 32, 0.28);
        }
        .atelier-brand-name {
            font-family: var(--font-serif);
            font-size: 1.55rem;
            font-weight: 700;
            color: #111111;
            letter-spacing: 0.015em;
            margin: 0;
            line-height: 1.1;
            display: block;
        }
        .atelier-brand-tagline {
            font-size: 0.72rem;
            font-weight: 600;
            color: #800020;
            letter-spacing: 0.06em;
            text-transform: uppercase;
            display: block;
            margin-top: 2px;
        }

        .atelier-main-header {
            background-color: #ffffff;
            border-bottom: 1px solid var(--color-border);
            padding: 1rem 0;
            z-index: 1020;
        }

        .header-action-link {
            display: inline-flex;
            align-items: center;
            gap: 0.45rem;
            padding: 0.5rem 0.85rem;
            border-radius: 6px;
            font-size: 0.88rem;
            color: #374151;
            transition: all 0.2s;
        }
        .header-action-link:hover {
            background-color: #f3f4f6;
            color: var(--color-maroon) !important;
        }
        .header-cart-badge {
            position: absolute;
            top: 2px;
            left: 20px;
            background-color: #dc2626;
            color: #ffffff;
            font-size: 0.65rem;
            font-weight: 700;
            border-radius: 50rem;
            padding: 0.15em 0.5em;
            line-height: 1;
        }

        .atelier-subnav {
            background-color: #ffffff;
            border-bottom: 1px solid #ededed;
            padding: 0.15rem 0;
            box-shadow: 0 1px 3px rgba(0,0,0,0.02);
            position: relative;
            z-index: 1010;
        }
        .atelier-subnav-container {
            overflow: visible !important;
        }
        @media (max-width: 991.98px) {
            .atelier-subnav-container {
                overflow-x: auto !important;
                -webkit-overflow-scrolling: touch;
            }
        }
        @media (min-width: 992px) {
            .atelier-subnav .nav-item.dropdown:hover .dropdown-menu {
                display: block;
                margin-top: 0;
                animation: subnavFadeIn 0.18s ease-in-out;
            }
        }
        @keyframes subnavFadeIn {
            from { opacity: 0; transform: translateY(6px); }
            to { opacity: 1; transform: translateY(0); }
        }

        .atelier-nav-link {
            display: inline-flex;
            align-items: center;
            padding: 0.75rem 1rem !important;
            font-size: 0.875rem;
            font-weight: 600;
            color: #27272a !important;
            letter-spacing: 0.015em;
            text-decoration: none !important;
            border-bottom: 2px solid transparent;
            margin-bottom: -1px;
        }
        .atelier-nav-link:hover, .atelier-nav-link.active {
            color: #800020 !important;
            border-bottom-color: #800020;
        }

        .btn-atelier-primary {
            background-color: #800020 !important;
            border: 1.5px solid #800020 !important;
            color: #ffffff !important;
            font-weight: 600;
            font-size: 0.95rem;
            padding: 0.75rem 1.65rem;
            border-radius: 4px;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: 0.5rem;
            box-shadow: 0 4px 14px rgba(128, 0, 32, 0.22);
            transition: all 0.2s;
        }
        .btn-atelier-primary:hover {
            background-color: #5d0017 !important;
            border-color: #5d0017 !important;
            color: #ffffff !important;
            transform: translateY(-1px);
        }

        .btn-atelier-outline {
            background-color: transparent !important;
            border: 1.5px solid #111111 !important;
            color: #111111 !important;
            font-weight: 600;
            font-size: 0.95rem;
            padding: 0.75rem 1.65rem;
            border-radius: 4px;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: 0.5rem;
            transition: all 0.2s;
        }
        .btn-atelier-outline:hover {
            background-color: #111111 !important;
            color: #ffffff !important;
        }

        /* Hero Section & Showcase */
        .atelier-hero {
            background: linear-gradient(180deg, #fbfaf8 0%, #ffffff 100%);
            border-bottom: 1px solid var(--color-border);
            padding: 3.5rem 0 4.5rem;
        }
        .atelier-hero-eyebrow {
            display: inline-flex;
            align-items: center;
            gap: 0.4rem;
            font-size: 0.75rem;
            font-weight: 700;
            letter-spacing: 0.12em;
            color: #800020;
            text-transform: uppercase;
            margin-bottom: 1rem;
        }
        .atelier-hero-title {
            font-family: var(--font-serif);
            font-size: 2.75rem;
            font-weight: 700;
            line-height: 1.2;
            color: #111111;
            margin-bottom: 1.25rem;
            letter-spacing: -0.02em;
        }
        .atelier-hero-lead {
            font-size: 1.05rem;
            line-height: 1.7;
            color: #4b5563;
            margin-bottom: 2rem;
            max-width: 600px;
        }
        .hero-book-card-wrap {
            max-width: 320px;
            margin: 0 auto;
            position: relative;
        }
        .hero-book-img {
            width: 100%;
            height: auto;
            aspect-ratio: 2/3;
            object-fit: cover;
            border-radius: 6px;
            box-shadow: -15px 20px 45px rgba(0,0,0,0.22), 0 5px 15px rgba(0,0,0,0.08);
            border: 1px solid rgba(0,0,0,0.1);
            display: block;
        }

        /* Kartu Kategori Minimalis */
        .atelier-cat-card {
            background: #ffffff;
            border: 1px solid var(--color-border);
            border-radius: 6px;
            padding: 1.75rem;
            text-decoration: none !important;
            color: inherit !important;
            display: block;
            height: 100%;
            transition: all 0.22s ease;
        }
        .atelier-cat-card:hover {
            border-color: #800020;
            box-shadow: 0 10px 25px rgba(0,0,0,0.06);
            transform: translateY(-3px);
            color: inherit !important;
        }
        .atelier-cat-icon {
            width: 48px;
            height: 48px;
            background-color: #f8f6f0;
            color: #800020;
            border-radius: 6px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.35rem;
            margin-bottom: 1.15rem;
            transition: all 0.2s ease;
        }
        .atelier-cat-card:hover .atelier-cat-icon {
            background-color: #800020;
            color: #ffffff;
        }
        .atelier-cat-name {
            font-family: var(--font-serif);
            font-size: 1.25rem;
            font-weight: 700;
            color: #111111;
            margin-bottom: 0.45rem;
        }
        .atelier-cat-desc {
            font-size: 0.85rem;
            color: #6b7280;
            line-height: 1.5;
            margin-bottom: 1rem;
        }
        .atelier-cat-link {
            font-size: 0.825rem;
            font-weight: 700;
            color: #800020;
            text-transform: uppercase;
            letter-spacing: 0.05em;
            display: inline-flex;
            align-items: center;
            gap: 0.35rem;
        }

        /* Kartu Buku & Cover Sempurna (Kinokuniya Australia Reference) */
        .atelier-book-card {
            background: #ffffff;
            border: 1px solid #ebebeb;
            border-radius: 6px;
            padding: 1rem;
            display: flex;
            flex-direction: column;
            height: 100%;
            transition: all 0.22s ease-in-out;
            position: relative;
        }
        .atelier-book-card:hover {
            border-color: #800020;
            box-shadow: 0 12px 30px rgba(0,0,0,0.08);
            transform: translateY(-4px);
        }
        .atelier-book-cover-wrap,
        .atelier-cover-wrapper {
            position: relative;
            aspect-ratio: 2/3;
            width: 100%;
            overflow: hidden;
            border-radius: 4px;
            background-color: #f3f4f6;
            margin-bottom: 1rem;
            box-shadow: 0 4px 12px rgba(0,0,0,0.06);
            display: block;
        }
        .atelier-book-cover-wrap > a,
        .atelier-cover-wrapper > a {
            display: block;
            width: 100%;
            height: 100%;
            overflow: hidden;
        }
        .atelier-book-cover,
        .atelier-cover-img {
            width: 100%;
            height: 100%;
            object-fit: cover;
            object-position: center;
            transition: transform 0.35s ease;
            display: block;
        }
        .atelier-book-card:hover .atelier-book-cover,
        .atelier-book-card:hover .atelier-cover-img {
            transform: scale(1.04);
        }
        .atelier-book-genre {
            font-size: 0.7rem;
            font-weight: 700;
            letter-spacing: 0.08em;
            text-transform: uppercase;
            color: #800020;
            margin-bottom: 0.3rem;
            display: block;
        }
        .atelier-book-title {
            font-family: var(--font-serif);
            font-size: 1.05rem;
            font-weight: 700;
            color: #111111;
            line-height: 1.35;
            margin-bottom: 0.35rem;
            display: -webkit-box;
            -webkit-line-clamp: 2;
            -webkit-box-orient: vertical;
            overflow: hidden;
            height: 2.7rem;
        }
        .atelier-book-title a {
            color: #111111 !important;
            text-decoration: none !important;
        }
        .atelier-book-title a:hover {
            color: #800020 !important;
        }
        .atelier-book-author {
            font-size: 0.825rem;
            color: #6b7280;
            margin-bottom: 0.75rem;
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
        }
        .atelier-book-price {
            font-family: var(--font-sans);
            font-size: 1.1rem;
            font-weight: 700;
            color: #111111;
            margin-bottom: 0;
        }

        /* Section Header & Subtitle */
        .atelier-section-header {
            text-align: center;
            max-width: 650px;
            margin: 0 auto 2.5rem;
        }
        .atelier-section-eyebrow {
            font-size: 0.75rem;
            font-weight: 700;
            letter-spacing: 0.12em;
            color: #800020;
            text-transform: uppercase;
            display: block;
            margin-bottom: 0.4rem;
        }
        .atelier-section-title {
            font-family: var(--font-serif);
            font-size: 2.15rem;
            font-weight: 700;
            color: #111111;
            margin-bottom: 0.65rem;
        }
        .atelier-section-subtitle {
            font-size: 0.95rem;
            color: #64748b;
            line-height: 1.6;
            margin: 0;
        }

        /* Newsletter & Footer Atelier */
        .atelier-newsletter-wrap {
            background-color: #0f141c;
            color: #ffffff;
            border-radius: 8px;
            padding: 3.5rem 2rem;
            text-align: center;
        }
        .atelier-newsletter-title {
            font-family: var(--font-serif);
            font-size: 2rem;
            color: #ffffff;
            margin-bottom: 0.75rem;
        }
        .atelier-newsletter-desc {
            font-size: 0.95rem;
            color: rgba(255,255,255,0.78);
            max-width: 580px;
            margin: 0 auto 1.75rem;
            line-height: 1.6;
        }

        .atelier-footer {
            background-color: #0f141c;
            color: #e5e7eb;
            padding: 4rem 0 2rem;
            margin-top: auto;
        }
    </style>
    @stack('styles')
</head>
<body>
    @php
        $navCategories = \App\Models\Category::orderBy('id')->get();
        $cartCount = array_sum(array_column(session('cart', []), 'qty'));
    @endphp

    <!-- HEADER UTAMA -->
    <header class="atelier-main-header sticky-top bg-white">
        <div class="container">
            <div class="d-flex align-items-center justify-content-between gap-3">
                <!-- Brand Identitas Toko Buku -->
                <a class="atelier-brand d-flex align-items-center gap-3 text-dark text-decoration-none" href="{{ route('home') }}">
                    <span class="atelier-crest">BA</span>
                    <div class="d-flex flex-column">
                        <span class="atelier-brand-name">The Book Atelier</span>
                        <span class="atelier-brand-tagline">Dunia Cerita, Dikurasi Khusus Untuk Anda</span>
                    </div>
                </a>

                <!-- Kolom Pencarian Tengah (Desktop) -->
                <div class="d-none d-lg-block flex-grow-1 mx-4">
                    <form action="{{ route('books.index') }}" method="GET" class="atelier-search-group mx-auto">
                        <div class="input-group">
                            <input type="text" name="q" class="form-control" placeholder="Cari judul buku, penulis, atau ISBN..." value="{{ request('q') }}">
                            <button type="submit" class="btn btn-search">
                                <i class="bi bi-search me-1"></i> Cari
                            </button>
                        </div>
                    </form>
                </div>

                <!-- Aksi Pengguna: Akun & Keranjang Belanja -->
                <div class="d-flex align-items-center gap-2">
                    @auth
                        @if (auth()->user()->isAdmin())
                            <a href="{{ route('admin.dashboard') }}" class="btn btn-outline-dark btn-sm rounded-1 px-3 d-none d-sm-inline-flex align-items-center gap-1">
                                <i class="bi bi-shield-lock-fill text-warning"></i> Admin
                            </a>
                        @endif

                        <!-- Menu Akun Pengguna -->
                        <div class="dropdown">
                            <a href="#" class="header-action-link dropdown-toggle" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                                <i class="bi bi-person-circle fs-5 text-maroon"></i>
                                <span class="d-none d-md-inline fw-semibold">{{ explode(' ', auth()->user()->name)[0] }}</span>
                            </a>
                            <ul class="dropdown-menu dropdown-menu-end shadow border mt-2">
                                <li class="dropdown-header">
                                    <strong class="text-dark">{{ auth()->user()->name }}</strong>
                                    <small class="d-block text-muted">{{ auth()->user()->email }}</small>
                                </li>
                                <li><hr class="dropdown-divider my-1"></li>
                                <li>
                                    <a class="dropdown-item py-2" href="{{ route('orders.index') }}">
                                        <i class="bi bi-bag-check me-2 text-maroon"></i> Pesanan Saya
                                    </a>
                                </li>
                                <li>
                                    <a class="dropdown-item py-2" href="{{ route('contact.index') }}">
                                        <i class="bi bi-chat-dots me-2 text-maroon"></i> Chat Concierge
                                    </a>
                                </li>
                                <li><hr class="dropdown-divider my-1"></li>
                                <li>
                                    <form method="POST" action="{{ route('logout') }}">
                                        @csrf
                                        <button type="submit" class="dropdown-item py-2 text-danger border-0 bg-transparent">
                                            <i class="bi bi-box-arrow-right me-2"></i> Keluar (Logout)
                                        </button>
                                    </form>
                                </li>
                            </ul>
                        </div>
                    @else
                        <a href="{{ route('login') }}" class="header-action-link" title="Masuk ke Akun">
                            <i class="bi bi-person fs-5"></i>
                            <span class="d-none d-md-inline fw-semibold">Masuk / Daftar</span>
                        </a>
                    @endauth

                    <!-- Tombol Cepat Pesanan Saya -->
                    <a href="{{ route('orders.index') }}" class="header-action-link {{ request()->routeIs('orders.*') ? 'text-maroon' : '' }}" title="Riwayat Pesanan Saya">
                        <i class="bi bi-receipt fs-5"></i>
                        <span class="d-none d-md-inline fw-semibold">Pesanan Saya</span>
                    </a>

                    <!-- Tombol Keranjang Belanja -->
                    <a href="{{ route('cart.index') }}" class="header-action-link position-relative" title="Keranjang Belanja">
                        <i class="bi bi-bag fs-5"></i>
                        <span class="d-none d-md-inline fw-semibold">Keranjang</span>
                        @if ($cartCount > 0)
                            <span class="header-cart-badge">{{ $cartCount }}</span>
                        @endif
                    </a>
                </div>
            </div>

            <!-- Kolom Pencarian (Mobile) -->
            <div class="d-lg-none mt-3">
                <form action="{{ route('books.index') }}" method="GET">
                    <div class="input-group border rounded-2 overflow-hidden">
                        <input type="text" name="q" class="form-control form-control-sm border-0" placeholder="Cari buku, penulis, ISBN..." value="{{ request('q') }}">
                        <button type="submit" class="btn btn-dark btn-sm px-3">
                            <i class="bi bi-search"></i>
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </header>

    <!-- SUBNAVIGASI MENU -->
    <nav class="atelier-subnav bg-white border-bottom">
        <div class="container">
            <div class="d-flex align-items-center justify-content-between atelier-subnav-container py-1">
                <ul class="nav flex-nowrap align-items-center gap-1">
                    <li class="nav-item">
                        <a class="atelier-nav-link {{ request()->routeIs('home') ? 'active' : '' }}" href="{{ route('home') }}">
                            Beranda
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="atelier-nav-link {{ (request()->routeIs('books.*') && !request('category') && !request('sort')) ? 'active' : '' }}" href="{{ route('books.index') }}">
                            Katalog Buku
                        </a>
                    </li>
                    <li class="nav-item dropdown">
                        <a class="atelier-nav-link dropdown-toggle {{ request('category') ? 'active' : '' }}" href="{{ route('books.index') }}" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                            Kategori Buku
                        </a>
                        <ul class="dropdown-menu shadow border mt-1" style="min-width: 220px;">
                            <li>
                                <a class="dropdown-item py-2 fw-semibold" href="{{ route('books.index') }}">
                                    <i class="bi bi-grid-fill me-2 text-maroon"></i>Semua Kategori
                                </a>
                            </li>
                            <li><hr class="dropdown-divider my-1"></li>
                            @foreach ($navCategories as $nc)
                                <li>
                                    <a class="dropdown-item py-2 {{ request('category') == $nc->id ? 'active bg-light text-maroon fw-bold' : '' }}" href="{{ route('books.index', ['category' => $nc->id]) }}">
                                        <i class="bi bi-tag me-2 text-muted"></i>{{ $nc->name }}
                                    </a>
                                </li>
                            @endforeach
                        </ul>
                    </li>
                    <li class="nav-item">
                        <a class="atelier-nav-link {{ request('sort') === 'newest' ? 'active' : '' }}" href="{{ route('books.index', ['sort' => 'newest']) }}">
                            Buku Terbaru
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="atelier-nav-link {{ request('sort') === 'popular' ? 'active' : '' }}" href="{{ route('books.index', ['sort' => 'popular']) }}">
                            Buku Terlaris
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="atelier-nav-link" href="{{ route('home') }}#pilihan-kurator">
                            Pilihan Kurator
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="atelier-nav-link {{ request()->routeIs('orders.*') ? 'active' : '' }}" href="{{ route('orders.index') }}">
                            <i class="bi bi-bag-check me-1"></i> Pesanan Saya
                        </a>
                    </li>
                </ul>

                <div class="d-none d-lg-flex align-items-center gap-3">
                    <a href="{{ route('about') }}" class="atelier-nav-link {{ request()->routeIs('about') ? 'active' : '' }}">
                        Tentang Kami
                    </a>
                    <a href="{{ route('contact.index') }}" class="atelier-nav-link {{ request()->routeIs('contact.*') ? 'active' : '' }}">
                        <i class="bi bi-chat-dots me-1 text-maroon"></i> Chat Admin
                    </a>
                </div>
            </div>
        </div>
    </nav>

    <!-- FLASH MESSAGES -->
    <div class="container mt-3">
        @if (session('success'))
            <div class="alert alert-success alert-dismissible fade show shadow-sm rounded-3 py-3 px-4 mb-4" role="alert">
                <div class="d-flex align-items-center gap-3">
                    <i class="bi bi-check-circle-fill text-success fs-4 flex-shrink-0"></i>
                    <div class="fw-semibold">{{ session('success') }}</div>
                </div>
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif

        @if (session('danger'))
            <div class="alert alert-danger border-danger border-2 alert-dismissible fade show shadow-sm rounded-3 py-3 px-4 mb-4" role="alert">
                <div class="d-flex align-items-center gap-3">
                    <i class="bi bi-exclamation-octagon-fill text-danger fs-4 flex-shrink-0"></i>
                    <div class="fw-semibold">{{ session('danger') }}</div>
                </div>
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif

        @if (session('warning'))
            <div class="alert alert-warning border-warning border-2 alert-dismissible fade show shadow-sm rounded-3 py-3 px-4 mb-4" role="alert">
                <div class="d-flex align-items-center gap-3">
                    <i class="bi bi-exclamation-triangle-fill text-warning fs-4 flex-shrink-0"></i>
                    <div class="fw-semibold">{{ session('warning') }}</div>
                </div>
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif

        @if (session('info'))
            <div class="alert alert-info border-primary alert-dismissible fade show shadow-sm rounded-3 py-3 px-4 mb-4" role="alert">
                <div class="d-flex align-items-center gap-3">
                    <i class="bi bi-info-circle-fill text-primary fs-4 flex-shrink-0"></i>
                    <div class="fw-semibold">{{ session('info') }}</div>
                </div>
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif
    </div>

    <!-- MAIN CONTENT -->
    <main class="flex-grow-1">
        @yield('content')
    </main>

    <!-- FOOTER -->
    <footer class="atelier-footer">
        <div class="container">
            <div class="row g-4 mb-5">
                <div class="col-lg-4 col-md-6">
                    <div class="d-flex align-items-center gap-2 mb-2">
                        <span class="atelier-crest" style="width: 36px; height: 36px; font-size: 1.15rem;">BA</span>
                        <span class="fs-5 fw-bold text-white font-serif">The Book Atelier</span>
                    </div>
                    <span class="d-block text-maroon small fw-bold text-uppercase mb-2" style="letter-spacing: 0.08em;">
                        Dunia Cerita, Dikurasi Khusus Untuk Anda.
                    </span>
                    <p class="small text-secondary pe-lg-4 lh-base">
                        Toko buku independen premium yang menghadirkan mahakarya sastra dunia, non-fiksi inspiratif, pemikiran kritis, dan inovasi ilmiah untuk pelajar, mahasiswa, dan pecinta buku.
                    </p>
                </div>

                <div class="col-lg-2 col-md-3 col-6">
                    <h5 class="text-white font-serif mb-3 fs-6">Koleksi Buku</h5>
                    <ul class="list-unstyled d-flex flex-column gap-2 small">
                        <li><a href="{{ route('books.index') }}" class="text-secondary">Semua Buku</a></li>
                        <li><a href="{{ route('books.index', ['category' => 1]) }}" class="text-secondary">Fiksi</a></li>
                        <li><a href="{{ route('books.index', ['category' => 2]) }}" class="text-secondary">Non-Fiksi</a></li>
                        <li><a href="{{ route('books.index', ['category' => 3]) }}" class="text-secondary">Fantasi</a></li>
                        <li><a href="{{ route('books.index', ['category' => 5]) }}" class="text-secondary">Sains</a></li>
                        <li><a href="{{ route('books.index', ['category' => 6]) }}" class="text-secondary">Teknologi</a></li>
                    </ul>
                </div>

                <div class="col-lg-2 col-md-3 col-6">
                    <h5 class="text-white font-serif mb-3 fs-6">Layanan Pembaca</h5>
                    <ul class="list-unstyled d-flex flex-column gap-2 small">
                        <li><a href="{{ route('about') }}" class="text-secondary">Tentang Atelier</a></li>
                        <li><a href="{{ route('contact.index') }}" class="text-secondary">Chat Concierge</a></li>
                        <li><a href="{{ route('cart.index') }}" class="text-secondary">Keranjang Belanja</a></li>
                        <li><a href="{{ route('orders.index') }}" class="text-secondary">Lacak Pesanan</a></li>
                        <li><a href="{{ route('login') }}" class="text-secondary">Masuk / Akun</a></li>
                    </ul>
                </div>

                <div class="col-lg-4 col-md-12">
                    <h5 class="text-white font-serif mb-3 fs-6">Lokasi &amp; Jam Operasional</h5>
                    <div class="small text-secondary d-flex flex-column gap-2 mb-3">
                        <div>
                            <i class="bi bi-geo-alt-fill text-maroon me-2"></i>
                            <strong class="text-white">The Book Atelier Store:</strong><br>
                            Jl. MT. Haryono Kavling 52, Jakarta Selatan, DKI Jakarta 12770
                        </div>
                        <div>
                            <i class="bi bi-clock-fill text-maroon me-2"></i>
                            <strong class="text-white">Jam Buka:</strong> Senin &ndash; Sabtu: 08.00 &ndash; 17.00 WIB
                        </div>
                    </div>
                </div>
            </div>

            <div class="pt-4 border-top border-secondary border-opacity-25 d-flex flex-wrap justify-content-between align-items-center gap-2 small text-secondary">
                <div>
                    &copy; {{ date('Y') }} <strong>The Book Atelier</strong>. Seluruh Hak Cipta Dilindungi.
                </div>
                <div class="d-flex gap-3">
                    <a href="#" class="text-secondary">Kebijakan Privasi</a>
                    <span>&bull;</span>
                    <a href="#" class="text-secondary">Syarat &amp; Ketentuan</a>
                    <span>&bull;</span>
                    <a href="{{ route('contact.index') }}" class="text-secondary">Bantuan Konsumen</a>
                </div>
            </div>
        </div>
    </footer>

    <!-- Bootstrap 5.3 JS Bundle -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <script src="{{ asset('assets/js/main.js') }}"></script>
    @stack('scripts')
</body>
</html>
