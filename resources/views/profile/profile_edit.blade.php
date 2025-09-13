        
@extends('layouts.app')

@section('content')
<style>
    * {
        box-sizing: border-box;
    }

    body {
        font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif;
        background: linear-gradient(135deg, #f8f9fa 0%, #e8f5f0 50%, #d1f2eb 100%);
        min-height: 100vh;
        margin: 0;
        padding: 0;
    }

    .edit-profiles-wrapper {
        min-height: 100vh;
        padding: 40px 20px;
    }

    .edit-profiles-container {
        max-width: 600px;
        margin: 0 auto;
        background: rgba(255, 255, 255, 0.85);
        backdrop-filter: blur(10px);
        border-radius: 12px;
        padding: 40px;
        box-shadow: 0 8px 32px rgba(0, 0, 0, 0.1);
    }

    .edit-profiles-header {
        margin-bottom: 40px;
    }

    .edit-profiles-header h1 {
        font-size: 28px;
        font-weight: 300;
        color: #6b7280;
        margin-bottom: 15px;
        letter-spacing: 0.5px;
    }

    .edit-profiles-header-line {
        height: 1px;
        background: linear-gradient(90deg, #d1d5db 0%, transparent 100%);

<div class="edit-profile-form">
    <div class="edit-profile-header">Edit Profile</div>
    <form action="{{ route('profile.update', $user->id) }}" method="POST">
        @csrf
        @method('PUT')
        <div class="form-group">
            <label for="code">Profile ID</label>
            <input type="text" id="code" name="code" class="form-control" value="{{ $user->code }}" readonly>
        </div>
        <div class="form-group">
            <label for="first_name">Name</label>
            <input type="text" id="first_name" name="first_name" class="form-control" value="{{ $user->first_name }}">
        </div>
        <div class="form-group">
            <label for="gender">Gender</label>
            <input type="text" id="gender" name="gender" class="form-control" value="{{ $gender ?? $user->gender }}" readonly>
        </div>
        <div class="form-group">
            <label for="created_at">Registration Date</label>
            <input type="text" id="created_at" name="created_at" class="form-control" value="{{ $user->created_at ? $user->created_at->format('d/m/Y') : '' }}" readonly>
        </div>
        <div class="form-group">
            <label for="verified_date">Verified Date</label>
            <input type="text" id="verified_date" name="verified_date" class="form-control" value="{{ $user->created_at ? $user->created_at->format('d/m/Y') : '' }}" readonly>
        </div>
        <div class="form-group">
            <label for="phone">Mobile Number 1</label>
            <input type="tel" id="phone" name="phone" class="form-control" value="{{ $user->phone }}">
        </div>
        <div class="form-group">
            <label for="phone2">Mobile Number 2</label>
            <input type="tel" id="phone2" name="phone2" class="form-control" value="{{ $user->phone2 }}">
        </div>
        <div class="form-group">
            <label for="whatsapp_number">WhatsApp Number</label>
            <input type="tel" id="whatsapp_number" name="whatsapp_phone" class="form-control" value="{{ $user->whatsapp_number }}">
        </div>
        <div class="form-group">
            <label for="comments">Comments</label>
            <textarea id="comments" name="comments" class="form-control" rows="3">{{ $user->comments }}</textarea>
        </div>
        <button type="submit" class="btn-primary">Update</button>
    </form>
</div>
    }

    .edit-profiles-form-group {
        margin-bottom: 25px;
    }

    .edit-profiles-form-group label {
        display: block;
        font-size: 13px;
        color: #6b7280;
        margin-bottom: 8px;
        font-weight: 400;
    }

    .edit-profiles-form-control {
        width: 100%;
        padding: 12px 16px;
        border: 1px solid #e5e7eb;
        border-radius: 6px;
        font-size: 14px;
        color: #374151;
        background: #ffffff;
        transition: all 0.2s ease;
    }

    .edit-profiles-form-control:focus {
        outline: none;
        border-color: #10b981;
        box-shadow: 0 0 0 3px rgba(16, 185, 129, 0.1);
    }

    .edit-profiles-form-control[readonly] {
        background: #f8f9fa; /* Slightly brighter readonly background */
        color: #495057; /* Darker readonly text */
        cursor: not-allowed;
        font-weight: 600; /* Bold readonly text */
    }

    .edit-profiles-form-control.date-field {
        background: #f8f9fa; /* Slightly brighter date field background */
        color: #495057; /* Darker date field text */
        font-weight: 600; /* Bold date field text */
    }

    select.edit-profiles-form-control {
        cursor: pointer;
        appearance: none;
        background-image: url("data:image/svg+xml;charset=utf-8,%3Csvg xmlns='http://www.w3.org/2000/svg' fill='none' viewBox='0 0 20 20'%3E%3Cpath stroke='%236b7280' stroke-linecap='round' stroke-linejoin='round' stroke-width='1.5' d='M6 8l4 4 4-4'/%3E%3C/svg%3E");
        background-position: right 12px center;
        background-repeat: no-repeat;
        background-size: 16px;
        padding-right: 40px;
    }

    .edit-profiles-checkbox-group {
        display: flex;
        align-items: center;
        gap: 12px;
        margin-bottom: 25px;
    }

    .edit-profiles-checkbox-group label {
        font-size: 13px;
        color: #6b7280;
        margin-bottom: 0;
    }

    .edit-profiles-checkbox-group input[type="checkbox"] {
        width: 16px;
        height: 16px;
        accent-color: #10b981;
        cursor: pointer;
    }

    .edit-profiles-form-control.textarea {
        resize: none;
        min-height: 100px;
        font-family: inherit;
    }

    .edit-profiles-button-group {
        display: flex;
        gap: 16px;
        margin-top: 40px;
    }

    .edit-profiles-btn {
        flex: 1;
        padding: 14px 24px;
        border: none;
        border-radius: 6px;
        font-size: 14px;
        font-weight: 500;
        cursor: pointer;
        text-decoration: none;
        text-align: center;
        transition: all 0.2s ease;
        display: inline-block;
    }

    .edit-profiles-btn-primary {
        background: linear-gradient(135deg, #10b981 0%, #059669 100%);
        color: white;
    }

    .edit-profiles-btn-primary:hover {
        background: linear-gradient(135deg, #059669 0%, #047857 100%);
        transform: translateY(-1px);
        box-shadow: 0 4px 12px rgba(16, 185, 129, 0.3);
        color: white;
        text-decoration: none;
    }

    .edit-profiles-btn-secondary {
        background: linear-gradient(135deg, #f87171 0%, #ef4444 100%);
        color: white;
    }

    .edit-profiles-btn-secondary:hover {
        background: linear-gradient(135deg, #ef4444 0%, #dc2626 100%);
        transform: translateY(-1px);
        box-shadow: 0 4px 12px rgba(248, 113, 113, 0.3);
        color: white;
        text-decoration: none;
    }

    .edit-profiles-mobile-icon {
        font-size: 16px;
    }
</style>

<div class="edit-profiles-wrapper">
    <div class="edit-profiles-container">
        <!-- Header -->
        <div class="edit-profiles-header">
            <h1>Edit Profiles</h1>
            <div class="edit-profiles-header-line"></div>
        </div>

        <!-- Form -->
        <form action="{{ route('profile.update', $user->id ?? 1) }}" method="POST">
            @csrf
            @method('PUT')
            
            <!-- Profile ID -->
            <div class="edit-profiles-form-group">
                <label for="profile_id">Profile ID</label>
                <input type="text" 
                       id="profile_id"
                       name="code"
                       value="{{ $user->code ?? '23626' }}" 
                       readonly
                       class="edit-profiles-form-control">
            </div>

            <!-- Name -->
            <div class="edit-profiles-form-group">
                <label for="name">Name</label>
                <input type="text" 
                       id="name"
                       name="first_name" 
                       value="{{ old('first_name', $user->first_name ?? 'mohamed salih') }}"
                       class="edit-profiles-form-control">
            </div>

            <!-- Gender -->
            <div class="edit-profiles-form-group">
                <label for="gender">Gender</label>
                <select id="gender" 
                        name="gender" 
                        class="edit-profiles-form-control">
                    <option value="Male" {{ (old('gender', $user->member->gender ?? 'Male') == 'Male') ? 'selected' : '' }}>Male</option>
                    <option value="Female" {{ (old('gender', $user->member->gender ?? '') == 'Female') ? 'selected' : '' }}>Female</option>
                    <option value="Other" {{ (old('gender', $user->member->gender ?? '') == 'Other') ? 'selected' : '' }}>Other</option>
                </select>
            </div>

            <!-- Registration Date -->
            <div class="edit-profiles-form-group">
                <label for="registration_date">Registration Date</label>
                <input type="text" 
                       id="registration_date"
                       name="registration_date"
                       value="{{ $user->created_at ? $user->created_at->format('d/m/Y') : '06/10/2024' }}" 
                       readonly
                       class="edit-profiles-form-control date-field">
            </div>

            <!-- Verified Date -->
            <div class="edit-profiles-form-group">
                <label for="verified_date">Verified Date</label>
                <input type="text" 
                       id="verified_date"
                       name="verified_date"
                       value="{{ $user->created_at ? $user->created_at->format('d/m/Y') : '06/10/2024' }}" 
                       readonly
                       class="edit-profiles-form-control date-field">
            </div>

            <!-- Mobile Number 1 -->
            <div class="edit-profiles-form-group">
                <label for="mobile_number_1">Mobile Number 1</label>
                <input type="tel" 
                       id="mobile_number_1"
                       name="phone" 
                       value="{{ old('phone', $user->phone ?? '0') }}"
                       class="edit-profiles-form-control">
            </div>

            <!-- Mobile Number 2 -->
            <div class="edit-profiles-form-group">
                <label for="mobile_number_2">Mobile Number 2</label>
                <input type="tel" 
                       id="mobile_number_2"
                       name="phone2" 
                       value="{{ old('phone2', $user->phone2 ?? '0') }}"
                       class="edit-profiles-form-control">
            </div>

            <!-- WhatsApp Number -->
            <div class="edit-profiles-form-group">
                <label for="whatsapp_number">WhatsApp Number</label>
                <input type="tel" 
                       id="whatsapp_number"
                       name="whatsapp_phone" 
                       value="{{ old('whatsapp_phone', $user->phone ?? '0') }}"
                       class="edit-profiles-form-control">
            </div>

            <!-- Welcome Call Completed -->
            <div class="edit-profiles-checkbox-group">
                <label for="welcome_call">Welcome Call is Completed? (Please Tick)</label>
                <input type="checkbox" 
                       id="welcome_call"
                       name="welcome_call_completed" 
                       value="1"
                       {{ old('welcome_call_completed', $user->welcome_call_completed ?? false) ? 'checked' : '' }}>
            </div>

            <!-- Comments -->
            <div class="edit-profiles-form-group">
                <label for="comments">Comments</label>
                <textarea id="comments"
                          name="comments" 
                          rows="4" 
                          class="edit-profiles-form-control textarea">{{ old('comments', $user->comments ?? '') }}</textarea>
            </div>

            <!-- Buttons -->
            <div class="edit-profiles-button-group">
                <button type="submit" class="edit-profiles-btn edit-profiles-btn-primary">
                    Update
                </button>
                <a href="{{ route('profile.hellow') }}" class="edit-profiles-btn edit-profiles-btn-secondary">
                    Back
                </a>
            </div>
        </form>
    </div>
</div>
@endsection