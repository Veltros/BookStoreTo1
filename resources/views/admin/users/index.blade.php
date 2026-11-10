@extends('layouts.admin')

@section('title', 'Data Pengguna Terdaftar')

@section('content')
<div class="d-flex flex-wrap align-items-center justify-content-between gap-3 mb-4">
    <div>
        <h2 class="fw-bold mb-1"><i class="bi bi-people text-primary me-2"></i>Data Pengguna Terdaftar</h2>
        <p class="text-muted small mb-0">Daftar pelanggan dan administrator yang memiliki akun di sistem</p>
    </div>
</div>

<!-- Search Bar -->
<div class="card shadow-sm border-0 rounded-4 mb-4 bg-white">
    <div class="card-body p-3">
        <form method="GET" action="{{ route('admin.users.index') }}" class="row g-2 align-items-center">
            <div class="col-md-9">
                <div class="input-group">
                    <span class="input-group-text bg-white"><i class="bi bi-search text-muted"></i></span>
                    <input type="text" name="q" class="form-control border-start-0" placeholder="Cari nama pengguna, email, atau no. telepon..." value="{{ $search }}">
                </div>
            </div>
            <div class="col-md-3 d-flex gap-2">
                <button type="submit" class="btn btn-primary flex-grow-1 rounded-3">Cari User</button>
                @if (!empty($search))
                    <a href="{{ route('admin.users.index') }}" class="btn btn-outline-secondary rounded-3" title="Reset">
                        <i class="bi bi-x-lg"></i>
                    </a>
                @endif
            </div>
        </form>
    </div>
</div>

<!-- Tabel Daftar User -->
<div class="card shadow-sm border-0 rounded-4 overflow-hidden mb-5 bg-white">
    <div class="table-responsive">
        <table class="table align-middle mb-0">
            <thead class="table-light small">
                <tr>
                    <th style="width: 5%;">#</th>
                    <th style="width: 25%;">Nama &amp; Email</th>
                    <th style="width: 15%;">No. Telepon</th>
                    <th style="width: 25%;">Alamat Terdaftar</th>
                    <th class="text-center" style="width: 10%;">Peran</th>
                    <th class="text-center" style="width: 10%;">Total Pesanan</th>
                    <th style="width: 10%;">Terdaftar Sejak</th>
                </tr>
            </thead>
            <tbody>
                @if ($users->isEmpty())
                    <tr>
                        <td colspan="7" class="text-center py-5 text-muted">
                            <i class="bi bi-person-x fs-1 d-block mb-2"></i>
                            Tidak ada data pengguna yang ditemukan.
                        </td>
                    </tr>
                @else
                    @foreach ($users as $index => $u)
                        <tr>
                            <td>{{ $users->firstItem() + $index }}</td>
                            <td>
                                <div class="fw-bold text-dark">{{ $u->name }}</div>
                                <div class="small text-muted"><i class="bi bi-envelope me-1"></i>{{ $u->email }}</div>
                            </td>
                            <td>{{ $u->phone ?: '-' }}</td>
                            <td class="small text-muted">{{ $u->address ?: '-' }}</td>
                            <td class="text-center">
                                @if ($u->role === 'admin')
                                    <span class="badge bg-warning text-dark px-3 py-1 rounded-pill">Administrator</span>
                                @else
                                    <span class="badge bg-primary-subtle text-primary px-3 py-1 rounded-pill">Pelanggan</span>
                                @endif
                            </td>
                            <td class="text-center">
                                <span class="badge bg-light text-dark border px-2 py-1">
                                    {{ $u->orders_count }} Pesanan
                                </span>
                            </td>
                            <td class="small text-muted">{{ $u->created_at->format('d M Y') }}</td>
                        </tr>
                    @endforeach
                @endif
            </tbody>
        </table>
    </div>
</div>

<div class="d-flex justify-content-center mb-5">
    {{ $users->links('pagination::bootstrap-5') }}
</div>
@endsection
