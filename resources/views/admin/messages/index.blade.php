@extends('layouts.admin')

@section('title', 'Kelola Chat & Pesan Pengguna')

@section('content')
<!-- Header Halaman -->
<div class="d-flex flex-wrap align-items-center justify-content-between gap-3 mb-4">
    <div>
        <h2 class="fw-bold mb-1"><i class="bi bi-chat-left-dots-fill text-primary me-2"></i>Kelola Chat &amp; Pesan Pengguna</h2>
        <p class="text-muted small mb-0">Tinjau pertanyaan pelanggan seputar buku, konfirmasi pesanan, dan kirimkan balasan langsung ke antarmuka chat pengguna.</p>
    </div>
    <div class="d-flex align-items-center gap-2">
        <a href="{{ route('contact.index') }}" target="_blank" class="btn btn-outline-primary rounded-pill btn-sm px-3">
            <i class="bi bi-chat-heart me-1"></i> Preview Chat Pelanggan
        </a>
    </div>
</div>

<!-- Kartu Statistik Ringkas -->
<div class="row g-3 mb-4">
    <div class="col-6 col-md-3">
        <div class="card stat-card border-0 bg-white shadow-sm p-3 rounded-4 h-100">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <span class="small text-muted fw-semibold text-uppercase">Total Pesan</span>
                    <h3 class="fw-bold my-1 text-dark">{{ $totalAll }}</h3>
                    <a href="{{ route('admin.messages.index', ['filter' => 'all']) }}" class="small text-primary text-decoration-none">
                        Lihat Semua &rarr;
                    </a>
                </div>
                <div class="bg-primary bg-opacity-10 text-primary rounded-circle p-3">
                    <i class="bi bi-inbox fs-4"></i>
                </div>
            </div>
        </div>
    </div>

    <div class="col-6 col-md-3">
        <div class="card stat-card border-0 bg-white shadow-sm p-3 rounded-4 border-start border-warning border-4 h-100">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <span class="small text-muted fw-semibold text-uppercase">Menunggu Balasan</span>
                    <h3 class="fw-bold my-1 text-warning">{{ $totalPending }}</h3>
                    <a href="{{ route('admin.messages.index', ['filter' => 'pending']) }}" class="small text-warning text-decoration-none fw-semibold">
                        Prioritas Balas &rarr;
                    </a>
                </div>
                <div class="bg-warning bg-opacity-10 text-warning rounded-circle p-3">
                    <i class="bi bi-hourglass-split fs-4"></i>
                </div>
            </div>
        </div>
    </div>

    <div class="col-6 col-md-3">
        <div class="card stat-card border-0 bg-white shadow-sm p-3 rounded-4 border-start border-success border-4 h-100">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <span class="small text-muted fw-semibold text-uppercase">Sudah Dibalas</span>
                    <h3 class="fw-bold my-1 text-success">{{ $totalReplied }}</h3>
                    <a href="{{ route('admin.messages.index', ['filter' => 'replied']) }}" class="small text-success text-decoration-none">
                        Riwayat Balasan &rarr;
                    </a>
                </div>
                <div class="bg-success bg-opacity-10 text-success rounded-circle p-3">
                    <i class="bi bi-check-circle fs-4"></i>
                </div>
            </div>
        </div>
    </div>

    <div class="col-6 col-md-3">
        <div class="card stat-card border-0 bg-white shadow-sm p-3 rounded-4 border-start border-danger border-4 h-100">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <span class="small text-muted fw-semibold text-uppercase">Belum Dibaca</span>
                    <h3 class="fw-bold my-1 text-danger">{{ $totalUnread }}</h3>
                    <a href="{{ route('admin.messages.index', ['filter' => 'unread']) }}" class="small text-danger text-decoration-none">
                        Tinjau Baru &rarr;
                    </a>
                </div>
                <div class="bg-danger bg-opacity-10 text-danger rounded-circle p-3">
                    <i class="bi bi-bell fs-4"></i>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Filter Tabs Navigation & Search Bar -->
