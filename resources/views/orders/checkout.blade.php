@extends('layouts.app')

@section('title', 'Checkout Pesanan - The Book Atelier')

@section('content')
<div class="container py-4">
    <!-- Breadcrumb -->
    <nav aria-label="breadcrumb" class="mb-4">
        <ol class="breadcrumb small">
            <li class="breadcrumb-item"><a href="{{ route('home') }}" class="text-muted">Beranda</a></li>
            <li class="breadcrumb-item"><a href="{{ route('cart.index') }}" class="text-muted">Keranjang Belanja</a></li>
            <li class="breadcrumb-item active text-dark fw-semibold" aria-current="page">Konfirmasi Checkout</li>
        </ol>
    </nav>

    <div class="d-flex align-items-center justify-content-between mb-4 pb-2 border-bottom">
        <div>
            <h1 class="h3 fw-bold font-serif mb-1"><i class="bi bi-shield-check me-2 text-maroon"></i>Checkout Pesanan</h1>
            <p class="text-muted small mb-0">Lengkapi alamat pengiriman dan pilih metode pembayaran</p>
        </div>
    </div>

    @if ($errors->any())
        <div class="alert alert-danger border-danger border-2 rounded-3 mb-4">
            <h6 class="fw-bold mb-2"><i class="bi bi-exclamation-triangle-fill me-2"></i>Harap periksa isian formulir:</h6>
            <ul class="mb-0 ps-3 small">
                @foreach ($errors->all() as $err)
                    <li>{{ $err }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form method="POST" action="{{ route('orders.place') }}">
        @csrf
        <div class="row g-4 mb-5">
            <!-- Kolom Kiri: Formulir Pengiriman & Pembayaran -->
            <div class="col-lg-8">
                <!-- 1. Alamat Pengiriman -->
                <div class="card border-0 shadow-sm rounded-4 p-4 mb-4 bg-white">
                    <h5 class="fw-bold font-serif mb-3 pb-2 border-bottom">
                        <i class="bi bi-geo-alt-fill text-maroon me-2"></i>1. Alamat Pengiriman Buku
                    </h5>

                    <div class="row g-3">
                        <div class="col-md-6">
                            <label for="recipient_name" class="form-label small fw-bold text-dark">Nama Penerima Paket <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" id="recipient_name" name="recipient_name" value="{{ old('recipient_name', $user->name) }}" required>
                        </div>
                        <div class="col-md-6">
                            <label for="recipient_phone" class="form-label small fw-bold text-dark">Nomor Telepon / WhatsApp <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" id="recipient_phone" name="recipient_phone" value="{{ old('recipient_phone', $user->phone) }}" placeholder="Contoh: 081234567890" required>
                        </div>
                        <div class="col-12">
                            <label for="shipping_address" class="form-label small fw-bold text-dark">Alamat Pengiriman Lengkap <span class="text-danger">*</span></label>
                            <textarea class="form-control" id="shipping_address" name="shipping_address" rows="3" placeholder="Sebutkan nama jalan, nomor rumah, RT/RW, kelurahan, kecamatan, kota, dan kode pos..." required>{{ old('shipping_address', $user->address) }}</textarea>
                            <span class="text-muted small" style="font-size: 0.75rem;">* Alamat ini akan digunakan kurir untuk mengantar paket pesanan Anda.</span>
                        </div>
                        <div class="col-12">
                            <label for="notes" class="form-label small fw-bold text-dark">Catatan Khusus Pengiriman (Opsional)</label>
                            <input type="text" class="form-control" id="notes" name="notes" value="{{ old('notes') }}" placeholder="Contoh: Titipkan ke satpam jika rumah sedang kosong.">
                        </div>
                    </div>
                </div>

                <!-- 2. Metode Pembayaran -->
                <div class="card border-0 shadow-sm rounded-4 p-4 bg-white">
                    <h5 class="fw-bold font-serif mb-3 pb-2 border-bottom">
                        <i class="bi bi-credit-card-2-front-fill text-maroon me-2"></i>2. Metode Pembayaran: Payment at Delivery
                    </h5>

                    <div class="alert alert-info border-primary small py-3 mb-3">
                        <i class="bi bi-info-circle-fill me-1"></i>
                        <strong>Payment at Delivery:</strong> Pembayaran diselesaikan melalui transfer bank resmi sebelum buku dikirimkan oleh kurir kami.
                    </div>

                    <div class="row row-cols-1 row-cols-md-2 g-3">
                        <div class="col">
                            <label class="form-check p-3 border rounded-3 d-flex align-items-center gap-3 cursor-pointer">
                                <input class="form-check-input mt-0" type="radio" name="payment_channel" value="Transfer Bank BCA" checked>
                                <div>
                                    <strong class="d-block text-dark">Bank Central Asia (BCA)</strong>
                                    <small class="text-muted">No. Rek: 123-456-7890 (a.n The Book Atelier)</small>
                                </div>
                            </label>
                        </div>
                        <div class="col">
                            <label class="form-check p-3 border rounded-3 d-flex align-items-center gap-3 cursor-pointer">
                                <input class="form-check-input mt-0" type="radio" name="payment_channel" value="Transfer Bank Mandiri">
                                <div>
                                    <strong class="d-block text-dark">Bank Mandiri</strong>
                                    <small class="text-muted">No. Rek: 137-00-1234567-8</small>
                                </div>
                            </label>
                        </div>
                        <div class="col">
                            <label class="form-check p-3 border rounded-3 d-flex align-items-center gap-3 cursor-pointer">
                                <input class="form-check-input mt-0" type="radio" name="payment_channel" value="Transfer Bank BRI">
                                <div>
                                    <strong class="d-block text-dark">Bank BRI</strong>
                                    <small class="text-muted">No. Rek: 0341-01-000123-30-5</small>
                                </div>
                            </label>
                        </div>
                        <div class="col">
                            <label class="form-check p-3 border rounded-3 d-flex align-items-center gap-3 cursor-pointer">
                                <input class="form-check-input mt-0" type="radio" name="payment_channel" value="Transfer Bank BNI">
                                <div>
                                    <strong class="d-block text-dark">Bank BNI</strong>
                                    <small class="text-muted">No. Rek: 0234-5678-90</small>
                                </div>
                            </label>
                        </div>
                            <label class="form-check p-3 border rounded-3 d-flex align-items-center gap-3 cursor-pointer">
                                <input class="form-check-input mt-0" type="radio" name="payment_channel" value="Transfer Bank BRI">
                                <div>
                                    <strong class="d-block text-dark">Bank BRI</strong>
                                    <small class="text-muted">No. Rek: 0341-01-000123-30-5</small>
                                </div>
                            </label>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Kolom Kanan: Rincian Pesanan Buku -->
            <div class="col-lg-4">
                <div class="card border-0 shadow-sm rounded-4 p-4 bg-white sticky-top" style="top: 100px;">
                    <h5 class="fw-bold font-serif mb-3 pb-2 border-bottom">Rincian Buku</h5>

                    <div class="d-flex flex-column gap-3 mb-3" style="max-height: 250px; overflow-y: auto;">
                        @foreach ($cart as $item)
                            <div class="d-flex align-items-center justify-content-between text-secondary small">
                                <div class="text-truncate pe-2">
                                    <strong class="text-dark d-block text-truncate">{{ $item['title'] }}</strong>
                                    <span>{{ $item['qty'] }} x Rp {{ number_format($item['price'], 0, ',', '.') }}</span>
                                </div>
                                <span class="fw-bold text-nowrap text-dark">Rp {{ number_format($item['price'] * $item['qty'], 0, ',', '.') }}</span>
                            </div>
                        @endforeach
                    </div>

                    <div class="pt-3 border-top mb-4">
                        <div class="d-flex justify-content-between mb-1 small text-muted">
                            <span>Ongkos Kirim:</span>
                            <span class="text-success fw-bold">GRATIS</span>
                        </div>
                        <div class="d-flex justify-content-between pt-2">
                            <span class="fw-bold text-dark">Total Pembayaran:</span>
                            <span class="fs-4 fw-bold text-maroon font-serif">Rp {{ number_format($totalPrice, 0, ',', '.') }}</span>
                        </div>
                    </div>

                    <button type="submit" class="btn btn-atelier-primary w-100 py-3 mb-2">
                        <span>Konfirmasi & Buat Pesanan</span>
                        <i class="bi bi-bag-check ms-2"></i>
                    </button>

                    <a href="{{ route('cart.index') }}" class="btn btn-outline-secondary btn-sm w-100 py-2">
                        <i class="bi bi-arrow-left me-1"></i> Kembali ke Keranjang
                    </a>
                </div>
            </div>
        </div>
    </form>
</div>
@endsection
