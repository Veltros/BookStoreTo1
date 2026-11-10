@extends('layouts.app')

@section('title', 'Layanan Chat Concierge - The Book Atelier')

@section('content')
<div class="container py-4">
    <!-- Breadcrumb -->
    <nav aria-label="breadcrumb" class="mb-4">
        <ol class="breadcrumb small">
            <li class="breadcrumb-item"><a href="{{ route('home') }}" class="text-muted">Beranda</a></li>
            <li class="breadcrumb-item active text-dark fw-semibold" aria-current="page">Chat Concierge</li>
        </ol>
    </nav>

    <div class="row g-4 mb-5">
        <!-- Kolom Kiri: Layanan Chat Bubble Interaktif (Lebar 8 Kolom) -->
        <div class="col-lg-8">
            <div class="chat-container">
                <!-- Header Chat Room -->
                <div class="chat-header d-flex flex-wrap align-items-center justify-content-between gap-3">
                    <div class="d-flex align-items-center gap-3">
                        <div class="position-relative">
                            <div class="bg-maroon text-white rounded-circle d-flex align-items-center justify-content-center shadow-sm" style="width: 48px; height: 48px;">
                                <i class="bi bi-headset fs-4"></i>
                            </div>
                            <span class="position-absolute bottom-0 end-0 p-1 bg-success border border-white rounded-circle" title="Admin Siap Membantu" style="width: 14px; height: 14px;"></span>
                        </div>
                        <div>
                            <div class="d-flex align-items-center gap-2">
                                <h5 class="fw-bold mb-0 text-dark font-serif">The Book Atelier Concierge</h5>
                                <span class="badge bg-maroon text-white fw-semibold small" style="font-size: 0.7rem;">
                                    <i class="bi bi-patch-check-fill me-1"></i>Official Atelier Staff
                                </span>
                            </div>
                            <div class="small text-muted d-flex align-items-center gap-2 mt-1">
                                <span class="chat-pulse-dot"></span>
                                <span>Admin Online &bull; Balasan Langsung</span>
                                <span class="text-secondary">&bull;</span>
                                <span class="text-secondary">08.00 &ndash; 17.00 WIB</span>
                            </div>
                        </div>
                    </div>

                    <div class="d-flex align-items-center gap-2">
                        <span class="badge bg-light text-dark border px-3 py-2 rounded-pill small">
                            <i class="bi bi-chat-left-dots text-maroon me-1"></i>
                            <strong>{{ $totalMessages }}</strong> Pesan
                        </span>
                        <a href="{{ route('contact.index') }}" class="btn btn-outline-secondary btn-sm rounded-circle p-2" title="Segarkan Obrolan" style="width: 36px; height: 36px; display: inline-flex; align-items: center; justify-content: center;">
                            <i class="bi bi-arrow-clockwise fs-6"></i>
                        </a>
                    </div>
                </div>

                <!-- Body Stream Chat (Tempat Percakapan Dua Arah) -->
                <div class="chat-body-stream" id="chatBodyStream">
                    @if ($userMessages->isEmpty())
                        <!-- Tampilan Kosong (Belum ada obrolan) -->
                        <div class="my-auto text-center py-5 px-3">
                            <div class="bg-white rounded-circle shadow-sm mx-auto d-flex align-items-center justify-content-center mb-3" style="width: 80px; height: 80px;">
                                <i class="bi bi-chat-heart text-maroon fs-1"></i>
                            </div>
                            <h5 class="fw-bold text-dark mb-2 font-serif">Halo, {{ auth()->check() ? auth()->user()->name : 'Sobat Pembaca' }}! 👋</h5>
                            <p class="text-muted small mx-auto mb-4" style="max-width: 480px;">
                                Selamat datang di layanan <strong>Concierge Resmi The Book Atelier</strong>. Tanyakan seputar ketersediaan stok buku, verifikasi status pembayaran, resi pengiriman, hingga rekomendasi bacaan favorit Anda.
                            </p>
                            <div class="p-3 bg-white border rounded-4 d-inline-block text-start shadow-sm" style="max-width: 420px;">
                                <div class="small fw-semibold text-maroon mb-2">
                                    <i class="bi bi-lightning-charge-fill me-1"></i> Mulai obrolan dengan topik cepat:
                                </div>
                                <div class="d-flex flex-wrap gap-2">
                                    <span class="badge bg-light text-secondary border p-2 rounded-pill pointer" style="cursor: pointer;" onclick="selectQuickTopic('Tanya Stok Buku', 'Halo Admin, saya ingin menanyakan ketersediaan stok untuk buku: ')">
                                        📦 Tanya Stok Buku
                                    </span>
                                    <span class="badge bg-light text-secondary border p-2 rounded-pill pointer" style="cursor: pointer;" onclick="selectQuickTopic('Konfirmasi Pembayaran', 'Halo Admin, saya ingin mengonfirmasi pembayaran untuk No. Pesanan: ')">
                                        💳 Konfirmasi Pembayaran
                                    </span>
                                    <span class="badge bg-light text-secondary border p-2 rounded-pill pointer" style="cursor: pointer;" onclick="selectQuickTopic('Cek Status Pengiriman', 'Halo Admin, bagaimana status pengiriman pesanan saya? No. Pesanan: ')">
                                        🚚 Cek Pengiriman
                                    </span>
                                </div>
                            </div>
                        </div>
                    @else
                        @php
                            $lastDate = '';
                        @endphp
                        @foreach ($userMessages as $msg)
                            @php
                                $msgDate = $msg->created_at->format('Y-m-d');
                                if ($msgDate !== $lastDate) {
                                    $displayDate = ($msgDate === date('Y-m-d')) ? 'Hari Ini' : (($msgDate === date('Y-m-d', strtotime('-1 day'))) ? 'Kemarin' : $msg->created_at->format('d M Y'));
                                    echo '<div class="chat-date-separator"><span>' . $displayDate . '</span></div>';
                                    $lastDate = $msgDate;
                                }
                            @endphp

                            <!-- Pesan dari Pengguna (Sisi Kanan - Hitam) -->
                            <div class="chat-msg-row user-msg">
                                <div class="chat-bubble-user">
                                    <div class="chat-topic-tag">
                                        <i class="bi bi-tag-fill me-1"></i>
                                        <span>{{ $msg->subject }}</span>
                                    </div>
                                    <div class="chat-text">
                                        {!! nl2br(e($msg->message)) !!}
                                    </div>
                                    <div class="chat-meta">
                                        <span>{{ $msg->created_at->format('H:i') }} WIB</span>
                                        @if (!empty($msg->admin_reply))
                                            <span title="Telah dibalas Admin" class="text-white"><i class="bi bi-check2-all text-info fw-bold"></i> Dibalas</span>
                                        @else
                                            <span title="Pesan terkirim & menunggu balasan" class="text-white-50"><i class="bi bi-check2"></i> Terkirim</span>
                                        @endif
                                    </div>
                                </div>
                            </div>

                            <!-- Balasan dari Admin (Sisi Kiri - Putih Aksen Marun) -->
                            @if (!empty($msg->admin_reply))
                                <div class="chat-msg-row admin-msg">
                                    <div class="chat-bubble-admin">
                                        <div class="chat-admin-header">
                                            <div class="d-flex align-items-center gap-2">
                                                <div class="bg-maroon text-white rounded-circle d-flex align-items-center justify-content-center" style="width: 26px; height: 26px; font-size: 0.75rem;">
                                                    <i class="bi bi-shield-check"></i>
                                                </div>
                                                <strong class="text-maroon small">{{ $msg->admin ? $msg->admin->name : 'Admin The Book Atelier' }}</strong>
                                                <span class="badge bg-danger-subtle text-danger border border-danger-subtle" style="font-size: 0.65rem;">Official Staff</span>
                                            </div>
                                            <small class="text-muted" style="font-size: 0.725rem;">
                                                <i class="bi bi-clock me-1"></i>{{ $msg->replied_at ? $msg->replied_at->format('d/m/Y H:i') : '' }} WIB
                                            </small>
                                        </div>
                                        <div class="chat-text">
                                            {!! nl2br(e($msg->admin_reply)) !!}
                                        </div>
                                        <div class="mt-2 pt-2 border-top border-light-subtle d-flex align-items-center justify-content-between small text-muted" style="font-size: 0.75rem;">
                                            <span><i class="bi bi-chat-quote me-1 text-maroon"></i>Balasan Resmi Toko</span>
                                            <span class="text-success"><i class="bi bi-check-circle-fill me-1"></i>Terselesaikan</span>
                                        </div>
                                    </div>
                                </div>
                            @else
                                <!-- Indikator Menunggu Tanggapan Admin -->
                                <div class="chat-msg-row admin-msg">
                                    <div class="chat-waiting-pill">
                                        <span class="spinner-grow spinner-grow-sm text-warning" role="status"></span>
                                        <span>Pesan sedang ditinjau Admin. Estimasi balasan: <strong>5 &ndash; 15 menit</strong>.</span>
                                    </div>
                                </div>
                            @endif
                        @endforeach
                    @endif
                </div>

                <!-- Bagian Input & Quick Topic Chips -->
                <div class="bg-white border-top p-3 p-md-4">
                    <!-- Baris Quick Topic Chips -->
                    <div class="mb-3">
                        <div class="small text-muted fw-semibold mb-2 d-flex align-items-center gap-1">
                            <i class="bi bi-lightning-charge text-warning"></i> Pilih Topik Cepat:
                        </div>
                        <div class="d-flex flex-wrap gap-2">
                            <button type="button" class="chat-quick-chip" onclick="selectQuickTopic('Tanya Stok Buku', 'Halo Admin, saya ingin menanyakan apakah stok buku ini masih tersedia: ')">
                                📦 Tanya Stok Buku
                            </button>
                            <button type="button" class="chat-quick-chip" onclick="selectQuickTopic('Konfirmasi Pembayaran', 'Halo Admin, saya sudah melakukan pembayaran untuk No. Pesanan: \nMohon bantuannya untuk verifikasi ya.')">
                                💳 Konfirmasi Pembayaran
                            </button>
                            <button type="button" class="chat-quick-chip" onclick="selectQuickTopic('Cek Status Pengiriman', 'Halo Admin, saya ingin menanyakan status pengiriman dan nomor resi untuk No. Pesanan: ')">
                                🚚 Cek Resi &amp; Pengiriman
                            </button>
                            <button type="button" class="chat-quick-chip" onclick="selectQuickTopic('Rekomendasi Buku', 'Halo Admin, bisakah minta rekomendasi buku seputar: ')">
                                📚 Rekomendasi Buku
                            </button>
                            <button type="button" class="chat-quick-chip" onclick="selectQuickTopic('Bantuan Pesanan & Akun', 'Halo Admin, saya mengalami kendala pada: ')">
                                ⚙️ Bantuan Akun &amp; Pesanan
                            </button>
                        </div>
                    </div>

                    @if ($errors->any())
                        <div class="alert alert-danger py-2 px-3 mb-3 small rounded-3" role="alert">
                            <ul class="mb-0 ps-3">
                                @foreach ($errors->all() as $err)
                                    <li>{{ $err }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    <!-- Formulir Pengiriman Pesan Baru -->
                    <form method="POST" action="{{ route('contact.store') }}" id="chatForm">
                        @csrf
                        <div class="row g-2 mb-2">
                            @guest
                                <div class="col-md-6 mb-2">
                                    <label class="form-label small fw-semibold text-secondary mb-1">Nama Lengkap <span class="text-danger">*</span></label>
                                    <input type="text" name="name" class="form-control form-control-sm" placeholder="Nama Anda" required>
                                </div>
                                <div class="col-md-6 mb-2">
                                    <label class="form-label small fw-semibold text-secondary mb-1">Alamat Email <span class="text-danger">*</span></label>
                                    <input type="email" name="email" class="form-control form-control-sm" placeholder="nama@email.com" required>
                                </div>
                            @else
                                <input type="hidden" name="name" value="{{ auth()->user()->name }}">
                                <input type="hidden" name="email" value="{{ auth()->user()->email }}">
                            @endguest

                            <div class="col-12">
                                <label for="chatSubject" class="form-label small fw-semibold text-secondary mb-1">
                                    Topik / Subjek Obrolan <span class="text-danger">*</span>
                                </label>
                                <div class="input-group">
                                    <span class="input-group-text bg-light border-end-0 text-muted">
                                        <i class="bi bi-chat-square-quote"></i>
                                    </span>
                                    <input type="text" class="form-control border-start-0" id="chatSubject" name="subject" value="{{ old('subject') }}" placeholder="Contoh: Tanya Ketersediaan Stok Buku Clean Code" required>
                                </div>
                            </div>
                        </div>

                        <div class="mb-3">
                            <label for="chatMessage" class="form-label small fw-semibold text-secondary mb-1">
                                Pesan Anda untuk Admin <span class="text-danger">*</span>
                            </label>
                            <textarea class="form-control" id="chatMessage" name="message" rows="3" placeholder="Tuliskan pertanyaan, permintaan, atau kendala Anda di sini..." required>{{ old('message') }}</textarea>
                        </div>

                        <div class="d-flex flex-wrap align-items-center justify-content-between gap-2">
                            @auth
                                <div class="small text-muted d-flex align-items-center gap-2">
                                    <i class="bi bi-shield-check text-success fs-6"></i>
                                    <span>Mengirim sebagai <strong>{{ auth()->user()->name }}</strong> ({{ auth()->user()->email }})</span>
                                </div>
                            @else
                                <div class="small text-muted">
                                    <i class="bi bi-info-circle me-1"></i>Anda dapat <a href="{{ route('login') }}" class="text-maroon fw-semibold">Masuk Akun</a> untuk menyimpan riwayat obrolan Anda.
                                </div>
                            @endauth

                            <button type="submit" class="btn btn-atelier-primary px-4 py-2 rounded-pill fw-semibold shadow-sm d-flex align-items-center gap-2">
                                <i class="bi bi-send-fill"></i>
                                <span>Kirim Pesan ke Admin</span>
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <!-- Kolom Kanan: Info Akun, Kontak Resmi & FAQ (Lebar 4 Kolom) -->
        <div class="col-lg-4">
            <!-- Kartu Identitas Akun Pengguna -->
            @auth
                <div class="card shadow-sm border-0 rounded-4 overflow-hidden mb-4 bg-white">
                    <div class="card-body p-4">
                        <div class="d-flex align-items-center gap-3 mb-3">
                            <div class="bg-primary-subtle text-primary rounded-circle d-flex align-items-center justify-content-center fw-bold fs-4" style="width: 52px; height: 52px;">
                                {{ strtoupper(substr(auth()->user()->name, 0, 1)) }}
                            </div>
                            <div>
                                <h6 class="fw-bold mb-0 text-dark">{{ auth()->user()->name }}</h6>
                                <small class="text-muted d-block">{{ auth()->user()->email }}</small>
                                <span class="badge bg-success-subtle text-success small mt-1">
                                    <i class="bi bi-check-circle me-1"></i>Pelanggan Terdaftar
                                </span>
                            </div>
                        </div>

                        <div class="row g-2 pt-3 border-top text-center">
                            <div class="col-6">
                                <div class="p-2 bg-light rounded-3">
                                    <div class="small text-muted">Total Chat</div>
                                    <div class="fw-bold fs-5 text-dark">{{ $totalMessages }}</div>
                                </div>
                            </div>
                            <div class="col-6">
                                <div class="p-2 bg-light rounded-3">
                                    <div class="small text-muted">Sudah Dibalas</div>
                                    <div class="fw-bold fs-5 text-success">{{ $repliedCount }}</div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                @if (auth()->user()->role === 'admin')
                    <!-- Shortcut khusus jika yang login adalah admin -->
                    <div class="card border-primary bg-primary bg-opacity-10 rounded-4 p-3 mb-4 shadow-sm">
                        <div class="d-flex align-items-start gap-2">
                            <i class="bi bi-shield-lock-fill text-primary fs-4"></i>
                            <div>
                                <strong class="text-primary d-block">Akses Administrator Terdeteksi</strong>
                                <p class="small text-secondary mb-2">Anda dapat melihat daftar seluruh pesan masuk dari pelanggan serta memberikan balasan resmi melalui panel admin.</p>
                                <a href="{{ route('admin.messages.index') }}" class="btn btn-primary btn-sm rounded-pill">
                                    <i class="bi bi-inbox-fill me-1"></i> Buka Kotak Masuk Pesan Admin
                                </a>
                            </div>
                        </div>
                    </div>
                @endif
            @endauth

            <!-- Kartu Layanan Pelanggan & Kontak Resmi -->
            <div class="card shadow-sm border-0 rounded-4 mb-4 bg-white">
                <div class="card-body p-4">
                    <h6 class="fw-bold text-dark mb-3 d-flex align-items-center gap-2">
                        <i class="bi bi-telephone-inbound text-maroon"></i> Kontak Resmi The Book Atelier
                    </h6>

                    <div class="d-flex flex-column gap-3 small text-secondary">
                        <div class="d-flex align-items-start gap-3">
                            <div class="bg-light p-2 rounded-3 text-primary">
                                <i class="bi bi-geo-alt fs-5"></i>
                            </div>
                            <div>
                                <div class="fw-semibold text-dark">Alamat Toko &amp; Gudang</div>
                                <div>Jl. MT. Haryono Kavling 52, Jakarta Selatan, DKI Jakarta 12770</div>
                            </div>
                        </div>

                        <div class="d-flex align-items-start gap-3">
                            <div class="bg-light p-2 rounded-3 text-success">
                                <i class="bi bi-whatsapp fs-5"></i>
                            </div>
                            <div>
                                <div class="fw-semibold text-dark">WhatsApp &amp; Hotline</div>
                                <div>(021) 7890-1234 / 0812-3456-7890</div>
                            </div>
                        </div>

                        <div class="d-flex align-items-start gap-3">
                            <div class="bg-light p-2 rounded-3 text-danger">
                                <i class="bi bi-envelope fs-5"></i>
                            </div>
                            <div>
                                <div class="fw-semibold text-dark">Email Bantuan</div>
                                <div>admin@bookstore.com</div>
                            </div>
                        </div>

                        <div class="d-flex align-items-start gap-3">
                            <div class="bg-light p-2 rounded-3 text-warning">
                                <i class="bi bi-clock-history fs-5"></i>
                            </div>
                            <div>
                                <div class="fw-semibold text-dark">Jam Operasional Layanan</div>
                                <div>Senin &ndash; Sabtu (08.00 &ndash; 17.00 WIB)<br><span class="text-muted">Minggu &amp; Hari Libur Nasional tutup</span></div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Pertanyaan yang Sering Ditanyakan (FAQ Accordion) -->
            <div class="card shadow-sm border-0 rounded-4 mb-4 bg-white">
                <div class="card-body p-4">
                    <h6 class="fw-bold text-dark mb-3 d-flex align-items-center gap-2">
                        <i class="bi bi-question-circle text-primary"></i> FAQ Bantuan Cepat
                    </h6>

                    <div class="accordion accordion-flush" id="faqAccordion">
                        <div class="accordion-item border-bottom">
                            <h2 class="accordion-header" id="faqHeading1">
                                <button class="accordion-button collapsed px-0 py-2 small fw-semibold" type="button" data-bs-toggle="collapse" data-bs-target="#faqCollapse1">
                                    Bagaimana cara konfirmasi pembayaran?
                                </button>
                            </h2>
                            <div id="faqCollapse1" class="accordion-collapse collapse" data-bs-parent="#faqAccordion">
                                <div class="accordion-body px-0 py-2 small text-muted">
                                    Pilih tombol topik <strong>Konfirmasi Pembayaran</strong> di atas, masukkan Nomor Pesanan Anda (contoh: <code>ORD-202611...</code>) dan nominal pada kolom chat. Admin akan segera memverifikasinya.
                                </div>
                            </div>
                        </div>

                        <div class="accordion-item border-bottom">
                            <h2 class="accordion-header" id="faqHeading2">
                                <button class="accordion-button collapsed px-0 py-2 small fw-semibold" type="button" data-bs-toggle="collapse" data-bs-target="#faqCollapse2">
                                    Berapa lama pesanan saya diproses?
                                </button>
                            </h2>
                            <div id="faqCollapse2" class="accordion-collapse collapse" data-bs-parent="#faqAccordion">
                                <div class="accordion-body px-0 py-2 small text-muted">
                                    Pesanan akan dikemas dalam waktu 1x24 jam kerja setelah pembayaran terkonfirmasi lunas. Estimasi kurir pengiriman reguler adalah 1&ndash;3 hari kerja.
                                </div>
                            </div>
                        </div>

                        <div class="accordion-item">
                            <h2 class="accordion-header" id="faqHeading3">
                                <button class="accordion-button collapsed px-0 py-2 small fw-semibold" type="button" data-bs-toggle="collapse" data-bs-target="#faqCollapse3">
                                    Bisa request judul buku yang belum ada?
                                </button>
                            </h2>
                            <div id="faqCollapse3" class="accordion-collapse collapse" data-bs-parent="#faqAccordion">
                                <div class="accordion-body px-0 py-2 small text-muted">
                                    Tentu saja! Anda dapat memilih topik <strong>Rekomendasi Buku</strong> dan menyebutkan judul serta penulis buku yang diinginkan. Tim kurator kami akan mengusahakan stoknya untuk Anda.
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
function selectQuickTopic(subject, templateMessage) {
    const subjectInput = document.getElementById('chatSubject');
    const messageInput = document.getElementById('chatMessage');
    
    if (subjectInput) {
        subjectInput.value = subject;
    }
    
    if (messageInput) {
        if (!messageInput.value.trim() || messageInput.value.startsWith('Halo Admin')) {
            messageInput.value = templateMessage;
        }
        messageInput.focus();
    }

    document.querySelectorAll('.chat-quick-chip').forEach(btn => {
        if (btn.innerText.includes(subject)) {
            btn.classList.add('active');
        } else {
            btn.classList.remove('active');
        }
    });
}

document.addEventListener('DOMContentLoaded', function () {
    const stream = document.getElementById('chatBodyStream');
    if (stream) {
        stream.scrollTop = stream.scrollHeight;
    }
});
</script>
@endsection
