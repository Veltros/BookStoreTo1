@extends('layouts.admin')

@section('title', 'Kelola Data Buku')

@section('content')
<div class="d-flex flex-wrap align-items-center justify-content-between gap-3 mb-4">
    <div>
        <h2 class="fw-bold mb-1"><i class="bi bi-book text-primary me-2"></i>Kelola Data Buku</h2>
        <p class="text-muted small mb-0">Manajemen katalog buku, harga, stok, dan gambar sampul</p>
    </div>
    <a href="{{ route('admin.books.create') }}" class="btn btn-primary rounded-pill px-4">
        <i class="bi bi-plus-lg me-1"></i> Tambah Buku Baru
    </a>
</div>

<!-- Filter & Search Bar -->
<div class="card shadow-sm border-0 rounded-4 mb-4 bg-white">
    <div class="card-body p-3">
        <form method="GET" action="{{ route('admin.books.index') }}" class="row g-2 align-items-center">
            <div class="col-md-5">
                <div class="input-group">
                    <span class="input-group-text bg-white"><i class="bi bi-search text-muted"></i></span>
                    <input type="text" name="q" class="form-control border-start-0" placeholder="Cari judul, penulis, penerbit..." value="{{ $search }}">
                </div>
            </div>
            <div class="col-md-4">
                <select name="category" class="form-select">
                    <option value="">Semua Kategori</option>
                    @foreach ($categories as $cat)
                        <option value="{{ $cat->id }}" {{ $catFilter == $cat->id ? 'selected' : '' }}>
                            {{ $cat->name }}
                        </option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-3 d-flex gap-2">
                <button type="submit" class="btn btn-primary flex-grow-1 rounded-3">
                    Filter Data
                </button>
                @if (!empty($search) || $catFilter)
                    <a href="{{ route('admin.books.index') }}" class="btn btn-outline-secondary rounded-3" title="Reset Filter">
                        <i class="bi bi-x-lg"></i>
                    </a>
                @endif
            </div>
        </form>
    </div>
</div>

<!-- Tabel Daftar Buku -->
<div class="card shadow-sm border-0 rounded-4 overflow-hidden mb-5 bg-white">
    <div class="table-responsive">
        <table class="table align-middle mb-0">
            <thead class="table-light small">
                <tr>
                    <th style="width: 5%;">#</th>
                    <th style="width: 10%;">Sampul</th>
                    <th style="width: 30%;">Judul &amp; Penulis</th>
                    <th style="width: 15%;">Kategori</th>
                    <th style="width: 15%;">Harga</th>
                    <th class="text-center" style="width: 10%;">Stok</th>
                    <th class="text-center" style="width: 15%;">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @if ($books->isEmpty())
                    <tr>
                        <td colspan="7" class="text-center py-5 text-muted">
                            <i class="bi bi-journal-x fs-1 d-block mb-2"></i>
                            Tidak ada data buku yang sesuai kriteria pencarian.
                        </td>
                    </tr>
                @else
                    @foreach ($books as $index => $b)
                        <tr>
                            <td>{{ $books->firstItem() + $index }}</td>
                            <td>
                                <img src="{{ $b->cover_url }}" alt="{{ $b->title }}" class="rounded shadow-sm" style="width: 50px; height: 70px; object-fit: cover;">
                            </td>
                            <td>
                                <div class="fw-bold text-dark mb-1">{{ $b->title }}</div>
                                <div class="small text-muted">
                                    <i class="bi bi-person me-1"></i>{{ $b->author }}
                                    @if ($b->publisher)
                                        &bull; {{ $b->publisher }} ({{ $b->year ?: '-' }})
                                    @endif
                                </div>
                                @if ($b->isbn)
                                    <div class="small text-muted font-monospace mt-1">
                                        <i class="bi bi-upc-scan me-1 text-primary"></i>ISBN: {{ $b->isbn }}
                                    </div>
                                @endif
                                @if ($b->order_items_count > 0)
                                    <div class="mt-1">
                                        <span class="badge bg-primary-subtle text-primary border border-primary-subtle rounded-pill" style="font-size: 0.72rem;">
                                            <i class="bi bi-bag-check-fill me-1"></i>{{ $b->order_items_count }} Pesanan User
                                        </span>
                                    </div>
                                @endif
                            </td>
                            <td>
                                <span class="badge bg-light text-dark border px-2 py-1 rounded-pill">
                                    {{ $b->category->name }}
                                </span>
                            </td>
                            <td class="fw-bold text-primary">
                                {{ $b->formatted_price }}
                            </td>
                            <td class="text-center">
                                <span class="badge {{ $b->stock > 5 ? 'bg-success-subtle text-success' : ($b->stock > 0 ? 'bg-warning-subtle text-warning' : 'bg-danger-subtle text-danger') }} px-2 py-1 rounded-pill">
                                    {{ $b->stock }} Eks
                                </span>
                            </td>
                            <td class="text-center">
                                <div class="d-flex justify-content-center gap-1">
                                    <a href="{{ route('books.show', $b) }}" class="btn btn-outline-info btn-sm" target="_blank" title="Lihat di Web">
                                        <i class="bi bi-box-arrow-up-right"></i>
                                    </a>
                                    <a href="{{ route('admin.books.edit', $b) }}" class="btn btn-outline-warning btn-sm" title="Ubah Data Buku">
                                        <i class="bi bi-pencil"></i>
                                    </a>
                                    @if ($b->order_items_count > 0)
                                        <button type="button" class="btn btn-outline-secondary btn-sm" disabled title="Tidak dapat dihapus: Buku ini memiliki relasi dengan {{ $b->order_items_count }} pesanan pengguna">
                                            <i class="bi bi-lock-fill text-muted"></i>
                                        </button>
                                    @else
                                        <form method="POST" action="{{ route('admin.books.destroy', $b) }}" onsubmit="return confirm('Hapus buku {{ addslashes($b->title) }}?');" class="d-inline">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-outline-danger btn-sm" title="Hapus Buku">
                                                <i class="bi bi-trash"></i>
                                            </button>
                                        </form>
                                    @endif
                                </div>
                            </td>
                        </tr>
                    @endforeach
                @endif
            </tbody>
        </table>
    </div>
</div>

<div class="d-flex justify-content-center mb-5">
    {{ $books->links('pagination::bootstrap-5') }}
</div>
@endsection
