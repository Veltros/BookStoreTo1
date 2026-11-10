@extends('layouts.app')

@section('title', 'Masuk ke Akun - The Book Atelier')

@section('content')
<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-md-6 col-lg-5">
            <div class="card border-0 shadow-sm rounded-4 p-4 p-md-5 bg-white">
                <div class="text-center mb-4">
                    <span class="atelier-crest mb-2" style="width: 48px; height: 48px; font-size: 1.4rem;">BA</span>
                    <h1 class="h4 fw-bold font-serif text-dark mt-2 mb-1">Masuk ke Akun Anda</h1>
                    <p class="text-muted small">Kelola pesanan dan nikmati pengalaman kurasi buku terbaik</p>
                </div>

                @if ($errors->any())
                    <div class="alert alert-danger py-2 px-3 small rounded-3 mb-3">
                        <ul class="mb-0 ps-3">
                            @foreach ($errors->all() as $err)
                                <li>{{ $err }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <form method="POST" action="{{ route('login.post') }}">
                    @csrf
                    <div class="mb-3">
                        <label for="email" class="form-label small fw-bold text-dark">Alamat Email</label>
                        <input type="email" class="form-control" id="email" name="email" value="{{ old('email') }}" placeholder="nama@email.com" required autofocus>
                    </div>

                    <div class="mb-3">
                        <label for="password" class="form-label small fw-bold text-dark">Kata Sandi</label>
                        <input type="password" class="form-control" id="password" name="password" placeholder="Minimal 8 karakter" required>
                    </div>

                    <div class="d-flex justify-content-between align-items-center mb-4">
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" name="remember" id="remember" {{ old('remember') ? 'checked' : '' }}>
                            <label class="form-check-label small text-muted" for="remember">
                                Ingat saya di perangkat ini
                            </label>
                        </div>
                    </div>

                    <button type="submit" class="btn btn-atelier-primary w-100 py-2 fw-semibold mb-3">
                        Masuk ke Akun
                    </button>
                </form>

                <div class="text-center small text-muted mb-4">
                    Belum punya akun? <a href="{{ route('register') }}" class="text-maroon fw-semibold">Daftar sekarang</a>
                </div>

                <!-- Box Demo Akun -->
                <div class="card bg-light border-0 rounded-3 p-3">
                    <div class="d-flex align-items-center mb-2">
                        <i class="bi bi-info-circle-fill text-primary me-2"></i>
                        <span class="fw-bold small">Akun Uji Coba (Demo):</span>
                    </div>
                    <div class="small text-muted mb-1">
                        <strong>Admin:</strong> <code>admin@bookstore.com</code> | Pass: <code>admin123</code>
                    </div>
                    <div class="small text-muted">
                        <strong>User:</strong> <code>budi@gmail.com</code> | Pass: <code>user1234</code>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
