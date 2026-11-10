@extends('layouts.app')

@section('title', 'Keranjang Belanja - The Book Atelier')

@push('styles')
<style>
    /* Styling Stepper Kuantitas Modern & Bersih */
    .cart-qty-group {
        display: inline-flex;
        align-items: center;
        background-color: #ffffff;
        border: 1px solid #d1d5db;
        border-radius: 6px;
        overflow: hidden;
        transition: border-color 0.2s, box-shadow 0.2s;
    }
    .cart-qty-group:focus-within {
        border-color: #800020;
        box-shadow: 0 0 0 2px rgba(128, 0, 32, 0.15);
    }
    .cart-qty-btn {
        background-color: #f9fafb;
        border: none;
        color: #4b5563;
        width: 32px;
        height: 32px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 0.95rem;
        font-weight: 700;
        cursor: pointer;
        transition: background-color 0.15s, color 0.15s;
        user-select: none;
    }
    .cart-qty-btn:hover:not(:disabled) {
        background-color: #e5e7eb;
        color: #111827;
    }
    .cart-qty-btn:disabled {
        opacity: 0.35;
        cursor: not-allowed;
    }
    .cart-qty-input {
        width: 48px;
        height: 32px;
        border: none;
        border-left: 1px solid #e5e7eb;
        border-right: 1px solid #e5e7eb;
        text-align: center;
        font-weight: 600;
        font-size: 0.9rem;
        color: #111827;
        padding: 0;
        background: transparent;
        outline: none !important;
        box-shadow: none !important;
    }
    /* Sembunyikan spin button bawaan browser agar rapi */
    .cart-qty-input::-webkit-outer-spin-button,
    .cart-qty-input::-webkit-inner-spin-button {
        -webkit-appearance: none;
        margin: 0;
    }
    .cart-qty-input[type=number] {
        -moz-appearance: textfield;
    }

    .cursor-pointer {
        cursor: pointer;
    }
</style>
@endpush

