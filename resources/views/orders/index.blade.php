@extends('layouts.app')

@section('title', 'Riwayat Pesanan Saya - The Book Atelier')

@section('content')
<div class="container py-4">
    <!-- Breadcrumb -->
    <nav aria-label="breadcrumb" class="mb-4">
        <ol class="breadcrumb small">
            <li class="breadcrumb-item"><a href="{{ route('home') }}" class="text-muted">Beranda</a></li>
            <li class="breadcrumb-item active text-dark fw-semibold" aria-current="page">Pesanan Saya</li>
        </ol>
    </nav>

    <div class="d-flex align-items-center justify-content-between mb-4 pb-2 border-bottom">
        <div>
            <h1 class="h3 fw-bold font-serif mb-1"><i class="bi bi-receipt me-2 text-maroon"></i>Pesanan Saya</h1>
            <p class="text-muted small mb-0">Pantau status konfirmasi, pembayaran, dan pengiriman buku Anda</p>
        </div>
        <a href="{{ route('books.index') }}" class="btn btn-outline-dark btn-sm rounded-1 px-3">
            <i class="bi bi-plus-lg me-1"></i> Pesan Buku Lain
        </a>
    </div>

    @if ($orders->isEmpty())
        <div class="card border-0 shadow-sm text-center py-5 rounded-4 mb-5 bg-white">
            <div class="card-body">
                <i class="bi bi-bag-x text-muted" style="font-size: 4rem;"></i>
                <h3 class="fw-bold font-serif mt-3">Belum Ada Riwayat Pesanan</h3>
                <p class="text-muted small mb-4">Anda belum pernah melakukan pemesanan buku di The Book Atelier.</p>
                <a href="{{ route('books.index') }}" class="btn btn-atelier-primary px-4 py-2">
                    <i class="bi bi-arrow-left me-2"></i> Jelajahi Katalog Buku
                </a>
            </div>
        </div>
    @else
        <div class="d-flex flex-column gap-4 mb-5">
            @foreach ($orders as $ord)
                @php
                    $statusBadge = match($ord->order_status) {
                        'Menunggu Konfirmasi' => 'bg-warning text-dark',
                        'Sedang Diproses'     => 'bg-info text-dark',
                        'Sedang Dikirim'      => 'bg-primary',
                        'Selesai'             => 'bg-success',
                        default               => 'bg-danger'
                    };
                    $payBadge = match($ord->payment_status) {
                        'Lunas'                               => 'bg-success',
                        'Sudah Dibayar (Menunggu Verifikasi)' => 'bg-info text-dark',
                        'Menunggu Pembayaran'                 => 'bg-warning text-dark',
                        default                               => 'bg-secondary'
                    };
                @endphp

                <div class="card border-0 shadow-sm rounded-4 overflow-hidden bg-white">
                    <!-- Header Kartu Pesanan -->
                    <div class="card-header bg-light bg-opacity-50 py-3 px-4 d-flex flex-wrap align-items-center justify-content-between gap-2 border-bottom">
                        <div>
                            <span class="small text-muted d-block" style="font-size: 0.75rem;">NOMOR TRANSAKSI</span>
                            <span class="fw-bold font-monospace text-dark">{{ $ord->order_number }}</span>
                            <span class="text-muted small ms-2">&bull; {{ $ord->created_at->format('d M Y, H:i') }} WIB</span>
                        </div>
                        <div class="d-flex align-items-center gap-2">
                            <span class="badge {{ $statusBadge }} px-3 py-1 rounded-pill small">
                                <i class="bi bi-truck me-1"></i>{{ $ord->order_status }}
                            </span>
                            <span class="badge {{ $payBadge }} px-3 py-1 rounded-pill small">
                                <i class="bi bi-cash me-1"></i>{{ $ord->payment_status }}
                            </span>
                        </div>
                    </div>

                    <div class="card-body p-4">
                        <div class="row g-4 align-items-center">
                            <!-- Daftar Item Buku -->
                            <div class="col-lg-7 border-end-lg">
                                <div class="d-flex flex-column gap-3">
                                    @foreach ($ord->items as $item)
                                        <div class="d-flex align-items-center gap-3">
                                            <img src="{{ asset('assets/uploads/' . ($item->book ? $item->book->cover_image : 'default_book.png')) }}" alt="{{ $item->book_title }}" class="rounded shadow-sm" style="width: 45px; height: 60px; object-fit: cover;" onerror="this.src='{{ asset('assets/uploads/default_book.png') }}'">
                                            <div>
                                                <h6 class="fw-bold text-dark mb-1">{{ $item->book_title }}</h6>
                                                <div class="small text-muted">
                                                    {{ $item->quantity }} eks &times; {{ $item->formatted_price }}
                                                    <span class="fw-bold text-maroon ms-2">= {{ $item->formatted_subtotal }}</span>
                                                </div>
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                            </div>

                            <!-- Info Alamat & Total Pembayaran -->
                            <div class="col-lg-5">
                                <div class="small text-secondary mb-3">
                                    <strong class="text-dark d-block mb-1"><i class="bi bi-geo-alt text-maroon me-1"></i>Alamat Pengiriman:</strong>
                                    <span>{{ $ord->recipient_name }} ({{ $ord->recipient_phone }})</span><br>
                                    <span class="text-muted">{{ $ord->shipping_address }}</span>
                                </div>

                                <div class="d-flex align-items-center justify-content-between pt-3 border-top">
                                    <div>
                                        <span class="text-muted small d-block">Total Tagihan:</span>
                                        <span class="fs-4 fw-bold text-maroon font-serif">{{ $ord->formatted_total }}</span>
                                    </div>
                                    <a href="{{ route('orders.show', $ord) }}" class="btn btn-outline-dark btn-sm rounded-1 px-3">
                                        Detail Pesanan <i class="bi bi-arrow-right ms-1"></i>
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    @endif
</div>
@endsection
