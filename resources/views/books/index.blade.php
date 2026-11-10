@extends('layouts.app')

@section('title', 'Katalog Koleksi Buku Pilihan - The Book Atelier')

@section('content')
<div class="container py-4">
    <!-- Breadcrumb -->
    <nav aria-label="breadcrumb" class="mb-4">
        <ol class="breadcrumb small">
            <li class="breadcrumb-item"><a href="{{ route('home') }}" class="text-muted">Beranda</a></li>
            <li class="breadcrumb-item active text-dark fw-semibold" aria-current="page">Katalog Buku</li>
        </ol>
    </nav>

    <div class="row g-4 mb-5">
        <!-- Sidebar Filter -->
        <div class="col-lg-3">
            <div class="p-3 bg-white border rounded shadow-sm">
                <div class="d-flex align-items-center justify-content-between pb-3 mb-3 border-bottom">
                    <span class="fw-bold text-dark fs-6"><i class="bi bi-sliders2 me-2 text-maroon"></i>Filter Koleksi</span>
                    @if ($hasActiveFilter)
                        <a href="{{ route('books.index') }}" class="small text-maroon fw-semibold">Reset Filter</a>
                    @endif
                </div>

                <!-- Filter Kategori -->
                <div class="mb-4">
                    <span class="small text-uppercase fw-bold text-muted d-block mb-2" style="font-size: 0.75rem; letter-spacing: 0.08em;">Kategori Buku</span>
                    <div class="d-flex flex-column gap-1">
                        <a href="{{ route('books.index', array_merge(request()->except('category', 'page'))) }}" 
                           class="d-flex align-items-center justify-content-between p-2 rounded {{ empty($categoryId) ? 'bg-light text-maroon fw-bold' : 'text-dark' }}">
                            <span>Semua Kategori</span>
                            <span class="badge bg-light text-dark rounded-pill border">{{ $categories->sum('books_count') }}</span>
                        </a>
                        @foreach ($categories as $cat)
                            <a href="{{ route('books.index', array_merge(request()->except('page'), ['category' => $cat->id])) }}" 
                               class="d-flex align-items-center justify-content-between p-2 rounded {{ $categoryId == $cat->id ? 'bg-light text-maroon fw-bold' : 'text-dark' }}">
                                <span>{{ $cat->name }}</span>
                                <span class="badge bg-light text-muted rounded-pill border">{{ $cat->books_count }}</span>
                            </a>
                        @endforeach
                    </div>
                </div>

                <!-- Filter Rentang Harga -->
                <div class="mb-4 pt-3 border-top">
                    <span class="small text-uppercase fw-bold text-muted d-block mb-2" style="font-size: 0.75rem; letter-spacing: 0.08em;">Rentang Harga</span>
                    <div class="d-flex flex-column gap-1">
                        <a href="{{ route('books.index', array_merge(request()->except('price', 'page'))) }}" 
                           class="p-2 rounded {{ empty($priceRange) ? 'bg-light text-maroon fw-bold' : 'text-dark' }}">
                            Semua Harga
                        </a>
                        <a href="{{ route('books.index', array_merge(request()->except('page'), ['price' => 'under100'])) }}" 
                           class="p-2 rounded {{ $priceRange === 'under100' ? 'bg-light text-maroon fw-bold' : 'text-dark' }}">
                            Di bawah Rp 100.000
                        </a>
                        <a href="{{ route('books.index', array_merge(request()->except('page'), ['price' => '100to150'])) }}" 
                           class="p-2 rounded {{ $priceRange === '100to150' ? 'bg-light text-maroon fw-bold' : 'text-dark' }}">
                            Rp 100.000 &ndash; Rp 150.000
                        </a>
                        <a href="{{ route('books.index', array_merge(request()->except('page'), ['price' => 'above150'])) }}" 
                           class="p-2 rounded {{ $priceRange === 'above150' ? 'bg-light text-maroon fw-bold' : 'text-dark' }}">
                            Di atas Rp 150.000
                        </a>
                    </div>
                </div>

                <!-- Filter Penulis -->
                @if ($authors->isNotEmpty())
                    <div class="mb-4 pt-3 border-top">
                        <span class="small text-uppercase fw-bold text-muted d-block mb-2" style="font-size: 0.75rem; letter-spacing: 0.08em;">Penulis</span>
                        <div class="d-flex flex-column gap-1" style="max-height: 180px; overflow-y: auto;">
                            <a href="{{ route('books.index', array_merge(request()->except('author', 'page'))) }}" 
                               class="p-2 rounded {{ empty($selectedAuthor) ? 'bg-light text-maroon fw-bold' : 'text-dark' }}">
                                Semua Penulis
                            </a>
                            @foreach ($authors as $aut)
                                <a href="{{ route('books.index', array_merge(request()->except('page'), ['author' => $aut])) }}" 
                                   class="p-2 rounded text-truncate {{ $selectedAuthor === $aut ? 'bg-light text-maroon fw-bold' : 'text-dark' }}" title="{{ $aut }}">
                                    {{ $aut }}
                                </a>
                            @endforeach
                        </div>
                    </div>
                @endif
            </div>
        </div>

        <!-- Kolom Katalog Buku -->
        <div class="col-lg-9">
            <div class="d-flex flex-wrap align-items-center justify-content-between gap-3 mb-4 pb-2 border-bottom">
                <div>
                    <h1 class="h3 fw-bold font-serif mb-1">
                        @if ($categoryId)
                            Kategori: {{ optional($categories->firstWhere('id', $categoryId))->name }}
                        @elseif (!empty($search))
                            Hasil Pencarian: "{{ $search }}"
                        @else
                            Seluruh Koleksi Buku
                        @endif
                    </h1>
                    <span class="text-muted small">Menampilkan {{ $books->total() }} judul buku</span>
                </div>

                <!-- Form Sortir -->
                <form method="GET" action="{{ route('books.index') }}" class="d-flex align-items-center gap-2">
                    @if ($categoryId) <input type="hidden" name="category" value="{{ $categoryId }}"> @endif
                    @if (!empty($search)) <input type="hidden" name="q" value="{{ $search }}"> @endif
                    @if (!empty($priceRange)) <input type="hidden" name="price" value="{{ $priceRange }}"> @endif
                    @if (!empty($selectedAuthor)) <input type="hidden" name="author" value="{{ $selectedAuthor }}"> @endif
                    
                    <label for="sort" class="small text-muted text-nowrap fw-medium">Urutkan:</label>
                    <select name="sort" id="sort" class="form-select form-select-sm" onchange="this.form.submit()">
                        <option value="newest" {{ $sort === 'newest' ? 'selected' : '' }}>Terbaru</option>
                        <option value="popular" {{ $sort === 'popular' ? 'selected' : '' }}>Terlaris</option>
                        <option value="price_asc" {{ $sort === 'price_asc' ? 'selected' : '' }}>Harga: Rendah ke Tinggi</option>
                        <option value="price_desc" {{ $sort === 'price_desc' ? 'selected' : '' }}>Harga: Tinggi ke Rendah</option>
                        <option value="title_asc" {{ $sort === 'title_asc' ? 'selected' : '' }}>Judul (A - Z)</option>
                    </select>
                </form>
            </div>

            <!-- Grid Buku -->
            @if ($books->isEmpty())
                <div class="p-5 text-center bg-white border rounded shadow-sm my-4">
                    <i class="bi bi-journal-x text-muted" style="font-size: 3.5rem;"></i>
                    <h4 class="font-serif fw-bold mt-3">Tidak Ada Buku Ditemukan</h4>
                    <p class="text-muted small">Coba ubah kata kunci pencarian atau reset filter yang dipilih.</p>
                    <a href="{{ route('books.index') }}" class="btn btn-atelier-primary btn-sm px-4 py-2 mt-2">
                        Reset Filter Pencarian
                    </a>
                </div>
            @else
                <div class="row row-cols-1 row-cols-sm-2 row-cols-md-3 g-4 mb-4">
                    @foreach ($books as $b)
                        <div class="col">
                            <div class="atelier-book-card">
                                <div class="atelier-book-cover-wrap position-relative">
                                    <a href="{{ route('books.show', $b) }}" class="d-block w-100 h-100">
                                        <img src="{{ $b->cover_url }}" alt="{{ $b->title }}" class="atelier-book-cover" loading="lazy" style="{{ $b->stock <= 0 ? 'filter: grayscale(60%); opacity: 0.85;' : '' }}">
                                    </a>
                                    <span class="badge bg-dark bg-opacity-75 text-white position-absolute top-0 start-0 m-2 px-2 py-1" style="font-size: 0.65rem; z-index: 2;">
                                        {{ $b->category->name }}
                                    </span>
                                    @if ($b->stock <= 0)
                                        <div class="position-absolute bottom-0 start-0 w-100 bg-danger text-white text-center py-1 small fw-bold" style="font-size: 0.72rem; letter-spacing: 0.04em; z-index: 2;">
                                            <i class="bi bi-x-circle me-1"></i> STOK HABIS
                                        </div>
                                    @endif
                                </div>

                                <span class="atelier-book-genre">{{ $b->category->name }}</span>
                                <a href="{{ route('books.show', $b) }}" class="atelier-book-title text-dark" title="{{ $b->title }}">
                                    {{ $b->title }}
                                </a>
                                <div class="atelier-book-author">Karya: {{ $b->author }}</div>

                                <!-- Rating Bintang -->
                                <div class="d-flex align-items-center gap-1 text-warning mb-2" style="font-size: 0.8rem;">
                                    <i class="bi bi-star-fill"></i>
                                    <i class="bi bi-star-fill"></i>
                                    <i class="bi bi-star-fill"></i>
                                    <i class="bi bi-star-fill"></i>
                                    <i class="bi bi-star-half"></i>
                                    <span class="text-muted ms-1 small fw-semibold">4.9</span>
                                </div>

                                <div class="d-flex align-items-center justify-content-between mb-3">
                                    <div class="atelier-book-price mb-0">{{ $b->formatted_price }}</div>
                                    <span class="badge {{ $b->stock > 5 ? 'bg-success-subtle text-success' : ($b->stock > 0 ? 'bg-warning-subtle text-dark' : 'bg-danger text-white') }} rounded-1" style="font-size: 0.72rem;">
                                        {{ $b->stock > 0 ? 'Sisa: ' . $b->stock : 'Stok Habis' }}
                                    </span>
                                </div>

                                <div class="mt-auto d-flex gap-2">
                                    <a href="{{ route('books.show', $b) }}" class="btn btn-outline-dark btn-sm flex-grow-1 rounded-1 fw-semibold py-2">
                                        Detail
                                    </a>
                                    @if ($b->stock <= 0)
                                        <button type="button" class="btn btn-secondary btn-sm rounded-1 px-3 py-2 text-nowrap opacity-75" disabled title="Stok buku ini sedang habis" style="cursor: not-allowed;">
                                            <i class="bi bi-slash-circle me-1"></i> Habis
                                        </button>
                                    @else
                                        <form method="POST" action="{{ route('cart.add') }}" class="m-0">
                                            @csrf
                                            <input type="hidden" name="book_id" value="{{ $b->id }}">
                                            <input type="hidden" name="quantity" value="1">
                                            <button type="submit" class="btn btn-dark btn-sm rounded-1 px-3 py-2" title="Tambah ke Keranjang">
                                                <i class="bi bi-bag-plus"></i>
                                            </button>
                                        </form>
                                    @endif
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>

                <!-- Pagination -->
                <div class="d-flex justify-content-center mt-5">
                    {{ $books->links('pagination::bootstrap-5') }}
                </div>
            @endif
        </div>
    </div>
</div>
@endsection