@section('content')
<div class="container py-4">
    <div class="d-flex align-items-center justify-content-between mb-4 pb-2 border-bottom">
        <div>
            <h1 class="h3 fw-bold font-serif mb-1"><i class="bi bi-bag me-2 text-maroon"></i>Keranjang Belanja</h1>
            <p class="text-muted small mb-0">Pilih buku yang ingin Anda beli sekarang atau simpan untuk pesanan berikutnya</p>
        </div>
        @if (!empty($cart))
            <form method="POST" action="{{ route('cart.clear') }}" onsubmit="return confirm('Kosongkan semua buku di keranjang?');">
                @csrf
                <button type="submit" class="btn btn-outline-danger btn-sm rounded-1">
                    <i class="bi bi-trash3 me-1"></i> Kosongkan Keranjang
                </button>
            </form>
        @endif
    </div>

    @if (empty($cart))
        <div class="card border-0 shadow-sm text-center py-5 rounded-4 mb-5">
            <div class="card-body">
                <i class="bi bi-bag-x text-muted" style="font-size: 4rem;"></i>
                <h3 class="fw-bold font-serif mt-3">Keranjang Anda Masih Kosong</h3>
                <p class="text-muted small mb-4">Belum ada buku yang Anda pilih untuk dimasukkan ke dalam keranjang belanja.</p>
                <a href="{{ route('books.index') }}" class="btn btn-atelier-primary px-4 py-2">
                    <i class="bi bi-arrow-left me-2"></i> Jelajahi Katalog Buku
                </a>
            </div>
        </div>
    @else
        {{-- Alert Informatif jika ada stok habis di keranjang --}}
        @if (!empty($hasStockIssue))
            <div class="alert alert-warning d-flex align-items-start gap-3 p-3 rounded-4 shadow-sm mb-4 border-start border-4 border-warning">
                <i class="bi bi-info-circle-fill fs-3 text-warning-emphasis flex-shrink-0 mt-1"></i>
                <div class="flex-grow-1">
                    <h6 class="fw-bold mb-1 text-dark">Informasi Ketersediaan Stok Buku</h6>
                    <p class="mb-0 small text-secondary">
                        Terdapat buku yang stoknya sedang habis atau melebihi persediaan. Buku yang habis telah <strong>otomatis tidak dicentang (uncheck)</strong> sehingga Anda <strong>tetap dapat melanjutkan checkout</strong> untuk buku-buku lain yang tersedia.
                    </p>
                </div>
            </div>
        @endif

        <div class="row g-4 mb-5">
            <!-- Daftar Buku dalam Keranjang -->
            <div class="col-lg-8">
                <div class="card border-0 shadow-sm rounded-4 overflow-hidden">
                    <div class="table-responsive">
                        <table class="table align-middle mb-0">
                            <thead class="table-light small text-uppercase">
                                <tr>
                                    <th style="width: 5%;" class="text-center py-3">
                                        <input type="checkbox" id="check-all" class="form-check-input cursor-pointer" title="Pilih Semua Buku" style="width: 19px; height: 19px;">
                                    </th>
                                    <th style="width: 45%;">Buku</th>
                                    <th style="width: 18%;">Harga Satuan</th>
                                    <th style="width: 16%;" class="text-center">Jumlah</th>
                                    <th style="width: 16%;" class="text-end pe-3">Subtotal</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($cart as $bookId => $item)
                                    @php
                                        $isOutOfStock = !empty($item['is_out_of_stock']);
                                        $isShortage = !empty($item['stock_shortage']);
                                        $rowClass = $isOutOfStock ? 'table-danger bg-opacity-25' : ($isShortage ? 'table-warning bg-opacity-25' : '');
                                        $maxStock = (int)($item['current_stock'] ?? 0);
                                        // Default: dicentang jika stok tersedia, uncheck jika habis
                                        $isChecked = !$isOutOfStock;
                                    @endphp
                                    <tr class="{{ $rowClass }}" id="cart-row-{{ $bookId }}">
                                        <!-- Checkbox Pilihan Item -->
                                        <td class="text-center">
                                            <input type="checkbox" 
                                                   class="form-check-input item-checkbox cursor-pointer" 
                                                   name="selected_items[]" 
                                                   value="{{ $bookId }}"
                                                   data-id="{{ $bookId }}"
                                                   data-title="{{ $item['title'] }}"
                                                   data-price="{{ $item['price'] }}"
                                                   data-qty="{{ $item['qty'] }}"
                                                   data-stock="{{ $maxStock }}"
                                                   data-out="{{ $isOutOfStock ? 1 : 0 }}"
                                                   data-shortage="{{ $isShortage ? 1 : 0 }}"
                                                   style="width: 19px; height: 19px;"
                                                   {{ $isChecked ? 'checked' : '' }}
                                                   onchange="updateCartSummary()">
                                        </td>
                                        <td>
                                            <div class="d-flex align-items-center gap-3">
                                                <img src="{{ asset('assets/uploads/' . ($item['cover_image'] ?: 'default_book.png')) }}" alt="{{ $item['title'] }}" class="rounded shadow-sm" style="width: 50px; height: 70px; object-fit: cover;" onerror="this.src='{{ asset('assets/uploads/default_book.png') }}'">
                                                <div>
                                                    <a href="{{ route('books.show', $bookId) }}" class="fw-bold text-dark text-decoration-none d-block">
                                                        {{ $item['title'] }}
                                                    </a>

                                                    @if ($isOutOfStock)
                                                        <div class="mt-1">
                                                            <span class="badge bg-danger text-white">
                                                                <i class="bi bi-x-circle-fill me-1"></i> Stok Habis (0 eks)
                                                            </span>
                                                            <div class="text-danger small mt-1 fw-semibold" style="font-size: 0.78rem;">
                                                                Buku habis. Hilangkan centang untuk checkout buku lainnya.
                                                            </div>
                                                        </div>
                                                    @elseif ($isShortage)
                                                        <div class="mt-1">
                                                            <span class="badge bg-warning text-dark">
                                                                <i class="bi bi-exclamation-triangle-fill me-1"></i> Sisa Stok: {{ $maxStock }} eks
                                                            </span>
                                                            <div class="text-warning-emphasis small mt-1 fw-semibold" style="font-size: 0.78rem;">
                                                                Kuantitas diminta ({{ $item['qty'] }}) melebihi stok yang tersisa.
                                                            </div>
                                                        </div>
                                                    @else
                                                        <div class="text-success small mt-1" style="font-size: 0.78rem;">
                                                            <i class="bi bi-check-circle-fill me-1"></i> Stok tersedia: {{ $maxStock }} eks
                                                        </div>
                                                    @endif

                                                    <form method="POST" action="{{ route('cart.remove', $bookId) }}" class="d-inline mt-1">
                                                        @csrf
                                                        <button type="submit" class="btn btn-link p-0 text-danger small text-decoration-none border-0" style="font-size: 0.78rem;">
                                                            <i class="bi bi-trash me-1"></i> Hapus dari Keranjang
                                                        </button>
                                                    </form>
                                                </div>
                                            </div>
                                        </td>
                                        <td class="text-secondary small">
                                            Rp {{ number_format($item['price'], 0, ',', '.') }}
                                        </td>
                                        <td class="text-center">
                                            <!-- Stepper Kuantitas dengan Input Manual -->
                                            <form method="POST" action="{{ route('cart.update') }}" class="d-inline-flex flex-column align-items-center" onsubmit="return handleCartSubmit(this, {{ $maxStock }});">
                                                @csrf
                                                <input type="hidden" name="book_id" value="{{ $bookId }}">
                                                
                                                <div class="cart-qty-group shadow-sm">
                                                    <button type="button" class="cart-qty-btn" onclick="decreaseQty(this)" title="Kurangi 1">
                                                        <i class="bi bi-dash"></i>
                                                    </button>
                                                    
                                                    <input type="number" 
                                                           name="quantity" 
                                                           value="{{ $item['qty'] }}" 
                                                           min="1" 
                                                           max="{{ max(1, $maxStock) }}" 
                                                           class="cart-qty-input {{ $isOutOfStock || $isShortage ? 'text-danger' : '' }}" 
                                                           title="Ketik angka manual lalu tekan Enter atau klik di luar"
                                                           onchange="handleQtyChange(this, {{ $maxStock }})"
                                                           data-original="{{ $item['qty'] }}">
                                                           
                                                    <button type="button" class="cart-qty-btn" onclick="increaseQty(this, {{ $maxStock }})" {{ $maxStock <= $item['qty'] ? 'disabled title=Batas_maksimal_stok' : 'title=Tambah_1' }}>
                                                        <i class="bi bi-plus"></i>
                                                    </button>
                                                </div>
                                                <span class="text-muted text-nowrap mt-1" style="font-size: 0.68rem; letter-spacing: -0.01em;">
                                                    <i class="bi bi-pencil-square me-1"></i>bisa ketik
                                                </span>
                                            </form>
                                        </td>
                                        <td class="text-end pe-3 fw-bold text-maroon">
                                            Rp {{ number_format($item['price'] * $item['qty'], 0, ',', '.') }}
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            <!-- Ringkasan Pesanan (Dihitung Real-time berdasarkan Ceklis) -->
            <div class="col-lg-4">
                <div class="card border-0 shadow-sm rounded-4 p-4 bg-white sticky-top" style="top: 100px;">
                    <h5 class="fw-bold font-serif mb-3 pb-2 border-bottom">Ringkasan Belanja</h5>

                    <div class="d-flex justify-content-between mb-2 small text-secondary">
                        <span>Buku Dipilih:</span>
                        <span class="fw-bold text-dark"><span id="summary-selected-count">0</span> dari {{ count($cart) }} item</span>
                    </div>

                    <div class="d-flex justify-content-between mb-2 small text-secondary">
                        <span>Total Kuantitas:</span>
                        <span class="fw-bold text-dark" id="summary-total-qty">0 Buku</span>
                    </div>

                    <div class="d-flex justify-content-between mb-3 small text-secondary">
                        <span>Metode Pembayaran:</span>
                        <span class="fw-semibold text-dark">Payment at Delivery</span>
                    </div>

                    <div class="d-flex justify-content-between pt-3 border-top mb-4">
                        <span class="fw-bold text-dark">Total Biaya:</span>
                        <span class="fs-4 fw-bold text-maroon font-serif" id="summary-total-price">Rp 0</span>
                    </div>

                    <!-- Form Aksi Lanjut ke Checkout dengan Filter Item yang Diceklis -->
                    <form id="checkout-action-form" method="GET" action="{{ route('orders.checkout') }}">
                        <input type="hidden" name="selected_items" id="selected-items-input" value="">

                        <!-- Alert jika ada buku bermasalah stok yang diceklis -->
                        <div id="checkout-warning-box" class="p-3 bg-danger bg-opacity-10 border border-danger rounded-3 mb-3 text-start d-none">
                            <div class="d-flex align-items-center gap-2 text-danger fw-bold small mb-1">
                                <i class="bi bi-shield-lock-fill"></i> Pembayaran Terkunci
                            </div>
                            <p class="small text-danger mb-0" id="checkout-warning-text" style="font-size: 0.8rem; line-height: 1.4;">
                                Terdapat buku yang stoknya habis / melebihi persediaan. Silakan hilangkan centang buku tersebut untuk melanjutkan.
                            </p>
                        </div>

                        <!-- Alert jika tidak ada buku yang diceklis sama sekali -->
                        <div id="checkout-empty-box" class="p-3 bg-warning bg-opacity-10 border border-warning rounded-3 mb-3 text-start d-none">
                            <div class="d-flex align-items-center gap-2 text-warning-emphasis fw-bold small mb-1">
                                <i class="bi bi-exclamation-circle-fill"></i> Belum Ada Buku Dipilih
                            </div>
                            <p class="small text-dark mb-0" style="font-size: 0.8rem; line-height: 1.4;">
                                Centang minimal 1 buku yang ingin Anda beli sekarang.
                            </p>
                        </div>

                        <button type="submit" id="btn-proceed-checkout" class="btn btn-atelier-primary w-100 py-3 mb-2 fw-semibold shadow-sm">
                            <span id="btn-proceed-text">Lanjut ke Pembayaran</span>
                            <i class="bi bi-arrow-right ms-2"></i>
                        </button>
                    </form>

                    <a href="{{ route('books.index') }}" class="btn btn-outline-secondary btn-sm w-100 py-2">
                        <i class="bi bi-arrow-left me-1"></i> Tambah Buku Lainnya
                    </a>
                </div>
            </div>
        </div>
    @endif
