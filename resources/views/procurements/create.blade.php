@extends('layouts.dashboard')

@section('content')
<div class="page-heading mb-4">
    <nav aria-label="breadcrumb">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
            <li class="breadcrumb-item"><a href="{{ route('procurements.index') }}">Procurements</a></li>
            <li class="breadcrumb-item active" aria-current="page">New Purchase Order</li>
        </ol>
    </nav>
    <h3>New Purchase Order</h3>
</div>

<section class="section">
    <form action="{{ route('procurements.store') }}" method="POST" id="procurement-form">
        @csrf
        <div class="row">
            <div class="col-md-4">
                <div class="card">
                    <div class="card-header">
                        <h4>Order Information</h4>
                    </div>
                    <div class="card-body">
                        <div class="mb-3">
                            <label for="vendor_id" class="form-label d-flex justify-content-between">
                                <span>Vendor <span class="text-danger">*</span></span>
                                <button type="button" class="btn btn-sm btn-link p-0 text-decoration-none" data-bs-toggle="modal" data-bs-target="#addVendorModal">
                                    <i class="bi bi-plus-circle"></i> Add New Vendor
                                </button>
                            </label>
                            <select name="vendor_id" id="vendor_id" class="form-select" required>
                                <option value="">-- Select Vendor --</option>
                                @foreach($vendors as $vendor)
                                    <option value="{{ $vendor->id }}">{{ $vendor->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="mb-3">
                            <label for="order_date" class="form-label">Order Date <span class="text-danger">*</span></label>
                            <input type="date" name="order_date" id="order_date" class="form-control" value="{{ date('Y-m-d') }}" required>
                        </div>
                        <div class="mb-3">
                            <label for="notes" class="form-label">Notes</label>
                            <textarea name="notes" id="notes" class="form-control" rows="3"></textarea>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-md-8">
                <div class="card">
                    <div class="card-header d-flex justify-content-between align-items-center">
                        <h4>Items</h4>
                        <button type="button" class="btn btn-sm btn-outline-primary" id="add-item">
                            <i class="bi bi-plus"></i> Add Item
                        </button>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-bordered" id="items-table">
                                <thead>
                                    <tr>
                                        <th>Inventory Item</th>
                                        <th style="width: 120px;">Qty</th>
                                        <th style="width: 180px;">Unit Price</th>
                                        <th style="width: 180px;">Subtotal</th>
                                        <th style="width: 50px;"></th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr class="item-row">
                                        <td>
                                            <select name="items[0][inventory_id]" class="form-select inventory-select" required>
                                                <option value="">-- Select Item --</option>
                                                @foreach($inventories as $inv)
                                                    <option value="{{ $inv->id }}">{{ $inv->name }} (Current: {{ $inv->quantity }})</option>
                                                @endforeach
                                            </select>
                                        </td>
                                        <td>
                                            <input type="number" name="items[0][quantity]" class="form-control qty-input" min="1" value="1" required>
                                        </td>
                                        <td>
                                            <input type="number" name="items[0][unit_price]" class="form-control price-input" min="0" step="0.01" required>
                                        </td>
                                        <td>
                                            <input type="text" class="form-control subtotal-input" readonly value="0">
                                        </td>
                                        <td></td>
                                    </tr>
                                </tbody>
                                <tfoot>
                                    <tr>
                                        <th colspan="3" class="text-end">Total Amount</th>
                                        <th>
                                            <input type="text" id="total_amount_display" class="form-control fw-bold" readonly value="0">
                                            <input type="hidden" name="total_amount" id="total_amount_hidden" value="0">
                                        </th>
                                        <th></th>
                                    </tr>
                                </tfoot>
                            </table>
                        </div>

                        <div class="mt-4">
                            <button type="submit" class="btn btn-primary w-100">Create Purchase Order</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </form>
</section>

<!-- Add Vendor Modal -->
<div class="modal fade" id="addVendorModal" tabindex="-1" aria-labelledby="addVendorModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="addVendorModalLabel">Add New Vendor</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form id="add-vendor-form">
                    @csrf
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="modal_vendor_name" class="form-label">Vendor Name <span class="text-danger">*</span></label>
                            <input type="text" name="name" id="modal_vendor_name" class="form-control" required>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="modal_contact_person" class="form-label">Contact Person</label>
                            <input type="text" name="contact_person" id="modal_contact_person" class="form-control">
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="modal_email" class="form-label">Email</label>
                            <input type="email" name="email" id="modal_email" class="form-control">
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="modal_phone" class="form-label">Phone</label>
                            <input type="text" name="phone" id="modal_phone" class="form-control">
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="modal_status" class="form-label">Status <span class="text-danger">*</span></label>
                            <select name="status" id="modal_status" class="form-select" required>
                                <option value="active">Active</option>
                                <option value="inactive">Inactive</option>
                            </select>
                        </div>
                        <div class="col-12 mb-3">
                            <label for="modal_address" class="form-label">Address</label>
                            <textarea name="address" id="modal_address" class="form-control" rows="3"></textarea>
                        </div>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                <button type="button" class="btn btn-primary" id="save-vendor-btn">Save Vendor</button>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
    let itemIndex = 1;

    $('#add-item').click(function() {
        const row = `
            <tr class="item-row">
                <td>
                    <select name="items[${itemIndex}][inventory_id]" class="form-select inventory-select" required>
                        <option value="">-- Select Item --</option>
                        @foreach($inventories as $inv)
                            <option value="{{ $inv->id }}">{{ $inv->name }} (Current: {{ $inv->quantity }})</option>
                        @endforeach
                    </select>
                </td>
                <td>
                    <input type="number" name="items[${itemIndex}][quantity]" class="form-control qty-input" min="1" value="1" required>
                </td>
                <td>
                    <input type="number" name="items[${itemIndex}][unit_price]" class="form-control price-input" min="0" step="0.01" required>
                </td>
                <td>
                    <input type="text" class="form-control subtotal-input" readonly value="0">
                </td>
                <td>
                    <button type="button" class="btn btn-sm btn-outline-danger btn-remove-row"><i class="bi bi-trash"></i></button>
                </td>
            </tr>
        `;
        $('#items-table tbody').append(row);
        itemIndex++;
    });

    $(document).on('click', '.btn-remove-row', function() {
        $(this).closest('tr').remove();
        calculateTotal();
    });

    $(document).on('input', '.qty-input, .price-input', function() {
        const row = $(this).closest('tr');
        const qty = parseFloat(row.find('.qty-input').val()) || 0;
        const price = parseFloat(row.find('.price-input').val()) || 0;
        const subtotal = qty * price;
        row.find('.subtotal-input').val(subtotal.toLocaleString('id-ID'));
        calculateTotal();
    });

    function calculateTotal() {
        let total = 0;
        $('.item-row').each(function() {
            const qty = parseFloat($(this).find('.qty-input').val()) || 0;
            const price = parseFloat($(this).find('.price-input').val()) || 0;
            total += qty * price;
        });
        $('#total_amount_display').val(total.toLocaleString('id-ID'));
        $('#total_amount_hidden').val(total);
    }

    // Add Vendor via AJAX
    $('#save-vendor-btn').click(function() {
        const btn = $(this);
        const form = $('#add-vendor-form');
        const modal = bootstrap.Modal.getInstance(document.getElementById('addVendorModal'));
        
        // Basic validation
        if (!document.getElementById('modal_vendor_name').value) {
            alert('Vendor Name is required');
            return;
        }

        btn.prop('disabled', true).html('<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span> Saving...');

        $.ajax({
            url: "{{ route('vendors.store') }}",
            type: "POST",
            data: form.serialize(),
            success: function(response) {
                if (response.success) {
                    // Update dropdown
                    const newOption = new Option(response.data.name, response.data.id, true, true);
                    $('#vendor_id').append(newOption).trigger('change');
                    
                    // Reset form and close modal
                    form[0].reset();
                    modal.hide();
                    
                    // Optional: Show success message (using your dashboard's alert system if available)
                    toast('Success', response.message, 'success');
                }
            },
            error: function(xhr) {
                const errors = xhr.responseJSON.errors;
                let errorMessage = 'Failed to save vendor.\n';
                if (errors) {
                    Object.keys(errors).forEach(key => {
                        errorMessage += `- ${errors[key][0]}\n`;
                    });
                }
                alert(errorMessage);
            },
            complete: function() {
                btn.prop('disabled', false).text('Save Vendor');
            }
        });
    });

    // Helper for toast if available, fallback to alert
    function toast(title, message, type) {
        if (typeof Swal !== 'undefined') {
            Swal.fire(title, message, type);
        } else {
            alert(message);
        }
    }
</script>
@endpush
@endsection
