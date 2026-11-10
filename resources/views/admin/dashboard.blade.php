@extends('layouts.admin')

@section('title', 'Dashboard Administrator')

@section('content')
<div class="d-flex flex-wrap align-items-center justify-content-between gap-3 mb-4">
    <div>
        <h2 class="fw-bold mb-1"><i class="bi bi-speedometer2 text-primary me-2"></i>Dashboard Manajemen</h2>
        <p class="text-muted small mb-0">Ringkasan statistik transaksi dan performa operasional The Book Atelier</p>
    </div>
    <div class="d-flex gap-2">
        <a href="{{ route('admin.books.create') }}" class="btn btn-primary rounded-pill px-3">
            <i class="bi bi-plus-lg me-1"></i> Tambah Buku
        </a>
        <a href="{{ route('admin.orders.index') }}" class="btn btn-outline-dark rounded-pill px-3">
            <i class="bi bi-bag-check me-1"></i> Lihat Pesanan
        </a>
    </div>
</div>

<!-- Statistik Metrik -->
<div class="row row-cols-1 row-cols-sm-2 row-cols-lg-4 g-3 mb-4">
    <div class="col">
        <div class="card border-0 shadow-sm rounded-4 p-3 bg-white h-100">
            <div class="d-flex align-items-center justify-content-between">
                <div>
                    <span class="text-muted small d-block mb-1">Total Koleksi Buku</span>
                    <h3 class="fw-bold mb-0 text-dark">{{ $totalBooks }}</h3>
                </div>
                <div class="rounded-circle bg-primary bg-opacity-10 p-3 text-primary">
                    <i class="bi bi-book fs-3"></i>
                </div>
            </div>
            <small class="text-muted mt-2 d-block pt-2 border-top">Dari {{ $totalCategories }} kategori aktif</small>
        </div>
    </div>

    <div class="col">
        <div class="card border-0 shadow-sm rounded-4 p-3 bg-white h-100">
            <div class="d-flex align-items-center justify-content-between">
                <div>
                    <span class="text-muted small d-block mb-1">Total Pesanan</span>
                    <h3 class="fw-bold mb-0 text-dark">{{ $totalOrders }}</h3>
                </div>
                <div class="rounded-circle bg-success bg-opacity-10 p-3 text-success">
                    <i class="bi bi-bag-check fs-3"></i>
                </div>
            </div>
            <small class="text-muted mt-2 d-block pt-2 border-top">
                @if ($pendingOrders > 0)
                    <span class="text-warning fw-bold">{{ $pendingOrders }} menunggu konfirmasi</span>
                @else
                    Semua pesanan terproses
                @endif
            </small>
        </div>
    </div>

    <div class="col">
        <div class="card border-0 shadow-sm rounded-4 p-3 bg-white h-100">
            <div class="d-flex align-items-center justify-content-between">
                <div>
                    <span class="text-muted small d-block mb-1">Total Pendapatan</span>
                    <h3 class="fw-bold mb-0 text-dark" style="font-size: 1.35rem;">Rp {{ number_format($totalRevenue, 0, ',', '.') }}</h3>
                </div>
                <div class="rounded-circle bg-warning bg-opacity-10 p-3 text-warning">
                    <i class="bi bi-cash-stack fs-3"></i>
                </div>
            </div>
            <small class="text-muted mt-2 d-block pt-2 border-top">Akumulasi pesanan diproses</small>
        </div>
    </div>

    <div class="col">
        <div class="card border-0 shadow-sm rounded-4 p-3 bg-white h-100">
            <div class="d-flex align-items-center justify-content-between">
                <div>
                    <span class="text-muted small d-block mb-1">Pelanggan Terdaftar</span>
                    <h3 class="fw-bold mb-0 text-dark">{{ $totalUsers }}</h3>
                </div>
                <div class="rounded-circle bg-info bg-opacity-10 p-3 text-info">
                    <i class="bi bi-people fs-3"></i>
                </div>
            </div>
            <small class="text-muted mt-2 d-block pt-2 border-top">{{ $unreadMessages }} pesan user belum dibaca</small>
        </div>
    </div>
</div>

<!-- Pesanan Terbaru -->
<div class="card border-0 shadow-sm rounded-4 overflow-hidden mb-4 bg-white">
    <div class="card-header bg-white py-3 px-4 d-flex align-items-center justify-content-between">
        <h5 class="fw-bold mb-0"><i class="bi bi-clock-history text-primary me-2"></i>Pesanan Masuk Terbaru</h5>
        <a href="{{ route('admin.orders.index') }}" class="small text-primary fw-semibold">Lihat Semua Pesanan &rarr;</a>
    </div>

    <div class="table-responsive">
        <table class="table align-middle mb-0">
            <thead class="table-light small">
                <tr>
                    <th>No. Pesanan</th>
                    <th>Pelanggan</th>
                    <th>Nominal</th>
                    <th>Status Pengiriman</th>
                    <th>Status Pembayaran</th>
                    <th class="text-center">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @if ($recentOrders->isEmpty())
                    <tr>
                        <td colspan="6" class="text-center py-4 text-muted">Belum ada pesanan terbaru.</td>
                    </tr>
                @else
                    @foreach ($recentOrders as $ord)
                        <tr>
                            <td>
                                <strong class="font-monospace text-dark">{{ $ord->order_number }}</strong>
                                <small class="d-block text-muted">{{ $ord->created_at->format('d M, H:i') }} WIB</small>
                            </td>
                            <td>
                                <span class="fw-semibold text-dark">{{ $ord->recipient_name }}</span>
                                <small class="d-block text-muted">{{ $ord->recipient_phone }}</small>
                            </td>
                            <td class="fw-bold text-dark">{{ $ord->formatted_total }}</td>
                            <td>
                                <span class="badge bg-primary-subtle text-primary px-3 py-1 rounded-pill small">
                                    {{ $ord->order_status }}
                                </span>
                            </td>
                            <td>
                                <span class="badge bg-secondary-subtle text-secondary px-3 py-1 rounded-pill small">
                                    {{ $ord->payment_status }}
                                </span>
                            </td>
                            <td class="text-center">
                                <a href="{{ route('admin.orders.show', $ord) }}" class="btn btn-outline-dark btn-sm rounded-1 px-3">
                                    Detail
                                </a>
                            </td>
                        </tr>
                    @endforeach
                @endif
            </tbody>
        </table>
    </div>
</div>
@endsection
