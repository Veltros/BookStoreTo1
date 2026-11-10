@extends('layouts.app')

@section('title', 'The Book Atelier - Dunia Cerita, Dikurasi Khusus Untuk Anda')

@section('content')
<!-- HERO SECTION (EDITORIAL LAYOUT) -->
<section class="atelier-hero">
    <div class="container">
        <div class="row align-items-center g-5">
            <div class="col-lg-7">
                <span class="atelier-hero-eyebrow">
                    <i class="bi bi-bookmark-star-fill"></i> KURATOR BUKU PILIHAN &bull; SEJAK 2024
                </span>
                <h1 class="atelier-hero-title">
                    Temukan Buku Favorit Anda Berikutnya
                </h1>
                <p class="atelier-hero-lead">
                    Selamat datang di The Book Atelier. Kami menghadirkan koleksi buku pilihan dari berbagai genre — mulai dari karya sastra dunia, non-fiksi inspiratif, hingga pemikiran kontemporer yang dikurasi khusus untuk memperluas cakrawala berpikir Anda.
                </p>
                <div class="d-flex flex-wrap align-items-center gap-3 mb-4">
                    <a href="{{ route('books.index') }}" class="btn-atelier-primary">
                        <span>Jelajahi Koleksi</span>
                        <i class="bi bi-arrow-right"></i>
                    </a>
                    <a href="{{ route('books.index', ['sort' => 'popular']) }}" class="btn-atelier-outline">
                        <span>Lihat Buku Terlaris</span>
                    </a>
                </div>

                <div class="pt-3 border-top d-flex flex-wrap gap-4 text-muted small">
                    <div class="d-flex align-items-center gap-2">
                        <i class="bi bi-patch-check-fill text-maroon fs-5"></i>
                        <span class="text-dark fw-medium">Kurasi Buku Pilihan</span>
                    </div>
                    <div class="d-flex align-items-center gap-2">
                        <i class="bi bi-truck text-maroon fs-5"></i>
                        <span class="text-dark fw-medium">Pengiriman Terpercaya</span>
                    </div>
                    <div class="d-flex align-items-center gap-2">
                        <i class="bi bi-chat-heart text-maroon fs-5"></i>
                        <span class="text-dark fw-medium">Layanan Concierge Personal</span>
                    </div>
                </div>
            </div>

            <div class="col-lg-5 text-center">
                <div class="hero-book-card-wrap">
                    <img src="{{ asset('assets/uploads/book_bumi.jpg') }}" alt="Buku Unggulan Kurator" class="hero-book-img" onerror="this.src='{{ asset('assets/uploads/default_book.png') }}'">
                    
                    <div class="mt-3 p-3 bg-white border rounded shadow-sm text-start" style="font-size: 0.85rem; border-left: 3px solid #800020 !important;">
                        <p class="mb-1 text-dark fst-italic">"Sebuah buku klasik adalah buku yang tidak pernah selesai mengatakan apa yang ingin dikatakannya."</p>
                        <span class="text-muted small fw-semibold">&mdash; Italo Calvino</span>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- KATEGORI PILIHAN (FEATURED CATEGORIES) -->
<section class="py-5 my-2" id="kategori-buku">
    <div class="container">
        <div class="atelier-section-header">
            <span class="atelier-section-eyebrow">JELAJAHI BERDASARKAN GENRE</span>
            <h2 class="atelier-section-title">Kategori Pilihan</h2>
            <p class="atelier-section-subtitle">
                Temukan kisah dan gagasan yang sesuai dengan minat Anda, mulai dari fiksi berbobot hingga sains dan teknologi terdepan.
            </p>
        </div>

        <div class="row row-cols-1 row-cols-sm-2 row-cols-lg-3 g-4">
            @foreach ($featuredGenres as $genre)
                <div class="col">
                    <a href="{{ route('books.index', ['category' => $genre['filter_id']]) }}" class="atelier-cat-card">
                        <div class="atelier-cat-icon">
                            <i class="bi {{ $genre['icon'] }}"></i>
                        </div>
                        <h4 class="atelier-cat-name">{{ $genre['name'] }}</h4>
                        <p class="atelier-cat-desc">{{ $genre['desc'] }}</p>
                        <span class="atelier-cat-link">
                            Jelajahi Genre <i class="bi bi-arrow-right"></i>
                        </span>
                    </a>
                </div>
            @endforeach
        </div>
    </div>
</section>

<!-- PILIHAN KURATOR -->
<section class="py-5 bg-light border-top border-bottom" id="pilihan-kurator">
    <div class="container">
        <div class="d-flex flex-wrap align-items-end justify-content-between gap-3 mb-4">
            <div>
                <span class="atelier-section-eyebrow text-start">REKOMENDASI KHUSUS</span>
                <h2 class="atelier-section-title text-start mb-1">Pilihan Kurator</h2>
                <p class="text-muted small mb-0">Karya sastra dan buku pilihan editor dengan narasi luar biasa serta gagasan berharga.</p>
            </div>
            <a href="{{ route('books.index') }}" class="btn btn-outline-dark btn-sm rounded-1 px-3 py-2 fw-semibold">
                Lihat Semua Koleksi &rarr;
            </a>
        </div>

        <div class="row row-cols-1 row-cols-sm-2 row-cols-lg-4 g-4">
            @foreach ($editorPicks as $b)
                <div class="col">
                    <div class="atelier-book-card">
                        <div class="atelier-book-cover-wrap">
                            <a href="{{ route('books.show', $b) }}" class="d-block w-100 h-100">
                                <img src="{{ $b->cover_url }}" alt="{{ $b->title }}" class="atelier-book-cover" loading="lazy" style="{{ $b->stock <= 0 ? 'filter: grayscale(60%); opacity: 0.85;' : '' }}">
                            </a>
                            <span class="badge bg-maroon position-absolute top-0 start-0 m-2 px-2 py-1" style="font-size: 0.65rem; letter-spacing: 0.05em; z-index: 2;">
                                PILIHAN KURATOR
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
    </div>
