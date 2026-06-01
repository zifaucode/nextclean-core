@extends('layouts.cashier.app')

@section('title', 'Katalog Layanan - Kasir')

@push('styles')
<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
<link href="https://cdn.jsdelivr.net/npm/select2-bootstrap-5-theme@1.3.0/dist/select2-bootstrap-5-theme.min.css" rel="stylesheet" />
<style>
    /* Select2 custom adjustments for compact size */
    .select2-container--bootstrap-5 .select2-selection {
        font-size: 13px;
        min-height: calc(1.5em + .5rem + 2px);
    }
    .select2-container--bootstrap-5 .select2-selection--single .select2-selection__rendered {
        padding: .25rem .75rem;
    }
    
    /* Service Card Custom CSS */
    .card-service {
        background-color: var(--nc-surface);
        border: 1px solid var(--nc-border);
        border-radius: 0.5rem;
        padding: 1rem;
        display: flex;
        flex-direction: column;
        align-items: center;
        justify-content: center;
        text-align: center;
        gap: 0.75rem;
        box-shadow: 0 1px 3px 0 rgba(0,0,0,0.05);
        transition: all 0.2s ease-in-out;
        cursor: pointer;
        width: 100%;
        background: none;
    }

    .card-service:hover {
        border-color: rgba(171, 0, 90, 0.3); /* Primary with opacity */
    }

    .card-service:active {
        transform: scale(0.95);
    }

    .icon-wrapper {
        width: 48px;
        height: 48px;
        border-radius: 50%;
        background-color: #fdfafb; /* Very light primary */
        color: var(--bs-primary);
        display: flex;
        align-items: center;
        justify-content: center;
        transition: all 0.2s ease-in-out;
    }

    .card-service:hover .icon-wrapper {
        background-color: #ffd9e2; /* Primary container */
        color: #fff0f2;
    }

    /* Order Panel CSS */
    .order-panel {
        background-color: var(--nc-surface);
        border: 1px solid var(--nc-border);
        border-radius: 1rem;
        box-shadow: 0 10px 15px -3px rgba(0,0,0,0.1);
        display: flex;
        flex-direction: column;
        height: calc(100vh - 64px - 4rem); /* Viewport - header - padding */
        position: sticky;
        top: calc(64px + 2rem);
    }

    .order-header {
        padding: 1.25rem;
        border-bottom: 1px solid var(--nc-border);
        background-color: #f8f9fa;
        border-top-left-radius: 1rem;
        border-top-right-radius: 1rem;
    }

    .order-body {
        flex-grow: 1;
        overflow-y: auto;
        padding: 1.25rem;
    }

    .order-footer {
        padding: 1.25rem;
        border-top: 1px solid var(--nc-border);
        background-color: #f8f9fa;
        border-bottom-left-radius: 1rem;
        border-bottom-right-radius: 1rem;
    }

    .qty-control {
        display: flex;
        align-items: center;
        border: 1px solid var(--nc-border);
        border-radius: 0.375rem;
        overflow: hidden;
        background-color: #fff;
    }

    .qty-btn {
        padding: 0.25rem 0.5rem;
        color: var(--nc-text-muted);
        background: transparent;
        border: none;
        transition: background 0.2s;
    }

    .qty-btn:hover {
        background-color: #f3f4f5;
    }

    .qty-input {
        width: 32px;
        text-align: center;
        border: none;
        padding: 0;
        font-size: 12px;
    }
    
    .qty-input:focus {
        outline: none;
    }

    .payment-method-btn {
        padding: 0.5rem 0.25rem;
        border: 1px solid var(--nc-border);
        color: var(--nc-text-muted);
        border-radius: 0.375rem;
        font-size: 13px;
        font-weight: 600;
        text-align: center;
        background: transparent;
        transition: all 0.2s;
        width: 100%;
    }

    .payment-method-btn.active {
        border-color: var(--bs-primary);
        background-color: rgba(171, 0, 90, 0.1);
        color: var(--bs-primary);
    }

    .payment-method-btn:not(.active):hover {
        background-color: #f3f4f5;
    }
</style>
@endpush

