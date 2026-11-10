@extends('layouts.app')

@section('title', 'Detail Pesanan ' . $order->order_number . ' - The Book Atelier')

@section('content')
<div class="container py-4">
    <!-- Breadcrumb -->
    <nav aria-label="breadcrumb" class="mb-4 d-print-none">
        <ol class="breadcrumb small">
            <li class="breadcrumb-item"><a href="{{ route('home') }}" class="text-muted">Beranda</a></li>
            <li class="breadcrumb-item"><a href="{{ route('orders.index') }}" class="text-muted">Pesanan Saya</a></li>
            <li class="breadcrumb-item active text-dark fw-semibold" aria-current="page">{{ $order->order_number }}</li>
        </ol>
    </nav>

    <div class="d-flex align-items-center justify-content-between mb-4 pb-2 border-bottom">
        <div>
            <h1 class="h3 fw-bold font-serif mb-1">Rincian Invoice Pesanan</h1>
            <span class="text-muted small">Nomor Transaksi: <strong class="text-dark font-monospace">{{ $order->order_number }}</strong></span>
        </div>
        <div class="d-flex gap-2 d-print-none">
            <button type="button" class="btn btn-outline-secondary btn-sm rounded-1" onclick="window.print()">
                <i class="bi bi-printer me-1"></i> Cetak Invoice
            </button>
            <a href="{{ route('orders.index') }}" class="btn btn-outline-dark btn-sm rounded-1">
                <i class="bi bi-arrow-left me-1"></i> Kembali
            </a>
        </div>
    </div>

    <div class="card border-0 shadow-sm rounded-4 p-4 p-md-5 bg-white mb-5">
        <div class="row g-4 mb-4 pb-4 border-bottom">
            <div class="col-md-6">
                <h6 class="fw-bold text-uppercase text-muted small mb-2">Informasi Penerima &amp; Pengiriman</h6>
                <strong class="fs-5 text-dark d-block">{{ $order->recipient_name }}</strong>
                <span class="text-muted d-block"><i class="bi bi-telephone me-1"></i>{{ $order->recipient_phone }}</span>
                <p class="text-secondary small mt-2 mb-0 lh-base">
                    {{ $order->shipping_address }}
                </p>
                @if ($order->notes)
                    <div class="mt-2 p-2 bg-light rounded text-muted small fst-italic">
                        Catatan: "{{ $order->notes }}"
                    </div>
                @endif
            </div>

            <div class="col-md-6 text-md-end">
                <h6 class="fw-bold text-uppercase text-muted small mb-2">Status &amp; Pembayaran</h6>
                <div class="mb-2">
                    <span class="badge bg-primary px-3 py-2 rounded-pill small">{{ $order->order_status }}</span>
                    <span class="badge bg-secondary px-3 py-2 rounded-pill small ms-1">{{ $order->payment_status }}</span>
                </div>
                <span class="text-muted small d-block">Tanggal: {{ $order->created_at->format('d F Y, H:i') }} WIB</span>
                <span class="text-muted small d-block">Metode: {{ $order->payment_method }}</span>
            </div>
        </div>

        <!-- Tabel Item Buku -->
        <h6 class="fw-bold font-serif mb-3">Daftar Buku yang Dipesan</h6>
        <div class="table-responsive border rounded-3 overflow-hidden mb-4">
            <table class="table align-middle mb-0">
                <thead class="table-light small text-uppercase">
                    <tr>
                        <th style="width: 5%;">#</th>
                        <th style="width: 55%;">Judul Buku</th>
                        <th style="width: 15%;">Harga Satuan</th>
                        <th style="width: 10%;" class="text-center">Kuantitas</th>
                        <th style="width: 15%;" class="text-end pe-3">Subtotal</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($order->items as $idx => $it)
                        <tr>
                            <td>{{ $idx + 1 }}</td>
                            <td>
                                <div class="d-flex align-items-center gap-2">
                                    <img src="{{ asset('assets/uploads/' . ($it->book ? $it->book->cover_image : 'default_book.png')) }}" alt="{{ $it->book_title }}" class="rounded shadow-sm" style="width: 38px; height: 50px; object-fit: cover;" onerror="this.src='{{ asset('assets/uploads/default_book.png') }}'">
                                    <strong class="text-dark">{{ $it->book_title }}</strong>
                                </div>
                            </td>
                            <td class="small">{{ $it->formatted_price }}</td>
                            <td class="text-center small">{{ $it->quantity }} eks</td>
                            <td class="text-end pe-3 fw-bold text-maroon">{{ $it->formatted_subtotal }}</td>
                        </tr>
                    @endforeach
                </tbody>
                <tfoot class="table-light">
                    <tr>
                        <td colspan="4" class="text-end fw-bold">Total Tagihan:</td>
                        <td class="text-end pe-3 fs-5 fw-bold text-maroon font-serif">{{ $order->formatted_total }}</td>
                    </tr>
                </tfoot>
            </table>
        </div>

        <div class="p-3 bg-light rounded-3 text-muted small d-flex align-items-center gap-2">
            <i class="bi bi-info-circle-fill text-primary fs-5"></i>
            <div>
                Jika Anda memiliki pertanyaan mengenai pengiriman pesanan ini, silakan hubungi tim kami via menu <a href="{{ route('contact.index') }}" class="text-primary fw-semibold">Chat Concierge</a> dengan menyertakan nomor pesanan <strong>{{ $order->order_number }}</strong>.
            </div>
        </div>
    </div>
</div>
@endsection
