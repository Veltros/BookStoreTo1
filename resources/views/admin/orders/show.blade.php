@extends('layouts.admin')

@section('title', 'Detail Pesanan ' . $order->order_number)

@section('content')
<div class="d-flex align-items-center justify-content-between mb-4">
    <div>
        <h2 class="fw-bold mb-1"><i class="bi bi-receipt text-primary me-2"></i>Rincian Pesanan #{{ $order->order_number }}</h2>
        <p class="text-muted small mb-0">Waktu Transaksi: {{ $order->created_at->format('d F Y, H:i') }} WIB</p>
    </div>
    <a href="{{ route('admin.orders.index') }}" class="btn btn-outline-secondary rounded-pill px-3">
        <i class="bi bi-arrow-left me-1"></i> Kembali ke Daftar
    </a>
</div>

<div class="row g-4 mb-5">
    <!-- Kolom Kiri: Rincian Produk & Pengiriman -->
    <div class="col-lg-8">
        <!-- Rincian Buku -->
        <div class="card border-0 shadow-sm rounded-4 p-4 mb-4 bg-white">
            <h5 class="fw-bold font-serif mb-3 pb-2 border-bottom">Buku yang Dipesan</h5>
            <div class="table-responsive">
                <table class="table align-middle mb-0">
                    <thead class="table-light small">
                        <tr>
                            <th>Buku</th>
                            <th>Harga Satuan</th>
                            <th class="text-center">Jumlah</th>
                            <th class="text-end pe-3">Subtotal</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($order->items as $it)
                            <tr>
                                <td>
                                    <div class="d-flex align-items-center gap-3">
                                        <img src="{{ asset('assets/uploads/' . ($it->book ? $it->book->cover_image : 'default_book.png')) }}" alt="{{ $it->book_title }}" class="rounded shadow-sm" style="width: 45px; height: 60px; object-fit: cover;" onerror="this.src='{{ asset('assets/uploads/default_book.png') }}'">
                                        <div>
                                            <strong class="text-dark d-block">{{ $it->book_title }}</strong>
                                        </div>
                                    </div>
                                </td>
                                <td class="small">{{ $it->formatted_price }}</td>
                                <td class="text-center small">{{ $it->quantity }} eks</td>
                                <td class="text-end pe-3 fw-bold text-primary">{{ $it->formatted_subtotal }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                    <tfoot class="table-light">
                        <tr>
                            <td colspan="3" class="text-end fw-bold">Total Pembayaran:</td>
                            <td class="text-end pe-3 fs-5 fw-bold text-primary font-serif">{{ $order->formatted_total }}</td>
                        </tr>
                    </tfoot>
                </table>
            </div>
        </div>

        <!-- Alamat Pengiriman -->
        <div class="card border-0 shadow-sm rounded-4 p-4 bg-white">
            <h5 class="fw-bold font-serif mb-3 pb-2 border-bottom">Informasi Pengiriman</h5>
            <div class="row g-3 small">
                <div class="col-sm-6">
                    <span class="text-muted d-block">Nama Penerima Paket:</span>
                    <strong class="text-dark fs-6">{{ $order->recipient_name }}</strong>
                </div>
                <div class="col-sm-6">
                    <span class="text-muted d-block">No. Telepon / WhatsApp:</span>
                    <strong class="text-dark fs-6">{{ $order->recipient_phone }}</strong>
                </div>
                <div class="col-12">
                    <span class="text-muted d-block">Alamat Tujuan:</span>
                    <p class="text-dark mb-0 lh-base">{{ $order->shipping_address }}</p>
                </div>
                @if ($order->notes)
                    <div class="col-12">
                        <span class="text-muted d-block">Catatan Pelanggan:</span>
                        <div class="p-2 bg-light rounded fst-italic text-secondary">{{ $order->notes }}</div>
                    </div>
                @endif
            </div>
        </div>
    </div>

    <!-- Kolom Kanan: Update Status Pesanan -->
    <div class="col-lg-4">
        <div class="card border-0 shadow-sm rounded-4 p-4 bg-white sticky-top" style="top: 100px;">
            <h5 class="fw-bold font-serif mb-3 pb-2 border-bottom">Perbarui Status</h5>

            <form method="POST" action="{{ route('admin.orders.updateStatus', $order) }}">
                @csrf
                @method('PUT')

                <div class="mb-3">
                    <label for="order_status" class="form-label small fw-bold text-dark">Status Pengiriman</label>
                    <select name="order_status" id="order_status" class="form-select">
                        @foreach (['Menunggu Konfirmasi', 'Sedang Diproses', 'Sedang Dikirim', 'Selesai', 'Dibatalkan'] as $st)
                            <option value="{{ $st }}" {{ $order->order_status === $st ? 'selected' : '' }}>{{ $st }}</option>
                        @endforeach
                    </select>
                </div>

                <div class="mb-4">
                    <label for="payment_status" class="form-label small fw-bold text-dark">Status Pembayaran</label>
                    <select name="payment_status" id="payment_status" class="form-select">
                        @foreach (['Menunggu Pembayaran', 'Sudah Dibayar (Menunggu Verifikasi)', 'Lunas', 'Dibatalkan'] as $ps)
                            <option value="{{ $ps }}" {{ $order->payment_status === $ps ? 'selected' : '' }}>{{ $ps }}</option>
                        @endforeach
                    </select>
                </div>

                <button type="submit" class="btn btn-primary w-100 py-2 fw-semibold">
                    <i class="bi bi-check2-circle me-1"></i> Simpan Pembaruan
                </button>
            </form>

            <div class="mt-4 pt-3 border-top small text-muted">
                <span class="d-block mb-1"><strong>Metode:</strong> {{ $order->payment_method }}</span>
                <span class="d-block"><strong>Akun Pemesan:</strong> {{ $order->user ? $order->user->name . ' (' . $order->user->email . ')' : 'Guest' }}</span>
            </div>
        </div>
    </div>
</div>
@endsection