@section('content')
<div class="row gx-4">
    <!-- Left Column: Catalog -->
    <div class="col-lg-8 d-flex flex-column gap-3 mb-4 mb-lg-0">
        <!-- Filter Header -->
        <div class="d-flex justify-content-between align-items-center mb-2">
            <h2 class="h5 mb-0 fw-semibold">Katalog Layanan</h2>
            <div class="d-flex gap-2" id="category-filters">
                <button type="button" class="btn badge bg-primary rounded-pill px-3 py-2 filter-btn border-0" data-filter="all" style="font-weight: 600; font-size:11px;">Semua</button>
                <button type="button" class="btn badge bg-light text-dark border rounded-pill px-3 py-2 filter-btn" data-filter="Kiloan" style="font-weight: 600; font-size:11px;">Kiloan</button>
                <button type="button" class="btn badge bg-light text-dark border rounded-pill px-3 py-2 filter-btn" data-filter="Satuan" style="font-weight: 600; font-size:11px;">Satuan</button>
            </div>
        </div>

        <!-- Catalog Grid -->
        <div class="row g-3" id="product-grid">
            @forelse($products as $product)
            <div class="col-6 col-sm-4 col-xl-3 product-item" data-type="{{ $product->type }}">
                <button type="button" class="card-service add-to-cart" 
                        data-id="{{ $product->id }}" 
                        data-name="{{ $product->name }}" 
                        data-price="{{ $product->price }}" 
                        data-type="{{ $product->type }}">
                    <div class="icon-wrapper">
                        @if($product->type == 'Kiloan')
                            <i class="bi bi-basket2 fs-4"></i>
                        @else
                            <i class="bi bi-box-seam fs-4"></i>
                        @endif
                    </div>
                    <div class="d-flex flex-column">
                        <span class="fw-semibold text-dark" style="font-size: 13px;">{{ $product->name }}</span>
                        <span class="text-primary fw-bold mt-1" style="font-size: 12px;">
                            Rp {{ number_format($product->price, 0, ',', '.') }} {{ $product->type == 'Kiloan' ? '/ kg' : '/ pcs' }}
                        </span>
                    </div>
                </button>
            </div>
            @empty
            <div class="col-12 text-center text-muted my-5">
                <i class="bi bi-inbox fs-1"></i>
                <p class="mt-2">Belum ada layanan/produk yang ditambahkan.</p>
            </div>
            @endforelse
        </div>
    </div>

    <!-- Right Column: Order Details -->
    <div class="col-lg-4">
        <form action="{{ route('cashier.transaction.store') }}" method="POST" id="pos-form">
            @csrf
            <input type="hidden" name="source" value="cashier">
            <input type="hidden" name="is_paid" id="is_paid" value="1">
            
            <div class="order-panel">
                <!-- Header -->
                <div class="order-header d-flex justify-content-between align-items-center">
                    <h2 class="h5 mb-0 fw-semibold text-dark">Detail Pesanan</h2>
                    <span class="badge bg-light border text-secondary px-2 py-1">#NEW</span>
                </div>

                <!-- Body -->
                <div class="order-body d-flex flex-column gap-3">
                    <!-- Outlet Selection -->
                    <div class="d-flex flex-column gap-1">
                        <label class="text-uppercase text-muted" style="font-size: 11px; font-weight: 700; letter-spacing: 0.05em;">Outlet</label>
                        <select name="outlet_id" class="form-select form-select-sm" required style="font-size: 13px;">
                            @foreach($outlets as $outlet)
                                <option value="{{ $outlet->id }}">{{ $outlet->name }}</option>
                            @endforeach
                        </select>
                    </div>

                    <!-- Customer Selection -->
                    <div class="d-flex flex-column gap-1">
                        <label class="text-uppercase text-muted" style="font-size: 11px; font-weight: 700; letter-spacing: 0.05em;">Pelanggan</label>
                        <div class="d-flex gap-2">
                            <div class="flex-grow-1">
                                <select name="customer_id" id="customer_id" class="form-select form-select-sm select2" required style="font-size: 13px;">
                                    <option value="">-- Pilih Pelanggan --</option>
                                    @foreach($customers as $customer)
                                        <option value="{{ $customer->id }}">{{ $customer->name }} - {{ $customer->phone }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <button type="button" class="btn btn-sm btn-outline-primary d-flex align-items-center justify-content-center" data-bs-toggle="modal" data-bs-target="#addCustomerModal" style="width: 36px; height: 36px;" title="Tambah Pelanggan Baru">
                                <i class="bi bi-plus-lg"></i>
                            </button>
                        </div>
                    </div>

                    <hr class="my-1 text-muted">

                    <!-- Order Items Container -->
                    <div class="d-flex flex-column gap-3" id="cart-items-container">
                        <div class="text-center text-muted my-4" id="empty-cart-msg">
                            <i class="bi bi-cart-x fs-2"></i>
                            <p class="mt-2 mb-0" style="font-size: 13px;">Keranjang masih kosong</p>
                        </div>
                    </div>

                    <!-- Notes Input (Hidden by default) -->
                    <div class="d-none mt-2" id="notes-container">
                        <textarea name="notes" class="form-control form-control-sm" rows="2" placeholder="Tulis catatan pesanan di sini..." style="font-size: 13px;"></textarea>
                    </div>

                    <!-- Add Note Button -->
                    <button type="button" id="btn-toggle-notes" class="btn btn-outline-primary border-dashed w-100 py-2 mt-2 d-flex align-items-center justify-content-center gap-2" style="border-style: dashed; font-size: 13px; font-weight: 600;">
                        <i class="bi bi-plus-circle"></i> Tambah Catatan
                    </button>
                </div>

                <!-- Footer -->
                <div class="order-footer d-flex flex-column gap-3">
                    <!-- Payment Methods -->
                    <div class="d-flex flex-column gap-2">
                        <label class="text-uppercase text-muted" style="font-size: 11px; font-weight: 700; letter-spacing: 0.05em;">Status Pembayaran</label>
                        <div class="row g-2">
                            <div class="col-6">
                                <button type="button" class="payment-method-btn active" data-paid="1">Langsung Bayar</button>
                            </div>
                            <div class="col-6">
                                <button type="button" class="payment-method-btn" data-paid="0">Belum Bayar</button>
                            </div>
                        </div>
                    </div>

                    <!-- Totals -->
                    <div class="d-flex flex-column gap-1 mt-1">
                        <div class="d-flex justify-content-between text-muted" style="font-size: 12px;">
                            <span>Subtotal</span>
                            <span id="subtotal-display">Rp 0</span>
                        </div>
                        <div class="d-flex justify-content-between text-primary fw-bold" style="font-size: 24px; letter-spacing: -0.01em;">
                            <span>Total</span>
                            <span id="total-display">Rp 0</span>
                        </div>
                    </div>

                    <!-- Action Buttons -->
                    <div class="d-flex flex-column gap-2 mt-2">
                        <button type="submit" class="btn btn-primary w-100 py-2 d-flex align-items-center justify-content-center gap-2" style="font-weight: 600; font-size: 13px;">
                            <i class="bi bi-printer"></i> Bayar & Simpan
                        </button>
                    </div>
                </div>
            </div>
        </form>
    </div>
</div>

<!-- Modal Tambah Pelanggan -->
<div class="modal fade" id="addCustomerModal" tabindex="-1" aria-labelledby="addCustomerModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 shadow" style="border-radius: 1rem;">
            <div class="modal-header border-bottom-0 pb-0">
                <h5 class="modal-title fw-bold text-primary" id="addCustomerModalLabel"><i class="bi bi-person-plus me-2"></i>Tambah Pelanggan Baru</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form id="formAddCustomer">
                    <div class="mb-3">
                        <label class="form-label fw-bold text-muted small text-uppercase">Nama Pelanggan</label>
                        <input type="text" class="form-control" name="name" id="new_customer_name" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-bold text-muted small text-uppercase">No. HP / WhatsApp</label>
                        <input type="text" class="form-control" name="phone" id="new_customer_phone" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-bold text-muted small text-uppercase">Alamat</label>
                        <textarea class="form-control" name="address" id="new_customer_address" rows="2" required></textarea>
                    </div>
                </form>
            </div>
            <div class="modal-footer border-top-0 pt-0">
                <button type="button" class="btn btn-light rounded-pill px-4" data-bs-dismiss="modal">Batal</button>
                <button type="button" class="btn btn-primary rounded-pill px-4" id="btnSaveCustomer">Simpan</button>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
<script>
    document.addEventListener("DOMContentLoaded", function() {
        // --- Toastr Logic (Fail-Safe) ---
        try {
            if (typeof toastr !== 'undefined') {
                toastr.options = {
                    "closeButton": true,
                    "progressBar": true,
                    "positionClass": "toast-top-right",
                    "timeOut": "3000"
                };

                let successMsg = `{{ session('success') ?? '' }}`;
                if (successMsg) toastr.success(successMsg);

                let errorMsg = `{{ session('error') ?? '' }}`;
                if (errorMsg) toastr.error(errorMsg);
            }
        } catch(e) {
            console.error("Toastr error: ", e);
        }

        // --- Auto Print Logic ---
        @if(session('print_url'))
            if (typeof Swal !== 'undefined') {
                Swal.fire({
                    title: 'Transaksi Berhasil!',
                    text: 'Apakah Anda ingin mencetak struk?',
                    icon: 'success',
                    showCancelButton: true,
                    confirmButtonColor: '#ab005a',
                    cancelButtonColor: '#6c757d',
                    confirmButtonText: '<i class="bi bi-printer"></i> Cetak Struk',
                    cancelButtonText: 'Tutup'
                }).then((result) => {
                    if (result.isConfirmed) {
                        window.open("{!! session('print_url') !!}", "CetakStruk", "width=400,height=600");
                    }
                });
            } else {
                setTimeout(() => {
                    window.open("{!! session('print_url') !!}", "CetakStruk", "width=400,height=600");
                }, 500);
            }
        @endif

        // --- Select2 Initialization ---
        try {
            if (typeof jQuery !== 'undefined' && typeof jQuery.fn.select2 !== 'undefined') {
                jQuery('#customer_id').select2({
                    theme: 'bootstrap-5',
                    width: '100%',
                    placeholder: '-- Pilih Pelanggan --'
                });
            } else {
                console.warn("Select2 or jQuery is not available.");
            }
        } catch (e) {
            console.error("Select2 initialization failed:", e);
        }

        // --- Handle Add Customer AJAX via Fetch ---
        const btnSaveCustomer = document.getElementById('btnSaveCustomer');
        if (btnSaveCustomer) {
            btnSaveCustomer.addEventListener('click', async function(e) {
                e.preventDefault();
                
                const nameInput = document.getElementById('new_customer_name');
                const phoneInput = document.getElementById('new_customer_phone');
                const addressInput = document.getElementById('new_customer_address');
                const outletSelect = document.querySelector('select[name="outlet_id"]');
                
                const name = nameInput ? nameInput.value : '';
                const phone = phoneInput ? phoneInput.value : '';
                const address = addressInput ? addressInput.value : '';
                const outlet_id = outletSelect ? outletSelect.value : '';
                
                // Retrieve CSRF token
                let csrfToken = '';
                const metaToken = document.querySelector('meta[name="csrf-token"]');
                if (metaToken) {
                    csrfToken = metaToken.getAttribute('content');
                } else {
                    const inputToken = document.querySelector('input[name="_token"]');
                    if (inputToken) csrfToken = inputToken.value;
                }

                if(!name || !phone || !address) {
                    if(typeof toastr !== 'undefined') toastr.warning('Harap lengkapi semua field pelanggan!');
                    else alert('Harap lengkapi semua field pelanggan!');
                    return;
                }
                
                const originalText = btnSaveCustomer.innerHTML;
                btnSaveCustomer.innerHTML = '<span class="spinner-border spinner-border-sm"></span> Menyimpan...';
                btnSaveCustomer.disabled = true;

                try {
                    const response = await fetch("{{ route('cashier.customer.store') }}", {
                        method: "POST",
                        headers: {
                            "Content-Type": "application/json",
                            "X-CSRF-TOKEN": csrfToken,
                            "Accept": "application/json"
                        },
                        body: JSON.stringify({
                            name: name,
                            phone: phone,
                            address: address,
                            outlet_id: outlet_id
                        })
                    });

                    const data = await response.json();

                    if (response.ok && data.success) {
                        if(typeof toastr !== 'undefined') toastr.success(data.message);
                        else alert(data.message);
                        
                        // Add to standard select
                        const selectEl = document.getElementById('customer_id');
                        if (selectEl) {
                            const newOption = new Option(data.customer.name + ' - ' + data.customer.phone, data.customer.id, true, true);
                            selectEl.add(newOption);
                            
                            // Trigger Select2 update if available
                            if (typeof jQuery !== 'undefined' && typeof jQuery.fn.select2 !== 'undefined') {
                                jQuery(selectEl).trigger('change');
                            }
                        }
                        
                        // Reset and close modal
                        const form = document.getElementById('formAddCustomer');
                        if (form) form.reset();
                        
                        if (typeof bootstrap !== 'undefined') {
                            const modalEl = document.getElementById('addCustomerModal');
                            const modal = bootstrap.Modal.getInstance(modalEl);
                            if (modal) modal.hide();
                        }
                    } else {
                        let msg = data.message || 'Terjadi kesalahan saat menyimpan data.';
                        if(typeof toastr !== 'undefined') toastr.error(msg);
                        else alert(msg);
                    }
                } catch (error) {
                    console.error("Error saving customer:", error);
                    if(typeof toastr !== 'undefined') toastr.error('Terjadi kesalahan koneksi.');
                    else alert('Terjadi kesalahan koneksi.');
                } finally {
                    btnSaveCustomer.innerHTML = originalText;
                    btnSaveCustomer.disabled = false;
                }
            });
        }
    });

    // --- POS Cart Logic (Vanilla JS) ---
    let cart = {};

    function formatRupiah(number) {
        return new Intl.NumberFormat('id-ID').format(number);
    }

    function updateCartUI() {
        const container = document.getElementById('cart-items-container');
        if (!container) return;
        
        container.innerHTML = '';
        let subtotal = 0;
        let itemIndex = 0;

        if (Object.keys(cart).length === 0) {
            container.innerHTML = `
                <div class="text-center text-muted my-4" id="empty-cart-msg">
                    <i class="bi bi-cart-x fs-2"></i>
                    <p class="mt-2 mb-0" style="font-size: 13px;">Keranjang masih kosong</p>
                </div>
            `;
        } else {
            for (let id in cart) {
                let item = cart[id];
                let itemTotal = item.price * item.quantity;
                subtotal += itemTotal;

                container.insertAdjacentHTML('beforeend', `
                    <div class="d-flex justify-content-between align-items-start mb-2">
                        <!-- Hidden inputs -->
                        <input type="hidden" name="items[${itemIndex}][product_id]" value="${item.id}">
                        <input type="hidden" name="items[${itemIndex}][quantity]" value="${item.quantity}">
                        
                        <div class="d-flex flex-column">
                            <span class="fw-semibold text-dark" style="font-size: 13px;">${item.name}</span>
                            <span class="text-muted" style="font-size: 12px;">Rp ${formatRupiah(item.price)} ${item.type == 'Kiloan' ? '/ kg' : '/ pcs'}</span>
                        </div>
                        <div class="d-flex flex-column align-items-end gap-1">
                            <div class="qty-control d-flex align-items-center">
                                <button type="button" class="qty-btn btn-minus border-0 bg-light" data-id="${item.id}"><i class="bi bi-dash"></i></button>
                                <input type="number" class="qty-input mx-1 text-center border-0" data-id="${item.id}" value="${item.quantity}" min="0.1" step="0.1" style="width:40px; font-size:13px;">
                                <button type="button" class="qty-btn btn-plus border-0 bg-light" data-id="${item.id}"><i class="bi bi-plus"></i></button>
                            </div>
                            <span class="fw-semibold text-dark" style="font-size: 13px;">Rp ${formatRupiah(itemTotal)}</span>
                        </div>
                    </div>
                `);
                itemIndex++;
            }
        }

        // Update Totals
        const subtotalDisplay = document.getElementById('subtotal-display');
        const totalDisplay = document.getElementById('total-display');
        if(subtotalDisplay) subtotalDisplay.textContent = 'Rp ' + formatRupiah(subtotal);
        if(totalDisplay) totalDisplay.textContent = 'Rp ' + formatRupiah(subtotal);
    }

    document.addEventListener("DOMContentLoaded", function() {
        // --- Add to Cart ---
        document.querySelectorAll('.add-to-cart').forEach(button => {
            button.addEventListener('click', function(e) {
                e.preventDefault();
                let id = this.getAttribute('data-id');
                let name = this.getAttribute('data-name');
                let price = parseFloat(this.getAttribute('data-price'));
                let type = this.getAttribute('data-type');
                
                let step = type === 'Kiloan' ? 1 : 1; 

                if (cart[id]) {
                    cart[id].quantity += step;
                } else {
                    cart[id] = { id: id, name: name, price: price, type: type, quantity: step };
                }

                updateCartUI();
                
                // Visual feedback
                let icon = this.querySelector('.icon-wrapper');
                if (icon) {
                    icon.style.transform = 'scale(1.2)';
                    setTimeout(() => icon.style.transform = 'scale(1)', 200);
                }
            });
        });

        // --- Qty Controls (Event Delegation) ---
        document.getElementById('cart-items-container').addEventListener('click', function(e) {
            let target = e.target.closest('button');
            if (!target) return;

            let id = target.getAttribute('data-id');
            if (target.classList.contains('btn-plus')) {
                if (cart[id]) {
                    cart[id].quantity += 1;
                    updateCartUI();
                }
            } else if (target.classList.contains('btn-minus')) {
                if (cart[id]) {
                    cart[id].quantity -= 1;
                    if (cart[id].quantity <= 0) delete cart[id];
                    updateCartUI();
                }
            }
        });

        // Handle manual typing of quantity
        document.getElementById('cart-items-container').addEventListener('change', function(e) {
            if (e.target.classList.contains('qty-input')) {
                let id = e.target.getAttribute('data-id');
                let newQty = parseFloat(e.target.value);
                
                if (cart[id]) {
                    if (newQty > 0) {
                        cart[id].quantity = newQty;
                    } else {
                        delete cart[id];
                    }
                    updateCartUI();
                }
            }
        });

        // --- Category Filters ---
        document.querySelectorAll('.filter-btn').forEach(btn => {
            btn.addEventListener('click', function() {
                // Update Active Styling
                document.querySelectorAll('.filter-btn').forEach(b => {
                    b.classList.remove('bg-primary', 'text-white');
                    b.classList.add('bg-light', 'text-dark');
                });
                this.classList.remove('bg-light', 'text-dark');
                this.classList.add('bg-primary', 'text-white');

                // Perform Filtering
                let filterType = this.getAttribute('data-filter');
                document.querySelectorAll('.product-item').forEach(item => {
                    if (filterType === 'all' || item.getAttribute('data-type') === filterType) {
                        item.style.display = 'block';
                    } else {
                        item.style.display = 'none';
                    }
                });
            });
        });

        // --- Toggle Notes ---
        const btnToggleNotes = document.getElementById('btn-toggle-notes');
        if (btnToggleNotes) {
            btnToggleNotes.addEventListener('click', function() {
                const notesContainer = document.getElementById('notes-container');
                notesContainer.classList.toggle('d-none');
                
                if (!notesContainer.classList.contains('d-none')) {
                    document.querySelector('textarea[name="notes"]').focus();
                    this.innerHTML = '<i class="bi bi-dash-circle"></i> Sembunyikan Catatan';
                } else {
                    document.querySelector('textarea[name="notes"]').value = ''; 
                    this.innerHTML = '<i class="bi bi-plus-circle"></i> Tambah Catatan';
                }
            });
        }

        // --- Payment Status Toggle ---
        document.querySelectorAll('.payment-method-btn').forEach(btn => {
            btn.addEventListener('click', function() {
                document.querySelectorAll('.payment-method-btn').forEach(b => b.classList.remove('active'));
                this.classList.add('active');
                document.getElementById('is_paid').value = this.getAttribute('data-paid');
            });
        });

        // --- Form Validation before Submit ---
        const posForm = document.getElementById('pos-form');
        if (posForm) {
            posForm.addEventListener('submit', function(e) {
                e.preventDefault(); 

                if (Object.keys(cart).length === 0) {
                    if (typeof Swal !== 'undefined') {
                        Swal.fire({
                            icon: 'warning',
                            title: 'Keranjang Kosong',
                            text: 'Pilih minimal satu layanan atau produk sebelum menyimpan transaksi!',
                            confirmButtonColor: '#ab005a'
                        });
                    } else {
                        alert('Pilih minimal satu layanan atau produk sebelum menyimpan transaksi!');
                    }
                    return false;
                }

                let customerId = document.querySelector('select[name="customer_id"]').value;
                if(!customerId) {
                    if (typeof toastr !== 'undefined') {
                        toastr.warning('Silakan pilih pelanggan terlebih dahulu!');
                    } else {
                        alert('Silakan pilih pelanggan terlebih dahulu!');
                    }
                    return false;
                }

                if (typeof Swal !== 'undefined') {
                    Swal.fire({
                        title: 'Konfirmasi Pesanan',
                        text: "Apakah Anda yakin data pesanan sudah benar?",
                        icon: 'question',
                        showCancelButton: true,
                        confirmButtonColor: '#ab005a',
                        cancelButtonColor: '#6c757d',
                        confirmButtonText: 'Ya, Simpan!',
                        cancelButtonText: 'Periksa Lagi'
                    }).then((result) => {
                        if (result.isConfirmed) {
                            let btnSubmit = this.querySelector('button[type="submit"]');
                            btnSubmit.innerHTML = '<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span> Menyimpan...';
                            btnSubmit.disabled = true;
                            this.submit();
                        }
                    });
                } else {
                    if(confirm("Apakah Anda yakin data pesanan sudah benar?")) {
                        this.submit();
                    }
                }
            });
        }
    });
</script>
