/**
 * Finance Transaction Create — UI Logic
 * All JS for create.blade.php is centralized here.
 * Global vars injected from Blade: window.FINANCE_TRX_CONFIG
 */

/* ══════════════════════════════════════════════════════
   1. NOMINAL MASKING — safe raw state pattern
   ══════════════════════════════════════════════════════ */

let rawAmount = 0; // single source of truth

function formatRupiah(num) {
    if (!num || isNaN(num)) return '';
    return Number(num).toLocaleString('id-ID');
}

function parseRawNumber(str) {
    // Strip semua non-digit (handle copy-paste "1.000.000" or "Rp 1,000")
    const cleaned = String(str).replace(/[^\d]/g, '');
    return parseInt(cleaned, 10) || 0;
}

function terbilang(n) {
    const satuan = ['', 'satu', 'dua', 'tiga', 'empat', 'lima',
                    'enam', 'tujuh', 'delapan', 'sembilan', 'sepuluh',
                    'sebelas'];
    n = Math.abs(Math.floor(n));
    if (n < 12) return satuan[n];
    if (n < 20) return satuan[n - 10] + ' belas';
    if (n < 100) return satuan[Math.floor(n / 10)] + ' puluh' + (n % 10 ? ' ' + satuan[n % 10] : '');
    if (n < 200) return 'seratus' + (n % 100 ? ' ' + terbilang(n % 100) : '');
    if (n < 1000) return satuan[Math.floor(n / 100)] + ' ratus' + (n % 100 ? ' ' + terbilang(n % 100) : '');
    if (n < 2000) return 'seribu' + (n % 1000 ? ' ' + terbilang(n % 1000) : '');
    if (n < 1000000) return terbilang(Math.floor(n / 1000)) + ' ribu' + (n % 1000 ? ' ' + terbilang(n % 1000) : '');
    if (n < 1000000000) return terbilang(Math.floor(n / 1000000)) + ' juta' + (n % 1000000 ? ' ' + terbilang(n % 1000000) : '');
    if (n < 1000000000000) return terbilang(Math.floor(n / 1000000000)) + ' miliar' + (n % 1000000000 ? ' ' + terbilang(n % 1000000000) : '');
    return 'lebih';
}

function initNominalInput() {
    const display = document.getElementById('amount_display');
    const hidden  = document.getElementById('amount');
    const helper  = document.getElementById('terbilang_helper');
    if (!display || !hidden) return;

    display.addEventListener('input', () => {
        // Save cursor position before formatting to restore after
        const cursorPos = display.selectionStart;
        const oldLen    = display.value.length;

        rawAmount = parseRawNumber(display.value);
        hidden.value = rawAmount || '';

        const formatted = rawAmount > 0 ? formatRupiah(rawAmount) : '';
        display.value = formatted;

        // Restore cursor (adjust for length change)
        const newLen = display.value.length;
        const diff   = newLen - oldLen;
        display.setSelectionRange(cursorPos + diff, cursorPos + diff);

        // Update terbilang helper
        if (helper) {
            helper.textContent = rawAmount > 0
                ? 'Terbilang: ' + terbilang(rawAmount) + ' rupiah'
                : '';
        }

        // Update preview color
        updateAmountColor();

        // Recalc tax if active
        recalcTax();
    });

    // Prefill if old() value exists
    if (hidden.value && parseInt(hidden.value) > 0) {
        rawAmount = parseInt(hidden.value);
        display.value = formatRupiah(rawAmount);
        if (helper) helper.textContent = 'Terbilang: ' + terbilang(rawAmount) + ' rupiah';
    }
}

/* ══════════════════════════════════════════════════════
   2. DEBIT / KREDIT SEGMENTED CONTROL
   ══════════════════════════════════════════════════════ */

function initSegmentedControl() {
    const radios = document.querySelectorAll('input[name="transaction_type"]');
    radios.forEach(r => {
        r.addEventListener('change', () => {
            updateSegmentedUI();
            updateAmountColor();
        });
    });
    updateSegmentedUI();
}