</div>
@endsection

@push('scripts')
<script>
    // 1. Kurangi 1 kuantitas
    function decreaseQty(btn) {
        const form = btn.closest('form');
        const input = form.querySelector('input[name="quantity"]');
        let current = parseInt(input.value) || 1;
        
        if (current > 1) {
            input.value = current - 1;
            submitCartForm(form);
        } else {
            if (confirm('Kuantitas buku sudah 1. Apakah Anda ingin menghapus buku ini dari keranjang?')) {
                input.value = 0;
                submitCartForm(form);
            }
        }
    }

    // 2. Tambah 1 kuantitas
    function increaseQty(btn, maxStock) {
        const form = btn.closest('form');
        const input = form.querySelector('input[name="quantity"]');
        let current = parseInt(input.value) || 1;
        
        if (maxStock > 0 && current >= maxStock) {
            alert('Jumlah pesanan sudah mencapai batas stok maksimal yang tersedia (' + maxStock + ' eks).');
            return;
        }
        input.value = current + 1;
        submitCartForm(form);
    }

    // 3. Validasi saat input manual diketik & form akan disubmit
    function handleCartSubmit(form, maxStock) {
        if (form.dataset.submitting === 'true') {
            return false;
        }

        const input = form.querySelector('input[name="quantity"]');
        let val = parseInt(input.value);

        if (isNaN(val) || val <= 0) {
            if (confirm('Kuantitas tidak valid (0). Hapus buku ini dari keranjang belanja?')) {
                input.value = 0;
                form.dataset.submitting = 'true';
                return true;
            } else {
                input.value = input.dataset.original || 1;
                return false;
            }
        }

        if (maxStock > 0 && val > maxStock) {
            alert('Jumlah yang Anda ketik (' + val + ' eks) melebihi stok yang tersedia (' + maxStock + ' eks).\nJumlah disesuaikan otomatis ke stok maksimal (' + maxStock + ' eks).');
            input.value = maxStock;
        }

        form.dataset.submitting = 'true';
        return true;
    }

    // 4. Trigger update saat pengguna selesai mengetik angka manual & klik di luar (blur/change)
    function handleQtyChange(input, maxStock) {
        const form = input.closest('form');
        if (form.dataset.submitting === 'true') return;

        if (handleCartSubmit(form, maxStock)) {
            form.submit();
        }
    }

    // Helper mencegah double submit
    function submitCartForm(form) {
        if (form.dataset.submitting === 'true') return;
        form.dataset.submitting = 'true';
        form.submit();
    }

    // ==========================================================
    // 5. LOGIKA CHECKBOX / CEKLIS ITEM KERANJANG REAL-TIME
    // ==========================================================
    function updateCartSummary() {
        const checkboxes = document.querySelectorAll('.item-checkbox');
        const checkAll = document.getElementById('check-all');
        const selectedCountEl = document.getElementById('summary-selected-count');
        const totalQtyEl = document.getElementById('summary-total-qty');
        const totalPriceEl = document.getElementById('summary-total-price');
        const hiddenInput = document.getElementById('selected-items-input');
        const btnProceed = document.getElementById('btn-proceed-checkout');
        const btnText = document.getElementById('btn-proceed-text');
        const warningBox = document.getElementById('checkout-warning-box');
        const emptyBox = document.getElementById('checkout-empty-box');

        let selectedIds = [];
        let totalQty = 0;
        let totalPrice = 0;
        let checkedCount = 0;
        let totalCount = checkboxes.length;
        let hasSelectedStockIssue = false;
        let issueTitles = [];

        checkboxes.forEach(cb => {
            if (cb.checked) {
                checkedCount++;
                const id = cb.dataset.id;
                const price = parseFloat(cb.dataset.price) || 0;
                const qty = parseInt(cb.dataset.qty) || 0;
                const isOut = cb.dataset.out === '1';
                const isShortage = cb.dataset.shortage === '1';

                selectedIds.push(id);
                totalQty += qty;
                totalPrice += price * qty;

                if (isOut || isShortage) {
                    hasSelectedStockIssue = true;
                    issueTitles.push(cb.dataset.title);
                }
            }
        });

        // Update ringkasan teks di UI
        if (selectedCountEl) selectedCountEl.textContent = checkedCount;
        if (totalQtyEl) totalQtyEl.textContent = totalQty + ' Buku';
        if (totalPriceEl) totalPriceEl.textContent = 'Rp ' + totalPrice.toLocaleString('id-ID');
        if (hiddenInput) hiddenInput.value = selectedIds.join(',');

        // Update status checkbox Master "Pilih Semua"
        if (checkAll) {
            if (checkedCount === 0) {
                checkAll.checked = false;
                checkAll.indeterminate = false;
            } else if (checkedCount === totalCount) {
                checkAll.checked = true;
                checkAll.indeterminate = false;
            } else {
                checkAll.checked = false;
                checkAll.indeterminate = true;
            }
        }

        // Validasi tombol Checkout
        if (checkedCount === 0) {
            // Kasus 1: Tidak ada item yang dicentang
            emptyBox.classList.remove('d-none');
            warningBox.classList.add('d-none');
            btnProceed.disabled = true;
            btnProceed.className = 'btn btn-secondary w-100 py-3 mb-2 fw-semibold opacity-75';
            btnProceed.style.cursor = 'not-allowed';
            btnText.textContent = 'Pilih Buku Terlebih Dahulu';
        } else if (hasSelectedStockIssue) {
            // Kasus 2: Ada item dicentang yang stoknya habis / melebihi stok
            emptyBox.classList.add('d-none');
            warningBox.classList.remove('d-none');
            btnProceed.disabled = true;
            btnProceed.className = 'btn btn-secondary w-100 py-3 mb-2 fw-semibold opacity-75';
            btnProceed.style.cursor = 'not-allowed';
            btnText.textContent = 'Pembayaran Terkunci (Stok Bermasalah)';
        } else {
            // Kasus 3: Semua item yang dicentang aman & stok mencukupi!
            emptyBox.classList.add('d-none');
            warningBox.classList.add('d-none');
            btnProceed.disabled = false;
            btnProceed.className = 'btn btn-atelier-primary w-100 py-3 mb-2 fw-semibold shadow-sm';
            btnProceed.style.cursor = 'pointer';
            btnText.textContent = 'Lanjut ke Pembayaran (' + checkedCount + ')';
        }
    }

    // Toggle Pilih Semua (Check All)
    document.addEventListener('DOMContentLoaded', function() {
        const checkAll = document.getElementById('check-all');
        if (checkAll) {
            checkAll.addEventListener('change', function() {
                const checkboxes = document.querySelectorAll('.item-checkbox');
                checkboxes.forEach(cb => {
                    // Jika dicentang semua, centang hanya yang tersedia agar tidak otomatis terkunci
                    if (checkAll.checked) {
                        cb.checked = cb.dataset.out !== '1';
                    } else {
                        cb.checked = false;
                    }
                });
                updateCartSummary();
            });
        }

        // Inisialisasi perhitungan pertama kali load halaman
        updateCartSummary();
    });
</script>
@endpush