<div class="d-flex flex-wrap align-items-center justify-content-between gap-3 mb-3">
    <ul class="nav nav-pills gap-2">
        <li class="nav-item">
            <a class="nav-link {{ $filter === 'all' ? 'active bg-primary' : 'bg-white border text-secondary' }} rounded-pill px-3 py-2 small fw-semibold" href="{{ route('admin.messages.index', ['filter' => 'all', 'q' => $search]) }}">
                Semua Pesan <span class="badge bg-light text-dark ms-1">{{ $totalAll }}</span>
            </a>
        </li>
        <li class="nav-item">
            <a class="nav-link {{ $filter === 'pending' ? 'active bg-warning text-dark' : 'bg-white border text-secondary' }} rounded-pill px-3 py-2 small fw-semibold" href="{{ route('admin.messages.index', ['filter' => 'pending', 'q' => $search]) }}">
                <i class="bi bi-hourglass-split me-1"></i> Menunggu Balasan <span class="badge {{ $filter === 'pending' ? 'bg-dark text-white' : 'bg-warning text-dark' }} ms-1">{{ $totalPending }}</span>
            </a>
        </li>
        <li class="nav-item">
            <a class="nav-link {{ $filter === 'replied' ? 'active bg-success' : 'bg-white border text-secondary' }} rounded-pill px-3 py-2 small fw-semibold" href="{{ route('admin.messages.index', ['filter' => 'replied', 'q' => $search]) }}">
                <i class="bi bi-check-all me-1"></i> Sudah Dibalas <span class="badge bg-light text-dark ms-1">{{ $totalReplied }}</span>
            </a>
        </li>
        <li class="nav-item">
            <a class="nav-link {{ $filter === 'unread' ? 'active bg-danger' : 'bg-white border text-secondary' }} rounded-pill px-3 py-2 small fw-semibold" href="{{ route('admin.messages.index', ['filter' => 'unread', 'q' => $search]) }}">
                <i class="bi bi-envelope-fill me-1"></i> Belum Dibaca <span class="badge bg-light text-dark ms-1">{{ $totalUnread }}</span>
            </a>
        </li>
    </ul>

    <!-- Form Pencarian -->
    <form method="GET" action="{{ route('admin.messages.index') }}" class="d-flex align-items-center gap-2" style="min-width: 280px;">
        <input type="hidden" name="filter" value="{{ $filter }}">
        <div class="input-group input-group-sm">
            <input type="text" name="q" class="form-control" placeholder="Cari nama, email, isi pesan..." value="{{ $search }}">
            <button class="btn btn-primary" type="submit"><i class="bi bi-search"></i></button>
        </div>
        @if (!empty($search))
            <a href="{{ route('admin.messages.index', ['filter' => $filter]) }}" class="btn btn-sm btn-outline-secondary" title="Reset">
                <i class="bi bi-x-lg"></i>
            </a>
        @endif
    </form>
</div>

