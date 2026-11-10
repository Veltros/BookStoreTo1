@extends('layouts.admin')

@section('title', 'Manajemen Pesanan')

@section('content')
<div class="d-flex flex-wrap align-items-center justify-content-between gap-3 mb-4">
    <div>
        <h2 class="fw-bold mb-1"><i class="bi bi-bag-check text-primary me-2"></i>Manajemen Pesanan</h2>
        <p class="text-muted small mb-0">Kelola dan perbarui status pesanan buku pelanggan</p>
    </div>
</div>

<!-- Filter & Search -->
<div class="card shadow-sm border-0 rounded-4 mb-4 bg-white">
    <div class="card-body p-3">
        <form method="GET" action="{{ route('admin.orders.index') }}" class="row g-2 align-items-center">
            <div class="col-md-6">
                <div class="input-group">
                    <span class="input-group-text bg-white"><i class="bi bi-search text-muted"></i></span>
                    <input type="text" name="q" class="form-control border-start-0" placeholder="Cari no. pesanan, nama penerima, no. telp..." value="{{ $search }}">
                </div>
            </div>
            <div class="col-md-4">
                <select name="status" class="form-select">
                    <option value="">Semua Status Pengiriman</option>
                    @foreach (['Menunggu Konfirmasi', 'Sedang Diproses', 'Sedang Dikirim', 'Selesai', 'Dibatalkan'] as $st)
                        <option value="{{ $st }}" {{ $statusFilter === $st ? 'selected' : '' }}>{{ $st }}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-2 d-flex gap-2">
                <button type="submit" class="btn btn-primary flex-grow-1 rounded-3">Filter</button>
                @if (!empty($search) || !empty($statusFilter))
                    <a href="{{ route('admin.orders.index') }}" class="btn btn-outline-secondary rounded-3" title="Reset Filter">
                        <i class="bi bi-x-lg"></i>
                    </a>
                @endif
            </div>
        </form>
    </div>
</div>

<!-- Tabel Daftar Pesanan -->
<div class="card shadow-sm border-0 rounded-4 overflow-hidden mb-5 bg-white">
    <div class="table-responsive">
        <table class="table align-middle mb-0">
            <thead class="table-light small">
                <tr>
                    <th>No. Pesanan</th>
                    <th>Nama Penerima</th>
                    <th>Waktu Pesan</th>
                    <th>Total Biaya</th>
                    <th>Status Pengiriman</th>
                    <th>Status Pembayaran</th>
                    <th class="text-center">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @if ($orders->isEmpty())
                    <tr>
                        <td colspan="7" class="text-center py-5 text-muted">
                            <i class="bi bi-bag-x fs-1 d-block mb-2"></i>
                            Tidak ada data pesanan yang sesuai kriteria pencarian.
                        </td>
                    </tr>
                @else
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
                        <tr>
                            <td>
                                <strong class="font-monospace text-dark">{{ $ord->order_number }}</strong>
                            </td>
                            <td>
                                <div class="fw-bold text-dark">{{ $ord->recipient_name }}</div>
                                <small class="text-muted">{{ $ord->recipient_phone }}</small>
                            </td>
                            <td class="small text-muted">{{ $ord->created_at->format('d M Y, H:i') }} WIB</td>
                            <td class="fw-bold text-dark">{{ $ord->formatted_total }}</td>
                            <td>
                                <span class="badge {{ $statusBadge }} px-3 py-1 rounded-pill small">
                                    {{ $ord->order_status }}
                                </span>
                            </td>
                            <td>
                                <span class="badge {{ $payBadge }} px-3 py-1 rounded-pill small">
                                    {{ $ord->payment_status }}
                                </span>
                            </td>
                            <td class="text-center">
                                <a href="{{ route('admin.orders.show', $ord) }}" class="btn btn-outline-primary btn-sm rounded-1 px-3">
                                    Kelola <i class="bi bi-arrow-right ms-1"></i>
                                </a>
                            </td>
                        </tr>
                    @endforeach
                @endif
            </tbody>
        </table>
    </div>
</div>

<div class="d-flex justify-content-center mb-5">
    {{ $orders->links('pagination::bootstrap-5') }}
</div>
@endsection