function updateSegmentedUI() {
    const selected = document.querySelector('input[name="transaction_type"]:checked');
    document.querySelectorAll('.seg-card').forEach(card => {
        card.classList.remove('seg-active-debit', 'seg-active-kredit');
    });
    if (!selected) return;
    const label = document.querySelector(`label[for="${selected.id}"]`);
    if (label) {
        label.classList.add(selected.value === 'debit' ? 'seg-active-debit' : 'seg-active-kredit');
    }
}

function updateAmountColor() {
    const display  = document.getElementById('amount_display');
    const selected = document.querySelector('input[name="transaction_type"]:checked');
    if (!display || !selected) return;
    display.classList.remove('nominal-debit', 'nominal-kredit');
    display.classList.add('nominal-' + selected.value);
}

/* ══════════════════════════════════════════════════════
   3. TAX SECTION COLLAPSIBLE
   ══════════════════════════════════════════════════════ */

function initTaxCollapse() {
    const trigger = document.getElementById('taxCollapseBtn');
    const section = document.getElementById('taxSection');
    if (!trigger || !section) return;

    // Restore state if old() values exist
    const taxType = document.getElementById('tax_type');
    const dppAmt  = document.getElementById('dpp_amount');
    if ((taxType && taxType.value !== 'none') || (dppAmt && parseFloat(dppAmt.value) > 0)) {
        section.style.display = 'block';
        updateTaxBadge();
    }
}

function toggleTax() {
    const section = document.getElementById('taxSection');
    const icon    = document.getElementById('taxCollapseIcon');
    if (!section) return;
    const isOpen = section.style.display !== 'none';
    section.style.display = isOpen ? 'none' : 'block';
    if (icon) icon.textContent = isOpen ? '▸' : '▾';
}

function updateTaxBadge() {
    const badge   = document.getElementById('taxBadge');
    const taxType = document.getElementById('tax_type');
    const dppAmt  = document.getElementById('dpp_amount');
    if (!badge) return;
    const hasTax = (taxType && taxType.value !== 'none') || (dppAmt && parseFloat(dppAmt.value) > 0);
    badge.style.display = hasTax ? 'inline-block' : 'none';
}

/* ══════════════════════════════════════════════════════
   4. TAX CALCULATOR (unchanged logic, safe version)
   ══════════════════════════════════════════════════════ */

const TAX_RATES       = { ppn: 0.11, pph_21: 0.05, pph_23: 0.02, pph_4_ayat_2: 0.10 };
const DEDUCTION_TYPES = ['pph_21', 'pph_23', 'pph_4_ayat_2'];

function recalcTax() {
    const dpp    = parseFloat(document.getElementById('dpp_amount')?.value) || 0;
    const type   = document.getElementById('tax_type')?.value;
    const taxEl  = document.getElementById('tax_amount');
    const hidden = document.getElementById('amount');
    const display = document.getElementById('amount_display');

    if (!type || type === 'none') {
        if (taxEl) { taxEl.value = ''; taxEl.placeholder = 'Tidak ada pajak'; }
        updateTaxBadge();
        return;
    }
    if (dpp === 0) {
        if (taxEl) { taxEl.value = ''; taxEl.placeholder = 'Isi DPP terlebih dahulu'; }
        updateTaxBadge();
        return;
    }

    const rate = TAX_RATES[type] || 0;
    const tax  = Math.round(dpp * rate);
    if (taxEl) taxEl.value = tax;

    // Only override amount when BOTH dpp & tax_type filled
    const totalAmt = DEDUCTION_TYPES.includes(type) ? dpp - tax : dpp + tax;
    rawAmount = totalAmt;
    if (hidden) hidden.value = totalAmt;
    if (display) display.value = formatRupiah(totalAmt);

    const helper = document.getElementById('terbilang_helper');
    if (helper) helper.textContent = totalAmt > 0 ? 'Terbilang: ' + terbilang(totalAmt) + ' rupiah' : '';

    updateTaxBadge();
}

