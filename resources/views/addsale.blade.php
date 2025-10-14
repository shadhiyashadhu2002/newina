@extends('layouts.app')

@section('content')
<style>
    * {
        margin: 0;
        padding: 0;
        box-sizing: border-box;
    }

    body {
        font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif;
        background: linear-gradient(135deg, #ac0742 0%, #9d1955 100%);
        min-height: 100vh;
    }

    .form-container {
        max-width: 900px;
        margin: 40px auto;
        background: rgba(255, 255, 255, 0.95);
        border-radius: 15px;
        padding: 35px;
        box-shadow: 0 10px 40px rgba(0, 0, 0, 0.3);
        backdrop-filter: blur(10px);
        border: 1px solid rgba(255, 255, 255, 0.3);
    }

    .form-header {
        display: flex;
        align-items: center;
        gap: 15px;
        margin-bottom: 30px;
        padding-bottom: 20px;
        border-bottom: 3px solid #ac0742;
    }

    .form-icon {
        width: 50px;
        height: 50px;
        background: linear-gradient(135deg, #ac0742, #9d1955);
        border-radius: 12px;
        display: flex;
        align-items: center;
        justify-content: center;
        color: white;
        font-size: 24px;
    }

    .form-title {
        font-size: 28px;
        font-weight: 700;
        color: #2c3e50;
    }

    .form-grid {
        display: grid;
        grid-template-columns: repeat(2, 1fr);
        gap: 25px;
        margin-bottom: 25px;
    }

    .form-group {
        display: flex;
        flex-direction: column;
    }

    .form-group.full-width {
        grid-column: 1 / -1;
    }

    .form-label {
        font-weight: 600;
        color: #2c3e50;
        margin-bottom: 8px;
        font-size: 14px;
        text-transform: uppercase;
        letter-spacing: 0.5px;
    }

    .form-label .required {
        color: #ac0742;
        margin-left: 3px;
    }

    .form-input,
    .form-select,
    .form-textarea {
        padding: 12px 15px;
        border: 2px solid #e0e0e0;
        border-radius: 8px;
        font-size: 15px;
        transition: all 0.3s ease;
        background: white;
        color: #333;
    }

    .form-input:focus,
    .form-select:focus,
    .form-textarea:focus {
        outline: none;
        border-color: #ac0742;
        box-shadow: 0 0 0 3px rgba(172, 7, 66, 0.1);
    }

    .form-input:disabled,
    .form-select:disabled {
        background: #f5f5f5;
        cursor: not-allowed;
        color: #666;
    }

    .form-textarea {
        min-height: 100px;
        resize: vertical;
        font-family: inherit;
    }

    .info-badge {
        display: inline-block;
        padding: 6px 12px;
        background: linear-gradient(135deg, #e8f5e8, #4CAF50);
        color: #1b5e20;
        border-radius: 20px;
        font-size: 12px;
        font-weight: 600;
        margin-top: 5px;
    }

    .form-actions {
        display: flex;
        gap: 15px;
        justify-content: flex-end;
        margin-top: 35px;
        padding-top: 25px;
        border-top: 2px solid #e0e0e0;
    }

    .btn {
        padding: 14px 30px;
        border: none;
        border-radius: 25px;
        font-weight: 600;
        font-size: 15px;
        cursor: pointer;
        transition: all 0.3s ease;
        text-decoration: none;
        display: inline-flex;
        align-items: center;
        gap: 8px;
    }

    .btn-primary {
        background: linear-gradient(135deg, #ac0742, #9d1955);
        color: white;
        box-shadow: 0 4px 15px rgba(172, 7, 66, 0.4);
    }

    .btn-primary:hover {
        transform: translateY(-2px);
        box-shadow: 0 6px 20px rgba(172, 7, 66, 0.6);
    }

    .btn-secondary {
        background: #e0e0e0;
        color: #333;
    }

    .btn-secondary:hover {
        background: #d0d0d0;
        transform: translateY(-2px);
    }

    .alert {
        padding: 15px 20px;
        border-radius: 8px;
        margin-bottom: 20px;
        display: flex;
        align-items: center;
        gap: 10px;
    }

    .alert-success {
        background: #d4edda;
        color: #155724;
        border-left: 4px solid #28a745;
    }

    .alert-error {
        background: #f8d7da;
        color: #721c24;
        border-left: 4px solid #dc3545;
    }

    @media (max-width: 768px) {
        .form-grid {
            grid-template-columns: 1fr;
        }

        .form-container {
            margin: 20px;
            padding: 25px;
        }

        .form-actions {
            flex-direction: column;
        }

        .btn {
            width: 100%;
            justify-content: center;
        }
    }
</style>

<div class="form-container">
    <div class="form-header"></div>

    @if(session('success'))
    <div class="alert alert-success">
        <span>✓</span> {{ session('success') }}
    </div>
    @endif

    @if($errors->any())
    <div class="alert alert-error">
        <span>⚠</span>
        <ul style="margin: 0; padding-left: 20px;">
            @foreach($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
    @endif

    <form action="{{ route('sale.store') }}" method="POST" id="salesForm">
        @csrf

        <div class="form-grid">
            <script>
                // Plan to amount mapping
                const planPrices = {
                    'Elite': 18000,
                    'Assisted': 12000,
                    'Premium': 9000,
                    'Basic': 6000,
                    'Standard': 4000
                };

                document.addEventListener('DOMContentLoaded', function() {
                    const planSelect = document.querySelector('select[name="plan"]');
                    const amountInput = document.getElementById('amount');
                    const paidInput = document.getElementById('paid_amount');
                    const discountInput = document.getElementById('discount');
                    const successFeeInput = document.getElementById('success_fee');
                    const successFeeGroup = successFeeInput ? successFeeInput.closest('.form-group') : null;
                    const discountGroup = discountInput ? discountInput.closest('.form-group') : null;

                    // Show/hide success fee based on plan
                    function updateSuccessFeeVisibility() {
                        if (!planSelect || !successFeeGroup || !discountGroup) return;
                        const show = ['Elite', 'Premium', 'Service', 'Assisted'].includes(planSelect.value);
                        successFeeGroup.style.display = show ? '' : 'none';
                        discountGroup.style.display = show ? 'none' : '';
                    }

                    if (planSelect) {
                        planSelect.addEventListener('change', function() {
                            const price = planPrices[this.value] || '';
                            if (amountInput) amountInput.value = price;
                            // Update success fee if paid amount is present
                            if (paidInput && successFeeInput) {
                                const paid = parseFloat(paidInput.value) || 0;
                                successFeeInput.value = price ? (price - paid) : '';
                            }
                            updateSuccessFeeVisibility();
                        });
                        // Initial call
                        updateSuccessFeeVisibility();
                    }

                    // When paid amount changes, set success fee
                    if (paidInput && amountInput && successFeeInput) {
                        paidInput.addEventListener('input', function() {
                            const total = parseFloat(amountInput.value) || 0;
                            const paid = parseFloat(this.value) || 0;
                            successFeeInput.value = total - paid;
                        });
                    }

                    // When paid amount or amount changes, set discount automatically
                    function updateDiscount() {
                        if (!amountInput || !paidInput || !discountInput) return;
                        const total = parseFloat(amountInput.value) || 0;
                        const paid = parseFloat(paidInput.value) || 0;
                        discountInput.value = total - paid;
                    }
                    if (paidInput && amountInput && discountInput) {
                        paidInput.addEventListener('input', updateDiscount);
                        amountInput.addEventListener('input', updateDiscount);
                    }
                });
            </script>


            <!-- Date Field -->
            <div class="form-group">
                <label class="form-label">Date<span class="required">*</span></label>
                <input type="date" name="date" class="form-input" value="{{ old('date', date('Y-m-d')) }}" required>
            </div>

            <!-- ID Field (Manual Entry) -->
            <div class="form-group">
                <label class="form-label">ID<span class="required">*</span></label>
                <input type="text" name="profile_id" id="profile_id" class="form-input" 
                       value="{{ old('profile_id') }}" placeholder="Enter ID manually" required>
            </div>

            <!-- Customer Name Field (Manual Entry) -->
            <div class="form-group">
                <label class="form-label">Customer Name<span class="required">*</span></label>
                <input type="text" name="name" id="name" class="form-input" 
                       value="{{ old('name') }}" placeholder="Enter Customer Name" required>
            </div>

            <!-- Plan Field (Dropdown) -->
            <div class="form-group">
                <label class="form-label">Plan<span class="required">*</span></label>
                <select name="plan" class="form-select" required>
                    <option value="">Select Plan</option>
                    <option value="Elite" {{ old('plan') == 'Elite' ? 'selected' : '' }}>Elite</option>
                    <option value="Assisted" {{ old('plan') == 'Assisted' ? 'selected' : '' }}>Assisted</option>
                    <option value="Premium" {{ old('plan') == 'Premium' ? 'selected' : '' }}>Premium</option>
                    <option value="Basic" {{ old('plan') == 'Basic' ? 'selected' : '' }}>Basic</option>
                    <option value="Standard" {{ old('plan') == 'Standard' ? 'selected' : '' }}>Standard</option>
                    <option value="Service" {{ old('plan') == 'Service' ? 'selected' : '' }}>Service</option>
                </select>
            </div>

            <!-- Amount Field -->
            <div class="form-group">
                <label class="form-label">Amount<span class="required">*</span></label>
                <input type="number" name="amount" id="amount" class="form-input" 
                       value="{{ old('amount') }}" placeholder="0.00" step="0.01" min="0" required>
            </div>


            <!-- Paid Amount Field -->
            <div class="form-group">
                <label class="form-label">Paid Amount</label>
                <input type="number" name="paid_amount" id="paid_amount" class="form-input" 
                       value="{{ old('paid_amount') }}" placeholder="0.00" step="0.01" min="0">
            </div>

            <!-- Discount Field -->
            <div class="form-group">
                <label class="form-label">Discount</label>
                <input type="number" name="discount" id="discount" class="form-input" 
                       value="{{ old('discount') }}" placeholder="0.00" step="0.01" min="0">
            </div>

            <!-- Success Fee Field -->
            <div class="form-group">
                <label class="form-label">Success Fee</label>
                <input type="number" name="success_fee" id="success_fee" class="form-input" 
                       value="{{ old('success_fee', 0) }}" placeholder="0.00" step="0.01" min="0">
            </div>

            <!-- Service Executive Field -->
            <div class="form-group">
                <label class="form-label">Service Executive<span class="required">*</span></label>
                <select name="executive" id="executive" class="form-select" required>
                    <option value="">Select Service Executive</option>
                    @forelse($serviceExecutives as $executive)
                        <option value="{{ $executive->first_name }}" 
                                {{ old('executive') == $executive->first_name ? 'selected' : '' }}>
                            {{ $executive->first_name }}
                        </option>
                    @empty
                        <option value="">No service executives available</option>
                    @endforelse
                </select>
            </div>

            <!-- Status Field -->
            <div class="form-group">
                <label class="form-label">Status<span class="required">*</span></label>
                <select name="status" class="form-select" required>
                    <option value="">Select Status</option>
                    <option value="new" {{ old('status') == 'new' ? 'selected' : '' }}>New</option>
                    <option value="active" {{ old('status', 'active') == 'active' ? 'selected' : '' }}>Active</option>
                    <option value="completed" {{ old('status') == 'completed' ? 'selected' : '' }}>Completed</option>
                    <option value="pending" {{ old('status') == 'pending' ? 'selected' : '' }}>Pending</option>
                    <option value="cancelled" {{ old('status') == 'cancelled' ? 'selected' : '' }}>Cancelled</option>
                </select>
            </div>

            <!-- Office Field -->
            <div class="form-group">
                <label class="form-label">Office<span class="required">*</span></label>
                <select name="office" class="form-select" required>
                    <option value="">Select Office</option>
                    <option value="Head Office" {{ old('office') == 'Head Office' ? 'selected' : '' }}>Head Office</option>
                    <option value="Branch 1" {{ old('office') == 'Branch 1' ? 'selected' : '' }}>Branch 1</option>
                    <option value="Branch 2" {{ old('office') == 'Branch 2' ? 'selected' : '' }}>Branch 2</option>
                    <option value="Regional Office" {{ old('office') == 'Regional Office' ? 'selected' : '' }}>Regional Office</option>
                </select>
            </div>

            <!-- Notes/Remarks Field (Full Width) -->
            <div class="form-group full-width">
                <label class="form-label">Notes/Remarks</label>
                <textarea name="notes" class="form-textarea" placeholder="Enter any additional notes or remarks...">{{ old('notes') }}</textarea>
            </div>
        </div>

        <div class="form-actions">
            <a href="{{ route('services.page') }}" class="btn btn-secondary">
                ← Cancel
            </a>
            <button type="submit" class="btn btn-primary">
                💾 Submit Sale
            </button>
        </div>
    </form>
</div>

@endsection