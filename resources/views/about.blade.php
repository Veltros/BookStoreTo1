@extends('layouts.app')

@section('title', 'Tentang Kami - The Book Atelier')

@section('content')
<div class="container py-4">
    <!-- Breadcrumb -->
    <nav aria-label="breadcrumb" class="mb-4">
        <ol class="breadcrumb small">
            <li class="breadcrumb-item"><a href="{{ route('home') }}" class="text-muted">Beranda</a></li>
            <li class="breadcrumb-item active text-dark fw-semibold" aria-current="page">Tentang Kami</li>
        </ol>
    </nav>

    <!-- Header Section -->
    <div class="text-center max-w-700 mx-auto mb-5 py-4">
        <span class="atelier-section-eyebrow">FILOSOFI &amp; IDENTITAS KAMI</span>
        <h1 class="h2 fw-bold font-serif mb-3">Dunia Cerita, Dikurasi Khusus Untuk Anda</h1>
        <p class="text-secondary lead fs-6">
            The Book Atelier lahir dari kecintaan mendalam terhadap dunia literatur, pemikiran kritis, dan estetika buku cetak. Kami bukan sekadar toko buku, melainkan ruang kurasi bagi para pencari gagasan bermakna.
        </p>
    </div>

    <!-- 3 Pilar -->
    <div class="row g-4 mb-5">
        <div class="col-md-4">
            <div class="p-4 bg-white border rounded-4 shadow-sm h-100 text-center">
                <div class="rounded-circle bg-light d-inline-flex align-items-center justify-content-center mb-3" style="width: 60px; height: 60px;">
                    <i class="bi bi-patch-check-fill text-maroon fs-3"></i>
                </div>
                <h5 class="fw-bold font-serif mb-2">Kurasi Ketat</h5>
                <p class="text-muted small mb-0">Setiap judul buku yang hadir di rak kami dipilih langsung oleh kurator berpengalaman untuk memastikan bobot isi dan kualitas narasi.</p>
            </div>
        </div>
        <div class="col-md-4">
            <div class="p-4 bg-white border rounded-4 shadow-sm h-100 text-center">
                <div class="rounded-circle bg-light d-inline-flex align-items-center justify-content-center mb-3" style="width: 60px; height: 60px;">
                    <i class="bi bi-shield-check text-maroon fs-3"></i>
                </div>
                <h5 class="fw-bold font-serif mb-2">100% Asli &amp; Berkualitas</h5>
                <p class="text-muted small mb-0">Kami hanya mendistribusikan buku edisi resmi langsung dari penerbit terkemuka nasional maupun internasional.</p>
            </div>
        </div>
        <div class="col-md-4">
            <div class="p-4 bg-white border rounded-4 shadow-sm h-100 text-center">
                <div class="rounded-circle bg-light d-inline-flex align-items-center justify-content-center mb-3" style="width: 60px; height: 60px;">
                    <i class="bi bi-chat-heart text-maroon fs-3"></i>
                </div>
                <h5 class="fw-bold font-serif mb-2">Layanan Concierge</h5>
                <p class="text-muted small mb-0">Konsultasi rekomendasi buku personal kapan pun Anda membutuhkan arahan bacaan yang tepat untuk topik tertentu.</p>
            </div>
        </div>
    </div>

    <!-- Visi & Misi -->
    <div class="card border-0 shadow-sm rounded-4 p-4 p-md-5 bg-white mb-5">
        <div class="row g-5 align-items-center">
            <div class="col-lg-6">
                <h3 class="h4 fw-bold font-serif mb-3 text-dark">Visi &amp; Komitmen Kami</h3>
                <p class="text-secondary small lh-lg mb-3" style="text-align: justify;">
                    Menjadi destinasi literasi independen terdepan yang menginspirasi generasi pembaca baru, menyebarkan budaya literasi berbobot, serta menyediakan akses mudah terhadap karya-karya sastra dan ilmiah terbaik dunia.
                </p>
                <h5 class="fw-bold font-serif mb-2 text-dark">Misi:</h5>
                <ul class="text-secondary small lh-lg mb-0 ps-3">
                    <li>Menyediakan koleksi buku pilihan dengan kualitas cetak dan orisinalitas terjamin.</li>
                    <li>Membangun ekosistem belanja buku online yang aman, cepat, dan transparan.</li>
                    <li>Memberikan pelayanan personal dan rekomendasi bacaan yang relevan bagi setiap individu pembaca.</li>
                </ul>
            </div>
            <div class="col-lg-6">
                <div class="p-4 bg-light rounded-4 border">
                    <h5 class="fw-bold font-serif text-dark mb-3"><i class="bi bi-geo-alt-fill text-maroon me-2"></i>Kunjungi Toko Fisik Kami</h5>
                    <div class="small text-secondary d-flex flex-column gap-2">
                        <div>
                            <strong>Alamat:</strong><br>
                            The Book Atelier Flagship Store<br>
                            Jl. MT. Haryono Kavling 52, Jakarta Selatan, DKI Jakarta 12770
                        </div>
                        <div>
                            <strong>Jam Operasional:</strong><br>
                            Senin &ndash; Sabtu: 08.00 &ndash; 17.00 WIB (Minggu Libur)
                        </div>
                        <div>
                            <strong>Hotline &amp; Layanan Pelanggan:</strong><br>
                            (021) 7890-1234 &bull; WhatsApp: 0812-3456-7890
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
