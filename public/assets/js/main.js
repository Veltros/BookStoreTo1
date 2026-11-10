// assets/js/main.js - Interactive UI helpers for BookStore
document.addEventListener('DOMContentLoaded', function () {
    // 1. Confirm delete actions
    const deleteButtons = document.querySelectorAll('.btn-confirm-delete');
    deleteButtons.forEach(btn => {
        btn.addEventListener('click', function (e) {
            const itemName = this.getAttribute('data-name') || 'data ini';
            if (!confirm(`Apakah Anda yakin ingin menghapus ${itemName}? Tindakan ini tidak dapat dibatalkan.`)) {
                e.preventDefault();
            }
        });
    });

    // 2. Auto dismiss alerts after 6 seconds
    const alerts = document.querySelectorAll('.alert-dismissible');
    alerts.forEach(alert => {
        setTimeout(() => {
            const bsAlert = bootstrap.Alert.getOrCreateInstance(alert);
            if (bsAlert) {
                bsAlert.close();
            }
        }, 6000);
    });

    // 3. One-click Copy to Clipboard (for Account Number & Total Amount)
    const copyButtons = document.querySelectorAll('.btn-copy-clipboard');
    copyButtons.forEach(btn => {
        btn.addEventListener('click', function () {
            const textToCopy = this.getAttribute('data-clipboard-text') || '';
            if (textToCopy) {
                navigator.clipboard.writeText(textToCopy).then(() => {
                    const originalHtml = this.innerHTML;
                    this.innerHTML = `<i class="bi bi-check2 text-success me-1"></i> Tersalin!`;
                    this.classList.add('btn-success', 'text-white');
                    this.classList.remove('btn-outline-secondary', 'btn-light');
                    setTimeout(() => {
                        this.innerHTML = originalHtml;
                        this.classList.remove('btn-success', 'text-white');
                    }, 2000);
                }).catch(err => {
                    alert('Nomor: ' + textToCopy);
                });
            }
        });
    });

    // Helper: Tampilkan efek visual shake & pesan peringatan stok
    function triggerStockExceededWarning(input, message) {
        if (!input) return;
        const stepper = input.closest('.qty-stepper');
        if (stepper) {
            stepper.classList.remove('is-exceeded');
            void stepper.offsetWidth; // Trigger reflow for animation restart
            stepper.classList.add('is-exceeded');
            setTimeout(() => {
                stepper.classList.remove('is-exceeded');
            }, 2500);
        }

        // Tampilkan teks peringatan di bawah stepper jika ada container
        let parentForm = input.closest('form');
        let warningContainer = parentForm ? parentForm.querySelector('.stock-warning-container') : null;
        if (!warningContainer && stepper) {
            warningContainer = stepper.parentElement.querySelector('.stock-warning-container');
        }
        if (warningContainer) {
            warningContainer.innerHTML = `<span class="stock-warning-text"><i class="bi bi-exclamation-triangle-fill"></i> ${message}</span>`;
            setTimeout(() => {
                warningContainer.innerHTML = '';
            }, 4000);
        }

        // Munculkan alert dialog browser
        alert(message);
    }

    // 4. Quantity Stepper (+ and - buttons) dengan Batas Kapasitas Stok
    document.querySelectorAll('.qty-decrement').forEach(btn => {
        btn.addEventListener('click', function () {
            const input = this.parentElement.querySelector('input[type="number"]');
            if (input) {
                let val = parseInt(input.value) || 1;
                const min = parseInt(input.getAttribute('min')) || 1;
                if (val > min) {
                    input.value = val - 1;
                    input.dispatchEvent(new Event('change'));
                }
            }
        });
    });

    document.querySelectorAll('.qty-increment').forEach(btn => {
        btn.addEventListener('click', function () {
            const input = this.parentElement.querySelector('input[type="number"]');
            if (input) {
                let val = parseInt(input.value) || 1;
                const max = parseInt(input.getAttribute('max')) || 999;
                if (val < max) {
                    input.value = val + 1;
                    input.dispatchEvent(new Event('change'));
                } else {
                    triggerStockExceededWarning(
                        input,
                        `Peringatan: Jumlah pesanan tidak dapat melebihi kapasitas stok yang tercatat (${max} eks)!`
                    );
                }
            }
        });
    });

    // Validasi pengetikan angka manual pada input kuantitas
    document.querySelectorAll('.qty-stepper input[type="number"]').forEach(input => {
        input.addEventListener('change', function () {
            const max = parseInt(this.getAttribute('max'));
            const min = parseInt(this.getAttribute('min')) || 1;
            let val = parseInt(this.value);

            if (isNaN(val) || val < min) {
                this.value = min;
            } else if (max && val > max) {
                this.value = max;
                triggerStockExceededWarning(
                    this,
                    `Peringatan: Jumlah yang dimasukkan melebihi kapasitas stok yang tersedia (${max} eks). Jumlah otomatis disesuaikan ke ${max} eks.`
                );
            }
        });

        input.addEventListener('input', function () {
            const max = parseInt(this.getAttribute('max'));
            let val = parseInt(this.value);
            if (max && val > max) {
                this.value = max;
                triggerStockExceededWarning(
                    this,
                    `Peringatan: Maksimal pesanan sesuai kapasitas stok adalah ${max} eks.`
                );
            }
        });
    });

    // 5. Validasi Klik Form Tambah Buku di Detail (book_detail.php)
    const formAddToCart = document.getElementById('formAddToCart');
    if (formAddToCart) {
        formAddToCart.addEventListener('submit', function (e) {
            const input = document.getElementById('qty');
            if (input) {
                const max = parseInt(input.getAttribute('max')) || 999;
                const val = parseInt(input.value) || 1;
                if (val > max) {
                    e.preventDefault();
                    input.value = max;
                    triggerStockExceededWarning(
                        input,
                        `Peringatan: Jumlah pesanan (${val} eks) melebihi kapasitas stok yang dapat ditambahkan (maksimal ${max} eks)!`
                    );
                    return false;
                }
            }
        });
    }

    // 6. Validasi Klik Update Keranjang (cart.php)
    document.querySelectorAll('.form-update-cart').forEach(form => {
        form.addEventListener('submit', function (e) {
            const input = this.querySelector('input[type="number"]');
            if (input) {
                const max = parseInt(input.getAttribute('max')) || 999;
                const val = parseInt(input.value) || 1;
                const title = this.getAttribute('data-title') || 'buku ini';
                if (val > max) {
                    e.preventDefault();
                    input.value = max;
                    triggerStockExceededWarning(
                        input,
                        `Peringatan: Jumlah pesanan untuk "${title}" tidak dapat melebihi kapasitas stok yang tercatat (${max} eks)!`
                    );
                    return false;
                }
            }
        });
    });

    // 7. Validasi Klik Cepat "Beli" di Katalog (index.php)
    document.querySelectorAll('.form-quick-buy').forEach(form => {
        form.addEventListener('submit', function (e) {
            const stock = parseInt(this.getAttribute('data-stock')) || 0;
            const inCart = parseInt(this.getAttribute('data-incart')) || 0;
            const title = this.getAttribute('data-title') || 'buku ini';

            if (inCart >= stock) {
                e.preventDefault();
                alert(`Peringatan: Jumlah pesanan buku "${title}" di keranjang belanja sudah mencapai batas maksimal kapasitas stok yang tercatat (${stock} eks)!`);
                return false;
            }
        });
    });

    // 8. Validasi Ketat Formulir Checkout (Wajib Isi Alamat Lengkap)
    const checkoutForm = document.getElementById('checkoutForm');
    if (checkoutForm) {
        checkoutForm.addEventListener('submit', function (e) {
            const addressInput = document.getElementById('shipping_address');
            const recipientName = document.getElementById('recipient_name');
            const recipientPhone = document.getElementById('recipient_phone');

            let hasError = false;
            let errorMessages = [];

            // Validasi Alamat (Wajib Diisi dan tidak hanya spasi)
            if (!addressInput || addressInput.value.trim().length === 0) {
                hasError = true;
                errorMessages.push('Alamat pengiriman lengkap wajib diisi!');
                if (addressInput) {
                    addressInput.classList.add('is-invalid');
                }
            } else if (addressInput.value.trim().length < 8) {
                hasError = true;
                errorMessages.push('Alamat pengiriman terlalu singkat. Harap cantumkan jalan, nomor rumah, RT/RW, dan kota dengan jelas.');
                addressInput.classList.add('is-invalid');
            } else {
                addressInput.classList.remove('is-invalid');
            }

            // Validasi Nomor Telepon
            if (!recipientPhone || recipientPhone.value.trim().length === 0) {
                hasError = true;
                errorMessages.push('Nomor telepon/HP penerima wajib diisi!');
                if (recipientPhone) recipientPhone.classList.add('is-invalid');
            } else {
                if (recipientPhone) recipientPhone.classList.remove('is-invalid');
            }

            // Validasi Nama Penerima
            if (!recipientName || recipientName.value.trim().length === 0) {
                hasError = true;
                errorMessages.push('Nama penerima paket wajib diisi!');
                if (recipientName) recipientName.classList.add('is-invalid');
            } else {
                if (recipientName) recipientName.classList.remove('is-invalid');
            }

            if (hasError) {
                e.preventDefault();
                e.stopPropagation();

                // Munculkan peringatan alert dialog interaktif
                alert('PERINGATAN:\n' + errorMessages.join('\n') + '\n\nSilakan lengkapi formulir sebelum membuat pesanan.');

                // Tampilkan alert banner merah di halaman
                let alertContainer = document.getElementById('checkoutAlertContainer');
                if (!alertContainer) {
                    alertContainer = document.createElement('div');
                    alertContainer.id = 'checkoutAlertContainer';
                    checkoutForm.parentNode.insertBefore(alertContainer, checkoutForm);
                }

                alertContainer.innerHTML = `
                    <div class="alert alert-danger shadow-sm border-danger rounded-3 p-3 mb-4 d-flex align-items-start gap-3" role="alert">
                        <i class="bi bi-exclamation-octagon-fill fs-3 text-danger flex-shrink-0 mt-1"></i>
                        <div class="flex-grow-1">
                            <strong class="fs-6 d-block mb-1">Pesanan Tidak Dapat Diproses:</strong>
                            <ul class="mb-0 ps-3">
                                ${errorMessages.map(msg => `<li>${msg}</li>`).join('')}
                            </ul>
                            <small class="text-danger-emphasis mt-2 d-block">
                                <i class="bi bi-info-circle me-1"></i> Alamat pengiriman lengkap wajib diisi dengan jelas agar kurir dapat mengantar buku ke lokasi Anda.
                            </small>
                        </div>
                    </div>
                `;

                // Scroll langsung dan fokus ke kolom yang bermasalah (utamakan alamat)
                if (!addressInput || addressInput.value.trim().length === 0) {
                    addressInput.focus();
                    addressInput.scrollIntoView({ behavior: 'smooth', block: 'center' });
                } else if (recipientPhone && recipientPhone.value.trim().length === 0) {
                    recipientPhone.focus();
                } else if (recipientName && recipientName.value.trim().length === 0) {
                    recipientName.focus();
                }

                return false;
            }
        });

        // Event listener saat user mulai mengetik alamat untuk menghapus status is-invalid
        const addressField = document.getElementById('shipping_address');
        if (addressField) {
            addressField.addEventListener('input', function () {
                if (this.value.trim().length >= 8) {
                    this.classList.remove('is-invalid');
                }
            });
        }
    }

    // 9. Validasi Ketat Formulir Registrasi (Password min 8 + kombinasi, No Telpon angka saja)
    const registerForm = document.getElementById('registerForm');
    if (registerForm) {
        const phoneInput = document.getElementById('phone');
        const passwordInput = document.getElementById('password');
        const confirmPasswordInput = document.getElementById('confirm_password');

        // Mencegah pengetikan huruf secara real-time pada kolom nomor telepon
        if (phoneInput) {
            phoneInput.addEventListener('keypress', function (e) {
                // Hanya perbolehkan digit 0-9 dan tombol kontrol navigasi
                if (!/[0-9]/.test(e.key) && e.key !== 'Backspace' && e.key !== 'Delete' && e.key !== 'ArrowLeft' && e.key !== 'ArrowRight' && e.key !== 'Tab') {
                    e.preventDefault();
                    alert('Peringatan: Kolom nomor telepon hanya boleh diisi dengan angka, tidak boleh ada huruf yang terselip!');
                }
            });

            phoneInput.addEventListener('input', function () {
                // Jika user melakukan paste teks yang mengandung huruf
                if (/[a-zA-Z]/.test(this.value)) {
                    this.value = this.value.replace(/[a-zA-Z]/g, '');
                    alert('Peringatan: Huruf yang terselip pada nomor telepon telah dihapus otomatis. Hanya angka yang diperbolehkan!');
                }
            });
        }

        registerForm.addEventListener('submit', function (e) {
            let regErrors = [];
            const passVal = passwordInput ? passwordInput.value : '';
            const confirmVal = confirmPasswordInput ? confirmPasswordInput.value : '';
            const phoneVal = phoneInput ? phoneInput.value.trim() : '';

            // Validasi Password: Min 8 karakter
            if (passVal.length < 8) {
                regErrors.push('Kata sandi minimal harus 8 karakter.');
            }

            // Validasi Password: Harus kombinasi angka atau karakter khusus
            const hasNumber = /[0-9]/.test(passVal);
            const hasSpecial = /[^a-zA-Z0-9]/.test(passVal);
            if (!hasNumber && !hasSpecial) {
                regErrors.push('Kata sandi harus mengandung kombinasi angka atau kredensial/karakter khusus (misal: @, #, $, 1, 2, dll).');
            }

            // Validasi Konfirmasi Password
            if (passVal !== confirmVal) {
                regErrors.push('Konfirmasi kata sandi tidak cocok dengan kata sandi.');
            }

            // Validasi Nomor Telepon
            if (phoneVal.length === 0) {
                regErrors.push('Nomor telepon / WhatsApp wajib diisi.');
            } else if (/[a-zA-Z]/.test(phoneVal)) {
                regErrors.push('Nomor telepon tidak valid! Tidak boleh ada huruf yang terselip.');
            } else if (phoneVal.replace(/[^0-9]/g, '').length < 10) {
                regErrors.push('Nomor telepon minimal harus 10 digit angka (contoh: 081234567890).');
            }

            if (regErrors.length > 0) {
                e.preventDefault();
                alert('PERINGATAN REGISTRASI AKUN:\n\n' + regErrors.map(err => '• ' + err).join('\n'));

                if (passVal.length < 8 || (!hasNumber && !hasSpecial)) {
                    passwordInput.focus();
                } else if (passVal !== confirmVal) {
                    confirmPasswordInput.focus();
                } else if (phoneInput) {
                    phoneInput.focus();
                }
                return false;
            }
        });
    }

    // 10. Validasi Formulir Login (Password minimal 8 karakter + kombinasi angka/simbol)
    const loginForm = document.getElementById('loginForm');
    if (loginForm) {
        loginForm.addEventListener('submit', function (e) {
            const emailInput = document.getElementById('email');
            const passwordInput = document.getElementById('password');

            const emailVal = emailInput ? emailInput.value.trim() : '';
            const passVal = passwordInput ? passwordInput.value : '';

            let logErrors = [];

            if (emailVal === '') {
                logErrors.push('Alamat email wajib diisi.');
            }

            if (passVal.length < 8) {
                logErrors.push('Kata sandi minimal harus 8 karakter.');
            }

            const hasNumber = /[0-9]/.test(passVal);
            const hasSpecial = /[^a-zA-Z0-9]/.test(passVal);
            if (!hasNumber && !hasSpecial) {
                logErrors.push('Kata sandi harus mengandung kombinasi angka atau kredensial/karakter khusus (misal: @, #, $, 1, 2, dll).');
            }

            if (logErrors.length > 0) {
                e.preventDefault();
                alert('PERINGATAN MASUK (LOGIN):\n\n' + logErrors.map(err => '• ' + err).join('\n'));

                if (passwordInput && (passVal.length < 8 || (!hasNumber && !hasSpecial))) {
                    passwordInput.focus();
                } else if (emailInput && emailVal === '') {
                    emailInput.focus();
                }
                return false;
            }
        });
    }
});
