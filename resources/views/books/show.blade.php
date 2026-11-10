@extends('layouts.app')

@section('title', $book->title . ' - The Book Atelier')

@section('content')
<div class="container py-4">
    <!-- Breadcrumb -->
    <nav aria-label="breadcrumb" class="mb-4">
        <ol class="breadcrumb small">
            <li class="breadcrumb-item"><a href="{{ route('home') }}" class="text-muted">Beranda</a></li>
            <li class="breadcrumb-item"><a href="{{ route('books.index') }}" class="text-muted">Katalog Buku</a></li>
            <li class="breadcrumb-item"><a href="{{ route('books.index', ['category' => $book->category_id]) }}" class="text-muted">{{ $book->category->name }}</a></li>
            <li class="breadcrumb-item active text-dark fw-semibold text-truncate" style="max-width: 250px;" aria-current="page">{{ $book->title }}</li>
        </ol>
    </nav>

    <div class="card border-0 shadow-sm rounded-4 p-4 p-lg-5 mb-5 bg-white">
        <div class="row g-5">
            <!-- Cover Gambar -->
            <div class="col-lg-4 text-center">
                <div class="position-relative p-3 border rounded-3 bg-light shadow-sm w-100" style="max-width: 360px; margin: 0 auto;">
                    <img src="{{ $book->cover_url }}" alt="{{ $book->title }}" class="img-fluid rounded shadow" style="max-height: 480px; max-width: 100%; object-fit: contain; {{ $book->stock <= 0 ? 'filter: grayscale(60%); opacity: 0.85;' : '' }}">
                    @if ($book->stock <= 0)
                        <span class="badge bg-danger position-absolute top-0 start-0 m-4 px-3 py-2 fs-6 shadow">
                            <i class="bi bi-x-circle me-1"></i> Stok Habis
                        </span>
                    @endif
                </div>
            </div>

            <!-- Informasi & Aksi Pembelian -->
            <div class="col-lg-8">
                <div class="d-flex align-items-center gap-2 mb-2">
                    <span class="badge bg-maroon px-3 py-2 text-uppercase" style="letter-spacing: 0.05em; font-size: 0.72rem;">
                        {{ $book->category->name }}
                    </span>
                    @if ($book->stock > 5)
                        <span class="badge bg-success-subtle text-success px-2 py-1"><i class="bi bi-check-circle me-1"></i>Stok Tersedia ({{ $book->stock }})</span>
                    @elseif ($book->stock > 0)
                        <span class="badge bg-warning-subtle text-warning px-2 py-1"><i class="bi bi-exclamation-circle me-1"></i>Sisa {{ $book->stock }} Eks</span>
                    @else
                        <span class="badge bg-danger text-white px-2 py-1"><i class="bi bi-x-circle me-1"></i>Stok Habis (0 eks)</span>
                    @endif
                </div>

                <h1 class="h2 fw-bold font-serif mb-2 text-dark">{{ $book->title }}</h1>
                <p class="text-muted fs-6 mb-3">
                    Ditulis oleh <strong class="text-dark">{{ $book->author }}</strong>
                    @if ($book->publisher)
                        &bull; Diterbitkan oleh <span class="text-dark">{{ $book->publisher }}</span> ({{ $book->year ?: '-' }})
                    @endif
                </p>

                <div class="p-3 bg-light rounded-3 my-3 d-inline-block">
                    <span class="text-muted small d-block mb-1">Harga Retail:</span>
                    <span class="fs-2 fw-bold text-maroon font-serif">{{ $book->formatted_price }}</span>
                </div>

                {{-- Formulir Tambah ke Keranjang --}}
                @if ($book->stock <= 0)
                    <div class="alert alert-danger d-flex align-items-start gap-3 p-3 rounded-3 my-4 border-start border-4 border-danger">
                        <i class="bi bi-x-circle-fill fs-3 text-danger flex-shrink-0 mt-1"></i>
                        <div>
                            <strong class="d-block text-danger">Persediaan Buku Ini Sedang Habis</strong>
                            <span class="small text-secondary">Mohon maaf, saat ini stok buku ini sudah habis terjual atau sedang dalam proses restock. Tombol pembelian dinonaktifkan sementara.</span>
                        </div>
                    </div>

                    <div class="d-flex flex-wrap align-items-center gap-3 my-4">
                        <button type="button" class="btn btn-secondary px-4 py-2 opacity-75" disabled style="cursor: not-allowed;">
                            <i class="bi bi-slash-circle me-2"></i> Stok Habis (Tidak Dapat Dipesan)
                        </button>
                        <a href="{{ route('books.index') }}" class="btn btn-outline-secondary px-4 py-2">
                            <i class="bi bi-arrow-left me-1"></i> Jelajahi Buku Lain
                        </a>
                    </div>
                @else
                    <form method="POST" action="{{ route('cart.add') }}" class="my-4">
                        @csrf
                        <input type="hidden" name="book_id" value="{{ $book->id }}">

                        <div class="d-flex flex-wrap align-items-center gap-3">
                            <div class="d-flex align-items-center border rounded-2 bg-white px-2 py-1" style="width: 140px;">
                                <button type="button" class="btn btn-sm text-muted qty-decrement border-0" onclick="let input = document.getElementById('qtyInput'); if(input.value > 1) input.value--;">-</button>
                                <input type="number" id="qtyInput" name="quantity" class="form-control form-control-sm text-center border-0 fw-bold" value="1" min="1" max="{{ $book->stock }}" style="box-shadow: none;">
                                <button type="button" class="btn btn-sm text-muted qty-increment border-0" onclick="let input = document.getElementById('qtyInput'); if(input.value < {{ $book->stock }}) input.value++;">+</button>
                            </div>

                            <button type="submit" class="btn btn-atelier-primary px-4 py-2">
                                <i class="bi bi-bag-plus me-2"></i> Tambah ke Keranjang
                            </button>
                        </div>
                    </form>
                @endif

                <!-- Sinopsis -->
                <div class="mt-4 pt-3 border-top">
                    <h5 class="fw-bold font-serif mb-2">Sinopsis / Ringkasan Buku</h5>
                    <p class="text-secondary lh-lg mb-4" style="text-align: justify;">
                        {{ $book->description ?: 'Belum ada deskripsi lengkap untuk buku ini.' }}
                    </p>
                </div>

                <!-- Spesifikasi Buku -->
                <div class="mt-3 pt-3 border-top">
                    <h6 class="fw-bold small text-uppercase text-muted mb-3" style="letter-spacing: 0.08em;">Detail Informasi Buku</h6>
                    <div class="row row-cols-2 row-cols-md-3 g-3 small">
                        <div>
                            <span class="text-muted d-block">Nomor ISBN:</span>
                            <span class="fw-semibold text-dark">{{ $book->isbn ?: '-' }}</span>
                        </div>
                        <div>
                            <span class="text-muted d-block">Penerbit:</span>
                            <span class="fw-semibold text-dark">{{ $book->publisher ?: '-' }}</span>
                        </div>
                        <div>
                            <span class="text-muted d-block">Tahun Terbit:</span>
                            <span class="fw-semibold text-dark">{{ $book->year ?: '-' }}</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Buku Terkait -->
    @if ($relatedBooks->isNotEmpty())
        <div class="my-5">
            <h3 class="h4 fw-bold font-serif mb-4">Rekomendasi Buku Terkait</h3>
            <div class="row row-cols-2 row-cols-md-4 g-4">
                @foreach ($relatedBooks as $rb)
                    <div class="col">
                        <div class="atelier-book-card h-100 d-flex flex-column">
                            <div class="atelier-book-cover-wrap position-relative">
                                <a href="{{ route('books.show', $rb) }}">
                                    <img src="{{ $rb->cover_url }}" alt="{{ $rb->title }}" class="atelier-book-cover" style="{{ $rb->stock <= 0 ? 'filter: grayscale(60%); opacity: 0.85;' : '' }}">
                                </a>
                                @if ($rb->stock <= 0)
                                    <div class="position-absolute bottom-0 start-0 w-100 bg-danger text-white text-center py-1 small fw-bold" style="font-size: 0.72rem; z-index: 2;">
                                        <i class="bi bi-x-circle me-1"></i> Stok Habis
                                    </div>
                                @endif
                            </div>
                            <div class="p-3 d-flex flex-column flex-grow-1">
                                <span class="small text-muted mb-1 text-truncate">{{ $rb->author }}</span>
                                <h4 class="atelier-book-title mb-2">
                                    <a href="{{ route('books.show', $rb) }}">{{ $rb->title }}</a>
                                </h4>
                                <div class="mt-auto d-flex align-items-center justify-content-between pt-2 border-top">
                                    <span class="atelier-book-price">{{ $rb->formatted_price }}</span>
                                    <a href="{{ route('books.show', $rb) }}" class="btn btn-sm btn-outline-dark rounded-1">Detail</a>
                                </div>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    @endif
</div>
@endsection
