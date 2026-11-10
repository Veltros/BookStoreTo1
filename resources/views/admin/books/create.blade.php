@extends('layouts.admin')

@section('title', 'Tambah Buku Baru')

@section('content')
<div class="d-flex align-items-center justify-content-between mb-4">
    <div>
        <h2 class="fw-bold mb-1"><i class="bi bi-plus-circle text-primary me-2"></i>Tambah Buku Baru</h2>
        <p class="text-muted small mb-0">Isi formulir untuk menambahkan koleksi buku baru ke katalog toko</p>
    </div>
    <a href="{{ route('admin.books.index') }}" class="btn btn-outline-secondary rounded-pill px-3">
        <i class="bi bi-arrow-left me-1"></i> Kembali ke Daftar
    </a>
</div>

<div class="card border-0 shadow-sm rounded-4 p-4 p-lg-5 bg-white mb-5">
    @if ($errors->any())
        <div class="alert alert-danger py-2 px-3 small rounded-3 mb-4">
            <ul class="mb-0 ps-3">
                @foreach ($errors->all() as $err)
                    <li>{{ $err }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form method="POST" action="{{ route('admin.books.store') }}" enctype="multipart/form-data">
        @csrf
        <div class="row g-4">
            <div class="col-md-8">
                <div class="mb-3">
                    <label for="title" class="form-label small fw-bold text-dark">Judul Buku <span class="text-danger">*</span></label>
                    <input type="text" class="form-control @error('title') is-invalid @enderror" id="title" name="title" value="{{ old('title') }}" placeholder="Judul lengkap buku..." required>
                    @error('title')
                        <div class="invalid-feedback fw-semibold">{{ $message }}</div>
                    @enderror
                </div>

                <div class="row g-3 mb-3">
                    <div class="col-md-6">
                        <label for="category_id" class="form-label small fw-bold text-dark">Kategori Klasifikasi <span class="text-danger">*</span></label>
                        <select name="category_id" id="category_id" class="form-select @error('category_id') is-invalid @enderror" required>
                            <option value="">Pilih Kategori...</option>
                            @foreach ($categories as $cat)
                                <option value="{{ $cat->id }}" {{ old('category_id') == $cat->id ? 'selected' : '' }}>
                                    {{ $cat->name }}
                                </option>
                            @endforeach
                        </select>
                        @error('category_id')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="col-md-6">
                        <label for="author" class="form-label small fw-bold text-dark">Penulis / Pengarang <span class="text-danger">*</span></label>
                        <input type="text" class="form-control @error('author') is-invalid @enderror" id="author" name="author" value="{{ old('author') }}" placeholder="Nama penulis..." required>
                        @error('author')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                <div class="row g-3 mb-3">
                    <div class="col-md-6">
                        <label for="publisher" class="form-label small fw-bold text-dark">Penerbit</label>
                        <input type="text" class="form-control" id="publisher" name="publisher" value="{{ old('publisher') }}" placeholder="Nama penerbit...">
                    </div>
                    <div class="col-md-3">
                        <label for="isbn" class="form-label small fw-bold text-dark">Nomor ISBN</label>
                        <input type="text" class="form-control @error('isbn') is-invalid @enderror" id="isbn" name="isbn" value="{{ old('isbn') }}" placeholder="978-...">
                        @error('isbn')
                            <div class="invalid-feedback fw-semibold">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="col-md-3">
                        <label for="year" class="form-label small fw-bold text-dark">Tahun Terbit</label>
                        <input type="number" class="form-control" id="year" name="year" value="{{ old('year', date('Y')) }}" min="1900" max="{{ date('Y') + 1 }}">
                    </div>
                </div>

                <div class="row g-3 mb-3">
                    <div class="col-md-6">
                        <label for="price" class="form-label small fw-bold text-dark">Harga Jual (Rp) <span class="text-danger">*</span></label>
                        <input type="number" class="form-control" id="price" name="price" value="{{ old('price') }}" min="0" placeholder="Contoh: 95000" required>
                    </div>
                    <div class="col-md-6">
                        <label for="stock" class="form-label small fw-bold text-dark">Jumlah Stok (Eks) <span class="text-danger">*</span></label>
                        <input type="number" class="form-control" id="stock" name="stock" value="{{ old('stock', 10) }}" min="0" required>
                    </div>
                </div>

                <div class="mb-3">
                    <label for="description" class="form-label small fw-bold text-dark">Sinopsis / Ringkasan Buku</label>
                    <textarea class="form-control" id="description" name="description" rows="5" placeholder="Tuliskan sinopsis buku...">{{ old('description') }}</textarea>
                </div>
            </div>

            <!-- Upload Cover Gambar -->
            <div class="col-md-4">
                <div class="card border rounded-3 p-3 bg-light text-center">
                    <label class="form-label small fw-bold text-dark mb-2">Gambar Sampul Buku</label>
                    <div class="mb-3">
                        <img id="coverPreview" src="{{ asset('assets/uploads/default_book.png') }}" alt="Preview Sampul" class="img-fluid rounded shadow-sm" style="max-height: 240px; object-fit: contain;">
                    </div>
                    <input type="file" class="form-control form-control-sm" id="cover_image" name="cover_image" accept="image/*" onchange="const [file] = this.files; if(file) { document.getElementById('coverPreview').src = URL.createObjectURL(file); }">
                    <small class="text-muted d-block mt-2" style="font-size: 0.72rem;">Format: JPG, PNG, WEBP. Maks: 2MB.</small>
                </div>

                <div class="d-grid gap-2 mt-4">
                    <button type="submit" class="btn btn-primary py-2 fw-semibold">
                        <i class="bi bi-save me-1"></i> Simpan Buku
                    </button>
                    <a href="{{ route('admin.books.index') }}" class="btn btn-outline-secondary">
                        Batal
                    </a>
                </div>
            </div>
        </div>
    </form>
</div>
@endsection