/* ══════════════════════════════════════════════════════
   5. LOADING + DOUBLE SUBMIT PREVENTION
   ══════════════════════════════════════════════════════ */

function initSubmitProtection() {
    const form   = document.getElementById('trxForm');
    const btn    = document.getElementById('submitBtn');
    if (!form || !btn) return;

    form.addEventListener('submit', (e) => {
        // Validate nominal > 0
        if (!rawAmount || rawAmount <= 0) {
            e.preventDefault();
            showFieldError('amount_display', 'Nominal harus lebih dari 0');
            document.getElementById('amount_display')?.focus();
            return;
        }

        btn.disabled = true;
        btn.innerHTML = '<span class="spinner-border spinner-border-sm me-2" style="width:.85rem;height:.85rem;border-width:2px"></span> Menyimpan...';

        // Reset dirty flag so beforeunload doesn't fire
        isDirty = false;
    });
}

/* ══════════════════════════════════════════════════════
   6. DIRTY FLAG — prevent accidental navigation
   ══════════════════════════════════════════════════════ */

let isDirty = false;

function initDirtyFlag() {
    document.querySelectorAll('#trxForm input, #trxForm select, #trxForm textarea')
        .forEach(el => el.addEventListener('change', () => isDirty = true));
    document.getElementById('amount_display')
        ?.addEventListener('input', () => isDirty = true);

    window.onbeforeunload = (e) => {
        if (isDirty) {
            e.preventDefault();
            return 'Data belum disimpan. Yakin ingin meninggalkan halaman ini?';
        }
    };

    // Batal button: bypass dirty check gracefully
    document.getElementById('btnBatal')?.addEventListener('click', () => {
        isDirty = false;
    });
}

/* ══════════════════════════════════════════════════════
   7. INLINE ERROR HELPERS
   ══════════════════════════════════════════════════════ */

function showFieldError(fieldId, message) {
    clearFieldError(fieldId);
    const field = document.getElementById(fieldId);
    if (!field) return;
    field.classList.add('fc-error');
    const err = document.createElement('p');
    err.className = 'fc-field-error';
    err.id = fieldId + '_err';
    err.textContent = message;
    field.parentNode.insertBefore(err, field.nextSibling);
}

function clearFieldError(fieldId) {
    const field   = document.getElementById(fieldId);
    const errEl   = document.getElementById(fieldId + '_err');
    if (field) field.classList.remove('fc-error');
    if (errEl) errEl.remove();
}

/* ══════════════════════════════════════════════════════
   8. FILE UPLOAD DISPLAY
   ══════════════════════════════════════════════════════ */

function showFileName(input) {
    const nameEl = document.getElementById('uploadName');
    const zone   = document.getElementById('uploadZone');
    if (input.files && input.files[0]) {
        if (nameEl) {
            nameEl.innerHTML = '<i class="bi bi-file-earmark-check"></i> ' + input.files[0].name;
            nameEl.style.display = 'inline-flex';
        }
        if (zone) {
            zone.style.borderColor = '#2d6a4f';
            zone.style.background  = '#eef7f2';
        }
    }
}

function initUploadZone() {
    const zone = document.getElementById('uploadZone');
    if (!zone) return;
    zone.addEventListener('dragover', e => { e.preventDefault(); zone.classList.add('drag-over'); });
    zone.addEventListener('dragleave', () => zone.classList.remove('drag-over'));
    zone.addEventListener('drop', e => {
        e.preventDefault();
        zone.classList.remove('drag-over');
        const fi = document.getElementById('document');
        if (fi) { fi.files = e.dataTransfer.files; showFileName(fi); }
    });
}

/* ══════════════════════════════════════════════════════
   9. QUICK ADD ENTITY (AJAX — unchanged logic)
   ══════════════════════════════════════════════════════ */

