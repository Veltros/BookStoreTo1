<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Admin Panel') - The Book Atelier</title>

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700&display=swap" rel="stylesheet">

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" rel="stylesheet">
    <link href="{{ asset('assets/css/style.css') }}" rel="stylesheet">

    <style>
        body {
            font-family: 'Plus Jakarta Sans', sans-serif;
            background-color: #f8fafc;
            color: #1e293b;
            min-height: 100vh;
            display: flex;
            flex-direction: column;
        }
        .atelier-crest {
            width: 36px;
            height: 36px;
            font-size: 1.15rem;
            background-color: #d97706 !important;
            color: #ffffff !important;
            border-radius: 4px;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            font-weight: 700;
        }
    </style>
    @stack('styles')
</head>
<body>
    @php
        $navPendingCount = \App\Models\Message::whereNull('admin_reply')->orWhere('admin_reply', '')->count();
    @endphp

    <!-- ADMIN NAVBAR -->
    <nav class="navbar navbar-expand-lg bg-dark navbar-dark sticky-top shadow-sm py-2">
        <div class="container">
            <a class="navbar-brand d-flex align-items-center gap-2 py-0 me-4" href="{{ route('admin.dashboard') }}">
                <span class="atelier-crest">BA</span>
                <div>
                    <span class="fw-bold text-white fs-5">The Book Atelier</span>
                    <span class="badge bg-warning text-dark ms-1" style="font-size: 0.7rem;">Admin</span>
                </div>
            </a>

            <button class="navbar-toggler border-0" type="button" data-bs-toggle="collapse" data-bs-target="#adminNavbarContent">
                <span class="navbar-toggler-icon"></span>
            </button>

            <div class="collapse navbar-collapse" id="adminNavbarContent">
                <ul class="navbar-nav me-auto mb-2 mb-lg-0 align-items-lg-center gap-1">
                    <li class="nav-item">
                        <a class="nav-link {{ request()->routeIs('admin.dashboard') ? 'active text-warning fw-bold' : 'text-white-50' }}" href="{{ route('admin.dashboard') }}">
                            <i class="bi bi-speedometer2 me-1"></i> Dashboard
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link {{ request()->routeIs('admin.categories.*') ? 'active text-warning fw-bold' : 'text-white-50' }}" href="{{ route('admin.categories.index') }}">
                            <i class="bi bi-tags me-1"></i> Kategori
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link {{ request()->routeIs('admin.books.*') ? 'active text-warning fw-bold' : 'text-white-50' }}" href="{{ route('admin.books.index') }}">
                            <i class="bi bi-book me-1"></i> Data Buku
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link {{ request()->routeIs('admin.orders.*') ? 'active text-warning fw-bold' : 'text-white-50' }}" href="{{ route('admin.orders.index') }}">
                            <i class="bi bi-bag-check me-1"></i> Pesanan
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link {{ request()->routeIs('admin.users.*') ? 'active text-warning fw-bold' : 'text-white-50' }}" href="{{ route('admin.users.index') }}">
                            <i class="bi bi-people me-1"></i> Data User
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link {{ request()->routeIs('admin.messages.*') ? 'active text-warning fw-bold' : 'text-white-50' }}" href="{{ route('admin.messages.index') }}">
                            <i class="bi bi-chat-dots me-1"></i> Pesan User
                            @if ($navPendingCount > 0)
                                <span class="badge bg-warning text-dark rounded-pill ms-1" style="font-size: 0.7rem;">{{ $navPendingCount }}</span>
                            @endif
                        </a>
                    </li>
                </ul>

                <ul class="navbar-nav ms-auto align-items-lg-center gap-2 mb-2 mb-lg-0">
                    <li class="nav-item">
                        <a class="btn btn-outline-warning btn-sm rounded-1 px-3 text-white" href="{{ route('home') }}" target="_blank">
                            <i class="bi bi-box-arrow-up-right me-1"></i> Buka Toko
                        </a>
                    </li>
                    <li class="nav-item dropdown">
                        <a class="nav-link text-white dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown">
                            <i class="bi bi-person-circle text-warning me-1"></i> {{ auth()->user()->name }}
                        </a>
                        <ul class="dropdown-menu dropdown-menu-end shadow border-0 mt-2">
                            <li class="dropdown-header">
                                <strong class="text-dark">{{ auth()->user()->name }}</strong>
                                <small class="d-block text-muted">{{ auth()->user()->email }}</small>
                            </li>
                            <li><hr class="dropdown-divider my-1"></li>
                            <li>
                                <form method="POST" action="{{ route('logout') }}">
                                    @csrf
                                    <button type="submit" class="dropdown-item py-2 text-danger border-0 bg-transparent">
                                        <i class="bi bi-box-arrow-right me-2"></i> Logout
                                    </button>
                                </form>
                            </li>
                        </ul>
                    </li>
                </ul>
            </div>
        </div>
    </nav>

    <!-- FLASH MESSAGES -->
    <div class="container mt-4">
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
    </div>

    <!-- MAIN ADMIN CONTENT -->
    <main class="container mb-5 flex-grow-1">
        @yield('content')
    </main>

    <!-- FOOTER ADMIN (BERSIH) -->
    <footer class="mt-auto py-3 bg-white border-top text-muted small">
        <div class="container d-flex flex-wrap justify-content-between align-items-center gap-2">
            <div>
                <i class="bi bi-shield-lock-fill text-warning me-1"></i>
                <strong>The Book Atelier &mdash; Panel Manajemen Administrator</strong>
            </div>
            <div>
                <span>&copy; {{ date('Y') }} The Book Atelier</span>
            </div>
        </div>
    </footer>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <script src="{{ asset('assets/js/main.js') }}"></script>
    @stack('scripts')
</body>
</html>
