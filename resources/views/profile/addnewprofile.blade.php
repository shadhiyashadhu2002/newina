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

    .add-profiles-wrapper {
        min-height: 100vh;
        padding: 40px 20px;
    }

    .add-profiles-container {
        max-width: 600px;
        margin: 0 auto;
        background: rgba(255, 255, 255, 0.85);
        backdrop-filter: blur(10px);
        border-radius: 12px;
        padding: 40px;
        box-shadow: 0 8px 32px rgba(0, 0, 0, 0.1);
    }

    .add-profiles-header {
        margin-bottom: 40px;
    }

    .add-profiles-header h1 {
        font-size: 30px;
        font-weight: 600;
        color: #10b981; /* Green */
        margin-bottom: 15px;
        letter-spacing: 0.5px;
        text-shadow: 0 2px 8px rgba(16,185,129,0.10);
    }

    .add-profiles-header-line {
        height: 1px;
        background: linear-gradient(90deg, #d1d5db 0%, transparent 100%);
    }

    .add-profiles-form-group {
        margin-bottom: 25px;
    }

    .add-profiles-form-group label {
        display: block;
        font-size: 15px;
        color: #222; /* Black for labels */
        margin-bottom: 8px;
        font-weight: 400;
        letter-spacing: 0.2px;
    }

    .add-profiles-form-control {
        width: 100%;
        padding: 14px 18px;
        border: 1.5px solid #e5e7eb;
        border-radius: 8px;
        font-size: 16px;
        color: #222;
        background: #fff;
        font-weight: 400;
        box-shadow: 0 2px 12px rgba(0,0,0,0.04);
        transition: all 0.2s ease;
    }

    .add-profiles-form-control:focus {
        outline: none;
        border-color: #10b981;
        box-shadow: 0 0 0 3px rgba(16, 185, 129, 0.1);
    }

    .add-profiles-form-control[readonly] {
        background: #f8f9fa;
        color: #495057;
        cursor: not-allowed;
        font-weight: 600;
    }

    .add-profiles-form-control.date-field {
        background: #fff;
        color: #222;
        cursor: pointer;
    }

    .add-profiles-form-control.date-field::-webkit-calendar-picker-indicator {
        cursor: pointer;
    }

    select.add-profiles-form-control {
        cursor: pointer;
        appearance: none;
        background-image: url("data:image/svg+xml;charset=utf-8,%3Csvg xmlns='http://www.w3.org/2000/svg' fill='none' viewBox='0 0 20 20'%3E%3Cpath stroke='%236b7280' stroke-linecap='round' stroke-linejoin='round' stroke-width='1.5' d='M6 8l4 4 4-4'/%3E%3C/svg%3E");
        background-position: right 12px center;
        background-repeat: no-repeat;
        background-size: 16px;
        padding-right: 40px;
    }

    .add-profiles-checkbox-group {
        display: flex;
        align-items: center;
        gap: 12px;
        margin-bottom: 25px;
    }

    .add-profiles-checkbox-group label {
        font-size: 13px;
        color: #6b7280;
        margin-bottom: 0;
    }

    .add-profiles-checkbox-group input[type="checkbox"] {
        width: 16px;
        height: 16px;
        accent-color: #10b981;
        cursor: pointer;
    }

    .add-profiles-form-control.textarea {
        resize: none;
        min-height: 100px;
        font-family: inherit;
    }

    .add-profiles-button-group {
        display: flex;
        gap: 16px;
        margin-top: 40px;
    }

    .add-profiles-btn {
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

    .add-profiles-btn-primary {
        background: linear-gradient(135deg, #10b981 0%, #059669 100%);
        color: white;
    }

    .add-profiles-btn-primary:hover {
        background: linear-gradient(135deg, #059669 0%, #047857 100%);
        transform: translateY(-1px);
        box-shadow: 0 4px 12px rgba(16, 185, 129, 0.3);
        color: white;
        text-decoration: none;
    }

    .add-profiles-btn-secondary {
        background: linear-gradient(135deg, #f87171 0%, #ef4444 100%);
        color: white;
    }

    .add-profiles-btn-secondary:hover {
        background: linear-gradient(135deg, #ef4444 0%, #dc2626 100%);
        transform: translateY(-1px);
        box-shadow: 0 4px 12px rgba(248, 113, 113, 0.3);
        color: white;
        text-decoration: none;
    }
</style>

<div class="add-profiles-wrapper">
    <div class="add-profiles-container">
        <!-- Header -->
        <div class="add-profiles-header">
            <h1>Add New Profiles</h1>
            <div class="add-profiles-header-line"></div>
        </div>

        <!-- Form -->
        <form action="{{ route('profile.store') }}" method="POST">
            @csrf
            
            <!-- Profile ID -->
            <div class="add-profiles-form-group">
                <label for="profile_id">Profile ID</label>
                <input type="text" 
                       id="profile_id"
                       name="code"
                       value="{{ old('code') }}" 
                       class="add-profiles-form-control"
                       placeholder="Enter profile ID">
            </div>

            <!-- Name -->
            <div class="add-profiles-form-group">
                <label for="name">Name</label>
                <input type="text" 
                       id="name"
                       name="first_name" 
                       value="{{ old('first_name') }}"
                       class="add-profiles-form-control"
                       placeholder="Enter name">
            </div>

            <!-- Gender -->
            <div class="add-profiles-form-group">
                <label for="gender">Gender</label>
                <select id="gender" 
                        name="gender" 
                        class="add-profiles-form-control">
                    <option value="">Select Gender</option>
                    <option value="Male" {{ old('gender') == 'Male' ? 'selected' : '' }}>Male</option>
                    <option value="Female" {{ old('gender') == 'Female' ? 'selected' : '' }}>Female</option>
                    <option value="Other" {{ old('gender') == 'Other' ? 'selected' : '' }}>Other</option>
                </select>
            </div>

            <!-- Registration Date -->
            <div class="add-profiles-form-group">
                <label for="registration_date">Registration Date</label>
                <input type="date" 
                       id="registration_date"
                       name="registration_date"
                       value="{{ old('registration_date') }}" 
                       class="add-profiles-form-control date-field">
            </div>

            <!-- Verified Date -->
            <div class="add-profiles-form-group">
                <label for="verified_date">Verified Date</label>
                <input type="date" 
                       id="verified_date"
                       name="verified_date"
                       value="{{ old('verified_date') }}" 
                       class="add-profiles-form-control date-field">
            </div>

            <!-- Mobile Number 1 -->
            <div class="add-profiles-form-group">
                <label for="mobile_number_1">Mobile Number 1</label>
                <input type="tel" 
                       id="mobile_number_1"
                       name="phone" 
                       value="{{ old('phone') }}"
                       class="add-profiles-form-control"
                       placeholder="Enter mobile number">
            </div>

            <!-- Mobile Number 2 -->
            <div class="add-profiles-form-group">
                <label for="mobile_number_2">Mobile Number 2</label>
                <input type="tel" 
                       id="mobile_number_2"
                       name="phone2" 
                       value="{{ old('phone2') }}"
                       class="add-profiles-form-control"
                       placeholder="Enter mobile number 2">
            </div>

            <!-- WhatsApp Number -->
            <div class="add-profiles-form-group">
                <label for="whatsapp_number">WhatsApp Number</label>
                <input type="tel" 
                       id="whatsapp_number"
                       name="whatsapp_phone" 
                       value="{{ old('whatsapp_phone') }}"
                       class="add-profiles-form-control"
                       placeholder="Enter WhatsApp number">
            </div>

            <!-- Welcome Call Completed -->
            <div class="add-profiles-checkbox-group">
                <label for="welcome_call">Welcome Call is Completed? (Please Tick)</label>
                <input type="checkbox" 
                       id="welcome_call"
                       name="welcome_call_completed" 
                       value="1"
                       {{ old('welcome_call_completed') ? 'checked' : '' }}>
            </div>

            <!-- Comments -->
            <div class="add-profiles-form-group">
                <label for="comments">Comments</label>
                <textarea id="comments"
                          name="comments" 
                          rows="4" 
                          class="add-profiles-form-control textarea"
                          placeholder="Enter comments">{{ old('comments') }}</textarea>
            </div>

            <!-- Buttons -->
            <div class="add-profiles-button-group">
                <button type="submit" class="add-profiles-btn add-profiles-btn-primary">
                    Add
                </button>
                <a href="{{ route('profile.hellow') }}" class="add-profiles-btn add-profiles-btn-secondary">
                    Back
                </a>
            </div>
        </form>
    </div>
</div>
@endsection