@extends('layouts.admin')

@section('title', 'Kelola Kategori Buku')

@section('content')
<div class="d-flex align-items-center justify-content-between mb-4">
    <div>
        <h2 class="fw-bold mb-1"><i class="bi bi-tags text-primary me-2"></i>Kelola Kategori Buku</h2>
        <p class="text-muted small mb-0">Tambah, ubah, dan kelola kategori klasifikasi buku</p>
    </div>
</div>

<div class="row g-4 mb-5">
    <!-- Form Tambah / Edit Kategori -->
    <div class="col-lg-4">
        <div class="card shadow-sm border-0 rounded-4 bg-white">
            <div class="card-header bg-white py-3 px-4">
                <h5 class="fw-bold mb-0">
                    <i class="bi {{ $editCategory ? 'bi-pencil-square text-warning' : 'bi-plus-circle text-primary' }} me-2"></i>
                    {{ $editCategory ? 'Ubah Kategori' : 'Tambah Kategori Baru' }}
                </h5>
            </div>
            <div class="card-body p-4">
                @if ($errors->any())
                    <div class="alert alert-danger py-2 px-3 small rounded-3 mb-3">
                        <ul class="mb-0 ps-3">
                            @foreach ($errors->all() as $err)
                                <li>{{ $err }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <form method="POST" action="{{ $editCategory ? route('admin.categories.update', $editCategory) : route('admin.categories.store') }}">
                    @csrf
                    @if ($editCategory)
                        @method('PUT')
                    @endif

                    <div class="mb-3">
                        <label for="name" class="form-label small fw-bold text-dark">Nama Kategori <span class="text-danger">*</span></label>
                        <input type="text" class="form-control @error('name') is-invalid @enderror" id="name" name="name" value="{{ old('name', $editCategory ? $editCategory->name : '') }}" placeholder="Misal: Teknologi, Fiksi" required>
                        @error('name')
                            <div class="invalid-feedback fw-semibold">
                                {{ $message }}
                            </div>
                        @enderror
                    </div>

                    <div class="mb-4">
                        <label for="description" class="form-label small fw-bold text-dark">Deskripsi (Opsional)</label>
                        <textarea class="form-control" id="description" name="description" rows="3" placeholder="Deskripsi ringkas kategori...">{{ old('description', $editCategory ? $editCategory->description : '') }}</textarea>
                    </div>

                    <div class="d-flex gap-2">
                        <button type="submit" class="btn btn-primary flex-grow-1 rounded-3 fw-semibold">
                            <i class="bi bi-check-lg me-1"></i> {{ $editCategory ? 'Simpan Perubahan' : 'Tambahkan Kategori' }}
                        </button>
                        @if ($editCategory)
                            <a href="{{ route('admin.categories.index') }}" class="btn btn-outline-secondary rounded-3">
                                Batal
                            </a>
                        @endif
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Tabel Daftar Kategori -->
    <div class="col-lg-8">
        <div class="card shadow-sm border-0 rounded-4 overflow-hidden bg-white">
            <div class="card-header bg-white py-3 px-4 d-flex justify-content-between align-items-center">
                <h5 class="fw-bold mb-0">Daftar Kategori Buku ({{ $categories->count() }})</h5>
            </div>
            <div class="table-responsive">
                <table class="table align-middle mb-0">
                    <thead class="table-light small">
                        <tr>
                            <th style="width: 5%;">#</th>
                            <th style="width: 30%;">Nama Kategori</th>
                            <th style="width: 30%;">Deskripsi</th>
                            <th class="text-center" style="width: 20%;">Jumlah Buku &amp; Relasi</th>
                            <th class="text-center" style="width: 15%;">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @if ($categories->isEmpty())
                            <tr>
                                <td colspan="5" class="text-center py-4 text-muted">Belum ada kategori yang dibuat.</td>
                            </tr>
                        @else
                            @foreach ($categories as $index => $cat)
                                <tr>
                                    <td>{{ $index + 1 }}</td>
                                    <td class="fw-bold text-primary">{{ $cat->name }}</td>
                                    <td class="small text-muted">{{ $cat->description ?: '-' }}</td>
                                    <td class="text-center">
                                        <span class="badge bg-secondary-subtle text-secondary px-3 py-1 rounded-pill">
                                            {{ $cat->books_count }} Buku
                                        </span>
                                        @if ($cat->order_items_count > 0)
                                            <div class="mt-1">
                                                <span class="badge bg-primary-subtle text-primary border border-primary-subtle rounded-pill" style="font-size: 0.7rem;">
                                                    <i class="bi bi-bag-check-fill me-1"></i>{{ $cat->order_items_count }} Pesanan User
                                                </span>
                                            </div>
                                        @endif
                                    </td>
                                    <td class="text-center">
                                        <div class="d-flex justify-content-center gap-1">
                                            <a href="{{ route('admin.categories.index', ['edit' => $cat->id]) }}" class="btn btn-outline-warning btn-sm" title="Ubah Kategori">
                                                <i class="bi bi-pencil"></i>
                                            </a>
                                            @if ($cat->order_items_count > 0 || $cat->books_count > 0)
                                                <button type="button" class="btn btn-outline-secondary btn-sm" disabled 
                                                        title="Tidak dapat dihapus: {{ $cat->order_items_count > 0 ? 'Buku di kategori ini terkait pesanan user (' . $cat->order_items_count . ' pesanan)' : 'Kategori masih berisi ' . $cat->books_count . ' buku' }}">
                                                    <i class="bi bi-lock-fill text-muted"></i>
                                                </button>
                                            @else
                                                <form method="POST" action="{{ route('admin.categories.destroy', $cat) }}" onsubmit="return confirm('Hapus kategori {{ addslashes($cat->name) }}?');" class="d-inline">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="btn btn-outline-danger btn-sm" title="Hapus Kategori">
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
    </div>
</div>
@endsection