let quickEntityModal = null;
let quickAccModal    = null;

function initModals() {
    const entityModalEl = document.getElementById('quickEntityModal');
    const accModalEl    = document.getElementById('quickAccountModal');
    if (entityModalEl) quickEntityModal = new bootstrap.Modal(entityModalEl);
    if (accModalEl)    quickAccModal    = new bootstrap.Modal(accModalEl);
}

function openEntityModal(target) {
    document.getElementById('target_dropdown').value = target;
    document.getElementById('quickEntityForm')?.reset();
    quickEntityModal?.show();
}

function openAccountModal() {
    document.getElementById('quickAccountForm')?.reset();
    quickAccModal?.show();
}

function initQuickEntityForm() {
    const form = document.getElementById('quickEntityForm');
    if (!form) return;
    form.addEventListener('submit', function(e) {
        e.preventDefault();
        const btn    = document.getElementById('entitySubmitBtn');
        const target = document.getElementById('target_dropdown').value;
        const origHTML = btn.innerHTML;
        btn.disabled = true;
        btn.innerHTML = '<span class="spinner-border spinner-border-sm me-1" style="width:.85rem;height:.85rem;border-width:2px"></span> Menyimpan...';

        fetch(window.FINANCE_TRX_CONFIG.entityStoreUrl, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-Requested-With': 'XMLHttpRequest',
                'X-CSRF-TOKEN': window.FINANCE_TRX_CONFIG.csrf
            },
            body: JSON.stringify({
                name: document.getElementById('entity_name').value,
                type: document.getElementById('entity_type').value,
                contact_info: document.getElementById('entity_contact').value
            })
        })
        .then(r => r.json())
        .then(result => {
            if (result.success) {
                const opt = new Option(`${result.data.name} (${result.data.type})`, result.data.id);
                document.getElementById('sender_entity_id')?.add(opt.cloneNode(true));
                document.getElementById('receiver_entity_id')?.add(opt.cloneNode(true));
                document.getElementById(`${target}_entity_id`).value = result.data.id;
                quickEntityModal?.hide();
                showToast('success', 'Entitas Ditambahkan', `"${result.data.name}" berhasil disimpan.`);
            } else {
                showToast('error', 'Gagal Menyimpan', result.message || 'Terjadi kesalahan.');
            }
        })
        .catch(() => showToast('error', 'Koneksi Gagal', 'Tidak dapat terhubung ke server.'))
        .finally(() => { btn.disabled = false; btn.innerHTML = origHTML; });
    });
}

function initQuickAccountForm() {
    const form = document.getElementById('quickAccountForm');
    if (!form) return;
    form.addEventListener('submit', function(e) {
        e.preventDefault();
        const btn = document.getElementById('accountSubmitBtn');
        const origHTML = btn.innerHTML;
        btn.disabled = true;
        btn.innerHTML = '<span class="spinner-border spinner-border-sm me-1" style="width:.85rem;height:.85rem;border-width:2px"></span> Menyimpan...';

        fetch(window.FINANCE_TRX_CONFIG.accountStoreUrl, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-Requested-With': 'XMLHttpRequest',
                'Accept': 'application/json',
                'X-CSRF-TOKEN': window.FINANCE_TRX_CONFIG.csrf
            },
            body: JSON.stringify({
                code: document.getElementById('account_code').value,
                name: document.getElementById('account_name').value,
                category: document.getElementById('account_category').value
            })
        })
        .then(async r => { const j = await r.json(); if (!r.ok) throw j; return j; })
        .then(result => {
            if (result.success) {
                const opt = new Option(`[${result.data.code}] ${result.data.name}`, result.data.id);
                const sel = document.getElementById('account_id');
                if (sel) { sel.appendChild(opt); sel.value = result.data.id; }
                // Refresh Tom Select if active
                if (window._tomSelectAccount) {
                    window._tomSelectAccount.addOption({ value: result.data.id, text: `[${result.data.code}] ${result.data.name}` });
                    window._tomSelectAccount.setValue(result.data.id);
                }
                quickAccModal?.hide();
                showToast('success', 'Akun Ditambahkan', `"${result.data.name}" berhasil disimpan.`);
            }
        })
        .catch(err => {
            let msg = 'Tidak dapat terhubung ke server.';
            if (err.errors) msg = Object.values(err.errors)[0][0];
            else if (err.message) msg = err.message;
            showToast('error', 'Gagal Menyimpan', msg);
        })
        .finally(() => { btn.disabled = false; btn.innerHTML = origHTML; });
    });
}

