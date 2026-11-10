@extends('layouts.app')

@section('title', 'Pesanan Berhasil Dibuat - The Book Atelier')

@section('content')
<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-lg-8">
            <div class="card border-0 shadow-sm rounded-4 p-4 p-md-5 bg-white text-center">
                <div class="mb-3">
                    <span class="rounded-circle bg-success bg-opacity-10 d-inline-flex align-items-center justify-content-center" style="width: 80px; height: 80px;">
                        <i class="bi bi-check2-circle text-success fs-1"></i>
                    </span>
                </div>

                <h1 class="h3 fw-bold font-serif mb-2">Terima Kasih! Pesanan Anda Telah Kami Terima</h1>
                <p class="text-muted small mb-4">
                    Nomor Pesanan: <strong class="text-dark font-monospace">{{ $order->order_number }}</strong>
                </p>

                <!-- Instruksi Pembayaran Bank -->
                <div class="card bg-light border-0 rounded-4 p-4 text-start mb-4">
                    <h5 class="fw-bold font-serif text-dark mb-2">Instruksi Pembayaran &mdash; Payment at Delivery</h5>
                    <p class="small text-secondary mb-3">
                        Silakan selesaikan transfer sejumlah tagihan di bawah ini sebelum pesanan kami proses dan kirimkan ke alamat Anda.
                    </p>

                    <div class="row g-3 mb-3">
                        <div class="col-sm-6">
                            <span class="text-muted small d-block">Metode Pembayaran:</span>
                            <strong class="text-dark">{{ $order->payment_method }}</strong>
                        </div>
                        <div class="col-sm-6">
                            <span class="text-muted small d-block">Total Nominal Transfer:</span>
                            <span class="fs-4 fw-bold text-maroon font-serif">{{ $order->formatted_total }}</span>
                        </div>
                    </div>

                    <div class="p-3 bg-white border rounded-3 d-flex align-items-center justify-content-between">
                        <div>
                            <span class="text-muted small d-block">Rekening Resmi The Book Atelier:</span>
                            <span class="fs-5 fw-bold font-monospace text-dark">123-456-7890</span>
                            <small class="d-block text-muted">a.n The Book Atelier Official</small>
                        </div>
                        <button type="button" class="btn btn-outline-secondary btn-sm rounded-1 btn-copy-clipboard" data-clipboard-text="1234567890">
                            <i class="bi bi-copy me-1"></i> Salin
                        </button>
                    </div>
                </div>

                <!-- Rincian Buku yang Dipesan -->
                <div class="text-start mb-4">
                    <h6 class="fw-bold text-uppercase small text-muted mb-3" style="letter-spacing: 0.08em;">Buku yang Dipesan ({{ $order->items->count() }})</h6>
                    <div class="table-responsive border rounded-3 overflow-hidden">
                        <table class="table align-middle mb-0 small">
                            <thead class="table-light">
                                <tr>
                                    <th>Judul Buku</th>
                                    <th>Harga</th>
                                    <th class="text-center">Jumlah</th>
                                    <th class="text-end pe-3">Subtotal</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($order->items as $it)
                                    <tr>
                                        <td>
                                            <div class="d-flex align-items-center gap-2">
                                                <img src="{{ asset('assets/uploads/' . ($it->book ? $it->book->cover_image : 'default_book.png')) }}" alt="{{ $it->book_title }}" class="rounded shadow-sm" style="width: 36px; height: 50px; object-fit: cover;" onerror="this.src='{{ asset('assets/uploads/default_book.png') }}'">
                                                <span class="fw-semibold text-dark">{{ $it->book_title }}</span>
                                            </div>
                                        </td>
                                        <td>{{ $it->formatted_price }}</td>
                                        <td class="text-center">{{ $it->quantity }} eks</td>
                                        <td class="text-end pe-3 fw-bold text-maroon">{{ $it->formatted_subtotal }}</td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>

                <!-- Aksi Lanjutan -->
                <div class="d-flex flex-wrap justify-content-center gap-3">
                    <a href="{{ route('orders.index') }}" class="btn btn-atelier-primary px-4 py-2">
                        <i class="bi bi-bag-check me-2"></i> Pantau di Pesanan Saya
                    </a>
                    <a href="{{ route('books.index') }}" class="btn btn-atelier-outline px-4 py-2">
                        Belanja Buku Lainnya
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