<!-- Daftar Pesan & Balasan -->
<div class="card shadow-sm border-0 rounded-4 overflow-hidden mb-5 bg-white">
    <div class="table-responsive">
        <table class="table align-middle mb-0">
            <thead class="table-light small">
                <tr>
                    <th style="width: 4%;">#</th>
                    <th style="width: 22%;">Pelanggan &amp; Waktu</th>
                    <th style="width: 20%;">Topik / Subjek</th>
                    <th style="width: 36%;">Percakapan Chat &amp; Balasan</th>
                    <th class="text-center" style="width: 10%;">Status</th>
                    <th class="text-center" style="width: 8%;">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @if ($messages->isEmpty())
                    <tr>
                        <td colspan="6" class="text-center py-5 text-muted">
                            <i class="bi bi-chat-square-text fs-1 d-block mb-2 text-secondary"></i>
                            Tidak ada pesan percakapan yang sesuai dengan kriteria saat ini.
                        </td>
                    </tr>
                @else
                    @foreach ($messages as $idx => $m)
                        @php
                            $hasReply = !empty($m->admin_reply);
                        @endphp
                        <tr class="{{ ($m->status === 'Belum Dibaca' && !$hasReply) ? 'table-warning bg-opacity-25' : '' }}">
                            <td class="text-muted small">{{ $messages->firstItem() + $idx }}</td>
                            <td>
                                <div class="fw-semibold text-dark">{{ $m->name }}</div>
                                <small class="text-muted d-block text-truncate" style="max-width: 200px;">
                                    <i class="bi bi-envelope me-1"></i>{{ $m->email }}
                                </small>
                                <small class="text-secondary">
                                    <i class="bi bi-clock me-1"></i>{{ $m->created_at->format('d/m/Y H:i') }} WIB
                                </small>
                            </td>
                            <td>
                                <span class="badge bg-primary-subtle text-primary border border-primary-subtle px-2 py-1 mb-1 d-inline-block text-truncate" style="max-width: 220px;">
                                    <i class="bi bi-tag-fill me-1"></i>{{ $m->subject }}
                                </span>
                                @if ($m->user)
                                    <small class="d-block text-muted" style="font-size: 0.72rem;">
                                        <i class="bi bi-person-check-fill text-success me-1"></i>Akun Terdaftar (ID: {{ $m->user_id }})
                                    </small>
                                @else
                                    <small class="d-block text-muted" style="font-size: 0.72rem;">
                                        <i class="bi bi-person-dash text-secondary me-1"></i>Tamu (Guest)
                                    </small>
                                @endif
                            </td>
                            <td>
                                <!-- Pesan Asli dari Pengguna -->
                                <div class="p-2 px-3 bg-light rounded-3 border-start border-3 border-primary mb-2 small text-dark" style="max-height: 120px; overflow-y: auto; white-space: pre-line;">
                                    {{ $m->message }}
                                </div>

                                <!-- Balasan Admin jika sudah ada -->
                                @if ($hasReply)
                                    <div class="p-2 px-3 bg-success-subtle rounded-3 border-start border-3 border-success small text-dark mb-2" style="max-height: 100px; overflow-y: auto;">
                                        <div class="d-flex align-items-center justify-content-between mb-1" style="font-size: 0.725rem;">
                                            <span class="text-success fw-bold">
                                                <i class="bi bi-patch-check-fill me-1"></i>Balasan Resmi ({{ $m->admin ? $m->admin->name : 'Administrator' }}):
                                            </span>
                                            <span class="text-muted">{{ $m->replied_at ? $m->replied_at->format('d/m/Y H:i') : '' }}</span>
                                        </div>
                                        <div style="white-space: pre-line; font-size: 0.85rem;">
                                            {{ $m->admin_reply }}
                                        </div>
                                    </div>
                                @endif

                                <!-- Tombol Buka Form Balas Modal / Collapse -->
                                <button class="btn btn-sm btn-outline-primary rounded-pill px-3 py-1" type="button" data-bs-toggle="collapse" data-bs-target="#replyBox-{{ $m->id }}" aria-expanded="false">
                                    <i class="bi bi-reply-fill me-1"></i> {{ $hasReply ? 'Perbarui Balasan' : 'Balas Chat Pengguna' }}
                                </button>

                                <!-- Kotak Form Balas -->
                                <div class="collapse mt-2" id="replyBox-{{ $m->id }}">
                                    <div class="card card-body bg-light border p-3 rounded-3">
                                        <div class="small fw-semibold text-secondary mb-2">
                                            <i class="bi bi-lightning-charge-fill text-warning me-1"></i>Pilih Template Balasan Cepat:
                                        </div>
                                        <div class="d-flex flex-wrap gap-1 mb-2">
                                            <button type="button" class="btn btn-outline-secondary btn-xs small py-0 px-2" style="font-size: 0.72rem;" onclick="setReplyTemplate({{ $m->id }}, 'Halo kak {{ addslashes($m->name) }}, buku yang kakak tanyakan saat ini ready stock di gudang kami dan siap dipesan langsung melalui website. Silakan melakukan pemesanan ya!')">
                                                Stok Ready
                                            </button>
                                            <button type="button" class="btn btn-outline-secondary btn-xs small py-0 px-2" style="font-size: 0.72rem;" onclick="setReplyTemplate({{ $m->id }}, 'Halo kak {{ addslashes($m->name) }}, pembayaran untuk pesanan Anda telah kami terima dan diverifikasi. Pesanan sedang disiapkan untuk pengiriman ke alamat tujuan. Terima kasih!')">
                                                Konfirmasi Pembayaran
                                            </button>
                                            <button type="button" class="btn btn-outline-secondary btn-xs small py-0 px-2" style="font-size: 0.72rem;" onclick="setReplyTemplate({{ $m->id }}, 'Halo kak {{ addslashes($m->name) }}, pesanan Anda telah kami serahkan ke kurir ekspedisi. Anda dapat memantau nomor resi pengiriman melalui halaman Pesanan Saya.')">
                                                Pengiriman Resi
                                            </button>
                                        </div>

                                        <form method="POST" action="{{ route('admin.messages.reply', $m) }}">
                                            @csrf
                                            <textarea name="admin_reply" id="replyTextarea-{{ $m->id }}" class="form-control form-control-sm mb-2" rows="3" placeholder="Tuliskan balasan resmi untuk {{ $m->name }}..." required>{{ $m->admin_reply }}</textarea>
                                            <div class="d-flex justify-content-end gap-2">
                                                <button type="button" class="btn btn-sm btn-secondary" data-bs-toggle="collapse" data-bs-target="#replyBox-{{ $m->id }}">Tutup</button>
                                                <button type="submit" class="btn btn-sm btn-primary">
                                                    <i class="bi bi-send me-1"></i> Kirim ke Akun User
                                                </button>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                            </td>
                            <td class="text-center">
                                @if (!$hasReply)
                                    <span class="badge bg-warning text-dark px-2 py-1 rounded-pill small">
                                        <i class="bi bi-hourglass-split me-1"></i>Menunggu
                                    </span>
                                @else
                                    <span class="badge bg-success-subtle text-success border border-success px-2 py-1 rounded-pill small">
                                        <i class="bi bi-check-all me-1"></i>Sudah Dibalas
                                    </span>
                                @endif

                                @if ($m->status === 'Belum Dibaca')
                                    <form method="POST" action="{{ route('admin.messages.read', $m) }}" class="mt-2">
                                        @csrf
                                        <button type="submit" class="btn btn-outline-secondary btn-xs py-0 px-2" style="font-size: 0.7rem;" title="Tandai Sudah Dibaca">
                                            Tandai Dibaca
                                        </button>
                                    </form>
                                @endif
                            </td>
                            <td class="text-center">
                                <form method="POST" action="{{ route('admin.messages.destroy', $m) }}" onsubmit="return confirm('Apakah Anda yakin ingin menghapus pesan percakapan ini?');">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-outline-danger btn-sm p-1 px-2 rounded-2" title="Hapus Pesan">
                                        <i class="bi bi-trash"></i>
                                    </button>
                                </form>
                            </td>
                        </tr>
                    @endforeach
                @endif
            </tbody>
        </table>
    </div>
</div>

<div class="d-flex justify-content-center mb-5">
    {{ $messages->links('pagination::bootstrap-5') }}
</div>

<script>
function setReplyTemplate(id, text) {
    const area = document.getElementById('replyTextarea-' + id);
    if (area) {
        area.value = text;
        area.focus();
    }
}
</script>
@endsection