/* ══════════════════════════════════════════════════════
   10. TOM SELECT INIT
   ══════════════════════════════════════════════════════ */

function initTomSelect() {
    const el = document.getElementById('account_id');
    if (!el || typeof TomSelect === 'undefined') return;
    window._tomSelectAccount = new TomSelect(el, {
        searchField: ['text'],
        placeholder: 'Cari kode atau nama akun...',
        create: false,
        maxOptions: 100,
        render: {
            no_results: () => `
                <div class="ts-no-results">
                    Akun tidak ditemukan
                    <button type="button" class="ts-add-btn" onclick="openAccountModal()">+ Tambah Akun Baru</button>
                </div>`
        }
    });
}

/* ══════════════════════════════════════════════════════
   11. TOAST NOTIFICATION (unchanged)
   ══════════════════════════════════════════════════════ */

function showToast(type, title, message, duration = 4000) {
    const icons = { success: '<i class="bi bi-check-lg"></i>', error: '<i class="bi bi-x-lg"></i>', info: '<i class="bi bi-info-lg"></i>' };
    const toast = document.createElement('div');
    toast.className = 'ef-toast';
    toast.innerHTML = `
        <div class="ef-toast-icon ${type}">${icons[type] || icons.info}</div>
        <div class="ef-toast-body">
            <p class="ef-toast-title">${title}</p>
            <p class="ef-toast-msg">${message}</p>
        </div>
        <button class="ef-toast-close" onclick="this.closest('.ef-toast').remove()">
            <i class="bi bi-x"></i>
        </button>`;
    const container = document.getElementById('ef-toast-container');
    if (container) container.appendChild(toast);
    requestAnimationFrame(() => requestAnimationFrame(() => toast.classList.add('show')));
    setTimeout(() => {
        toast.classList.replace('show', 'hide');
        setTimeout(() => toast.remove(), 300);
    }, duration);
}

/* ══════════════════════════════════════════════════════
   12. TEXTAREA CHAR COUNTER
   ══════════════════════════════════════════════════════ */

function initCharCounter() {
    const ta      = document.getElementById('description');
    const counter = document.getElementById('desc_counter');
    if (!ta || !counter) return;
    const max = 500;
    function update() {
        const left = max - ta.value.length;
        counter.textContent = `${ta.value.length} / ${max}`;
        counter.style.color = left < 50 ? '#dc2626' : '#9ca3af';
    }
    ta.addEventListener('input', update);
    update();
}

/* ══════════════════════════════════════════════════════
   INIT — DOMContentLoaded
   ══════════════════════════════════════════════════════ */

document.addEventListener('DOMContentLoaded', () => {
    initSegmentedControl();
    initNominalInput();
    initTaxCollapse();
    initSubmitProtection();
    initDirtyFlag();
    initUploadZone();
    initModals();
    initQuickEntityForm();
    initQuickAccountForm();
    initTomSelect();
    initCharCounter();

    // Autofocus nominal
    setTimeout(() => document.getElementById('amount_display')?.focus(), 100);

    // Keyboard: Enter on nominal -> jump to account
    document.getElementById('amount_display')?.addEventListener('keydown', e => {
        if (e.key === 'Enter') {
            e.preventDefault();
            document.getElementById('account_id')?.focus();
        }
    });
});
