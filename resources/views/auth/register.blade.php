@extends('layouts.app')

@section('title', 'Daftar Akun Baru - The Book Atelier')

@section('content')
<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-md-7 col-lg-6">
            <div class="card border-0 shadow-sm rounded-4 p-4 p-md-5 bg-white">
                <div class="text-center mb-4">
                    <span class="atelier-crest mb-2" style="width: 48px; height: 48px; font-size: 1.4rem;">BA</span>
                    <h1 class="h4 fw-bold font-serif text-dark mt-2 mb-1">Registrasi Akun Baru</h1>
                    <p class="text-muted small">Bergabunglah bersama komunitas pembaca The Book Atelier</p>
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

                <form method="POST" action="{{ route('register.post') }}">
                    @csrf
                    <div class="mb-3">
                        <label for="name" class="form-label small fw-bold text-dark">Nama Lengkap <span class="text-danger">*</span></label>
                        <input type="text" class="form-control" id="name" name="name" value="{{ old('name') }}" placeholder="Nama Anda" required autofocus>
                    </div>

                    <div class="mb-3">
                        <label for="email" class="form-label small fw-bold text-dark">Alamat Email <span class="text-danger">*</span></label>
                        <input type="email" class="form-control" id="email" name="email" value="{{ old('email') }}" placeholder="nama@email.com" required>
                    </div>

                    <div class="row g-3 mb-3">
                        <div class="col-md-6">
                            <label for="password" class="form-label small fw-bold text-dark">Kata Sandi <span class="text-danger">*</span></label>
                            <input type="password" class="form-control" id="password" name="password" placeholder="Minimal 8 karakter" required>
                        </div>
                        <div class="col-md-6">
                            <label for="password_confirmation" class="form-label small fw-bold text-dark">Konfirmasi Sandi <span class="text-danger">*</span></label>
                            <input type="password" class="form-control" id="password_confirmation" name="password_confirmation" placeholder="Ulangi kata sandi" required>
                        </div>
                    </div>

                    <div class="mb-3">
                        <label for="phone" class="form-label small fw-bold text-dark">Nomor Telepon / WhatsApp</label>
                        <input type="text" class="form-control" id="phone" name="phone" value="{{ old('phone') }}" placeholder="Contoh: 081234567890">
                    </div>

                    <div class="mb-4">
                        <label for="address" class="form-label small fw-bold text-dark">Alamat Pengiriman (Opsional)</label>
                        <textarea class="form-control" id="address" name="address" rows="2" placeholder="Nama jalan, kota, dan kode pos...">{{ old('address') }}</textarea>
                    </div>

                    <button type="submit" class="btn btn-atelier-primary w-100 py-2 fw-semibold mb-3">
                        Daftarkan Akun Sekarang
                    </button>
                </form>

                <div class="text-center small text-muted">
                    Sudah memiliki akun? <a href="{{ route('login') }}" class="text-maroon fw-semibold">Masuk di sini</a>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