</section>

<!-- BUKU TERBARU -->
<section class="py-5" id="buku-terbaru">
    <div class="container">
        <div class="d-flex flex-wrap align-items-end justify-content-between gap-3 mb-4">
            <div>
                <span class="atelier-section-eyebrow text-start">BARU SAJA TIBA</span>
                <h2 class="atelier-section-title text-start mb-1">Buku Terbaru</h2>
                <p class="text-muted small mb-0">Rilisan buku terkini yang baru saja hadir di rak perpustakaan kami.</p>
            </div>
            <a href="{{ route('books.index', ['sort' => 'newest']) }}" class="btn btn-outline-dark btn-sm rounded-1 px-3 py-2 fw-semibold">
                Lihat Semua yang Baru &rarr;
            </a>
        </div>

        <div class="row row-cols-1 row-cols-sm-2 row-cols-lg-4 g-4">
            @foreach ($newArrivals as $b)
                <div class="col">
                    <div class="atelier-book-card">
                        <div class="atelier-book-cover-wrap">
                            <a href="{{ route('books.show', $b) }}" class="d-block w-100 h-100">
                                <img src="{{ $b->cover_url }}" alt="{{ $b->title }}" class="atelier-book-cover" loading="lazy" style="{{ $b->stock <= 0 ? 'filter: grayscale(60%); opacity: 0.85;' : '' }}">
                            </a>
                            <span class="badge bg-dark position-absolute top-0 start-0 m-2 px-2 py-1" style="font-size: 0.65rem; letter-spacing: 0.05em; z-index: 2;">
                                TERBARU
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
    </div>
</section>

<!-- BUKU TERLARIS -->
<section class="py-5 bg-light border-top" id="buku-terlaris">
    <div class="container">
        <div class="d-flex flex-wrap align-items-end justify-content-between gap-3 mb-4">
            <div>
                <span class="atelier-section-eyebrow text-start">FAVORIT PEMBACA</span>
                <h2 class="atelier-section-title text-start mb-1">Buku Terlaris</h2>
                <p class="text-muted small mb-0">Buku-buku paling diminati dan paling banyak dibaca oleh komunitas pembaca kami.</p>
            </div>
            <a href="{{ route('books.index', ['sort' => 'popular']) }}" class="btn btn-outline-dark btn-sm rounded-1 px-3 py-2 fw-semibold">
                Lihat Terlaris &rarr;
            </a>
        </div>

        <div class="row row-cols-1 row-cols-sm-2 row-cols-lg-4 g-4">
            @foreach ($bestSellers as $idx => $b)
                <div class="col">
                    <div class="atelier-book-card">
                        <div class="atelier-book-cover-wrap">
                            <a href="{{ route('books.show', $b) }}" class="d-block w-100 h-100">
                                <img src="{{ $b->cover_url }}" alt="{{ $b->title }}" class="atelier-book-cover" loading="lazy" style="{{ $b->stock <= 0 ? 'filter: grayscale(60%); opacity: 0.85;' : '' }}">
                            </a>
                            <span class="badge bg-warning text-dark position-absolute top-0 start-0 m-2 px-2 py-1 fw-bold" style="font-size: 0.65rem; letter-spacing: 0.05em; z-index: 2;">
                                #{{ $idx + 1 }} TERLARIS
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
                            <span class="text-muted ms-1 small fw-semibold">4.9 / 5.0</span>
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
    </div>
</section>

<!-- NEWSLETTER / INVITE SECTION -->
<section class="py-5">
    <div class="container">
        <div class="atelier-newsletter-wrap">
            <h2 class="atelier-newsletter-title">Bergabunglah dengan Lingkar Kurator Kami</h2>
            <p class="atelier-newsletter-desc">
                Dapatkan rekomendasi mingguan buku-buku berbobot, intisari pemikiran tokoh dunia, serta penawaran eksklusif langsung di kotak masuk Anda.
            </p>
            <form class="d-flex justify-content-center gap-2 flex-wrap" style="max-width: 520px; margin: 0 auto;" onsubmit="event.preventDefault(); alert('Terima kasih! Anda telah terdaftar dalam buletin literatur The Book Atelier.');">
                <input type="email" class="form-control" placeholder="Masukkan alamat surel Anda..." required style="max-width: 340px; padding: 0.75rem 1.25rem;">
                <button type="submit" class="btn-atelier-primary border-0">Langganan</button>
            </form>
        </div>
    </div>
</section>
@endsection
